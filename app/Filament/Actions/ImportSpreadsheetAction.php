<?php

namespace App\Filament\Actions;

use DateInterval;
use DateTimeInterface;
use Filament\Actions\ImportAction;
use Filament\Actions\Imports\ImportColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use League\Csv\Reader as CsvReader;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use OpenSpout\Reader\Common\Creator\ReaderFactory;

class ImportSpreadsheetAction extends ImportAction
{
    /**
     * @var array<string, array<string>>
     */
    protected array $xlsxSheetNamesCache = [];

    protected function setUp(): void
    {
        parent::setUp();

        $defaultSchema = $this->schema;

        $this->schema(function (ImportAction $action) use ($defaultSchema): array {
            $components = $this->evaluate($defaultSchema);

            if (! is_array($components)) {
                return $components;
            }

            $fileComponentIndex = null;

            foreach ($components as $index => $component) {
                if (! $component instanceof FileUpload) {
                    continue;
                }

                $component
                    ->acceptedFileTypes([
                        'text/csv',
                        'text/x-csv',
                        'application/csv',
                        'application/x-csv',
                        'text/comma-separated-values',
                        'text/x-comma-separated-values',
                        'text/plain',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->afterStateUpdated(function (Set $set, ?TemporaryUploadedFile $state): void {
                        if (! $state instanceof TemporaryUploadedFile) {
                            $set('sheet_name', null);

                            return;
                        }

                        if (! $this->isXlsxFile($state)) {
                            $set('sheet_name', null);

                            return;
                        }

                        $sheetNames = $this->getXlsxSheetNames($state);
                        $set('sheet_name', $sheetNames[0] ?? null);

                        $this->syncColumnMapFromUploadedFile($set, $state);
                    });

                $fileComponentIndex = $index;
                break;
            }

            if ($fileComponentIndex !== null) {
                array_splice($components, $fileComponentIndex + 1, 0, [
                    Select::make('sheet_name')
                        ->label('Sheet')
                        ->options(function (Get $get): array {
                            $file = $get('file');

                            if (! $file instanceof TemporaryUploadedFile || ! $this->isXlsxFile($file)) {
                                return [];
                            }

                            $sheetNames = $this->getXlsxSheetNames($file);

                            return array_combine($sheetNames, $sheetNames) ?: [];
                        })
                        ->visible(fn (Get $get): bool => $get('file') instanceof TemporaryUploadedFile && $this->isXlsxFile($get('file')))
                        ->required(fn (Get $get): bool => $get('file') instanceof TemporaryUploadedFile && $this->isXlsxFile($get('file')))
                        ->live()
                        ->afterStateUpdated(function (Set $set, Get $get): void {
                            $file = $get('file');

                            if (! $file instanceof TemporaryUploadedFile) {
                                return;
                            }

                            $this->syncColumnMapFromUploadedFile($set, $file);
                        }),
                ]);
            }

            return $components;
        });
    }

    /**
     * @return array<mixed>
     */
    public function getFileValidationRules(): array
    {
        $rules = parent::getFileValidationRules();

        if (($rules[0] ?? null) === 'extensions:csv,txt') {
            $rules[0] = 'extensions:csv,txt,xlsx';
        }

        return $rules;
    }

    /**
     * @return resource|false
     */
    public function getUploadedFileStream(TemporaryUploadedFile $file)
    {
        if (! $this->isXlsxFile($file)) {
            return parent::getUploadedFileStream($file);
        }

        return $this->convertXlsxToCsvStream($file);
    }

    protected function isXlsxFile(TemporaryUploadedFile $file): bool
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        return $extension === 'xlsx';
    }

    /**
     * @return array<string>
     */
    protected function getXlsxSheetNames(TemporaryUploadedFile $file): array
    {
        $filePath = $file->getRealPath();

        if (! $filePath || ! is_file($filePath)) {
            return [];
        }

        $cacheKey = $filePath . ':' . ((string) @filesize($filePath)) . ':' . ((string) @filemtime($filePath));

        if (isset($this->xlsxSheetNamesCache[$cacheKey])) {
            return $this->xlsxSheetNamesCache[$cacheKey];
        }

        $reader = ReaderFactory::createFromFile($filePath);
        $reader->open($filePath);

        try {
            $names = [];

            foreach ($reader->getSheetIterator() as $sheet) {
                $names[] = $sheet->getName();
            }
        } finally {
            $reader->close();
        }

        $this->xlsxSheetNamesCache[$cacheKey] = $names;

        return $names;
    }

    protected function getSelectedSheetName(?TemporaryUploadedFile $file = null): ?string
    {
        $selected = null;

        try {
            $rawData = $this->getRawData();
            $selected = $rawData['sheet_name'] ?? null;
        } catch (\Throwable) {
            // Ignore if action state is not mounted yet.
        }

        if (! filled($selected)) {
            $selected = $this->getData()['sheet_name'] ?? null;
        }

        if (filled($selected)) {
            return (string) $selected;
        }

        if ($file instanceof TemporaryUploadedFile) {
            return $this->getXlsxSheetNames($file)[0] ?? null;
        }

        return null;
    }

    /**
     * @return resource|false
     */
    protected function convertXlsxToCsvStream(TemporaryUploadedFile $file)
    {
        $filePath = $file->getRealPath();

        if (! $filePath || ! is_file($filePath)) {
            return false;
        }

        $sheetNames = $this->getXlsxSheetNames($file);

        if ($sheetNames === []) {
            return false;
        }

        $selectedSheet = $this->getSelectedSheetName($file);

        if (! in_array($selectedSheet, $sheetNames, true)) {
            $selectedSheet = $sheetNames[0];
        }

        $reader = ReaderFactory::createFromFile($filePath);
        $reader->open($filePath);

        $stream = fopen('php://temp', 'r+');

        try {
            $delimiter = $this->getCsvDelimiter() ?? ',';

            foreach ($reader->getSheetIterator() as $sheet) {
                if ($sheet->getName() !== $selectedSheet) {
                    continue;
                }

                foreach ($sheet->getRowIterator() as $row) {
                    $values = array_map(
                        fn (mixed $value): string => $this->normalizeCellValue($value),
                        $row->toArray(),
                    );

                    fputcsv($stream, $values, $delimiter);
                }

                break;
            }
        } finally {
            $reader->close();
        }

        rewind($stream);

        return $stream;
    }

    protected function normalizeCellValue(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if ($value instanceof DateInterval) {
            return $value->format('%RP%yY%mM%dDT%hH%iM%sS');
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return (string) $value;
    }

    protected function syncColumnMapFromUploadedFile(Set $set, TemporaryUploadedFile $file): void
    {
        $csvStream = $this->getUploadedFileStream($file);

        if (! $csvStream) {
            return;
        }

        $csvReader = CsvReader::createFromStream($csvStream);

        if (filled($csvDelimiter = $this->getCsvDelimiter($csvReader))) {
            $csvReader->setDelimiter($csvDelimiter);
        }

        $csvReader->setHeaderOffset($this->getHeaderOffset() ?? 0);
        $csvColumns = $csvReader->getHeader();

        $lowercaseCsvColumnValues = array_map(Str::lower(...), $csvColumns);
        $lowercaseCsvColumnKeys = array_combine($lowercaseCsvColumnValues, $csvColumns);

        $set('columnMap', array_reduce($this->getImporter()::getColumns(), function (array $carry, ImportColumn $column) use ($lowercaseCsvColumnKeys, $lowercaseCsvColumnValues): array {
            $match = Arr::first(
                array_intersect(
                    $lowercaseCsvColumnValues,
                    $column->getGuesses(),
                ),
            );

            $carry[$column->getName()] = $lowercaseCsvColumnKeys[$match] ?? null;

            return $carry;
        }, []));
    }
}

