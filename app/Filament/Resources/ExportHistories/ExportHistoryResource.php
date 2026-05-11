<?php

namespace App\Filament\Resources\ExportHistories;

use App\Filament\Resources\ExportHistories\Pages\ListExportHistories;
use App\Filament\Resources\ExportHistories\Pages\ViewExportHistory;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export as ExportModel;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ExportHistoryResource extends Resource
{
    protected static ?string $model = ExportModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static ?string $navigationLabel = 'Lịch sử export';

    protected static ?string $modelLabel = 'Lịch sử export';

    protected static ?string $pluralModelLabel = 'Lịch sử export';

    protected static string|\UnitEnum|null $navigationGroup = 'Hệ thống';

    protected static ?int $navigationSort = 91;

    protected static bool $shouldSkipAuthorization = true;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('file_name')
                    ->label('File export')
                    ->searchable()
                    ->placeholder('Chưa tạo tên file')
                    ->wrap(),
                TextColumn::make('exporter')
                    ->label('Loại dữ liệu')
                    ->formatStateUsing(fn (?string $state): string => static::formatExporter($state))
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->state(fn (ExportModel $record): string => static::statusLabel($record))
                    ->badge()
                    ->color(fn (ExportModel $record): string => static::statusColor($record)),
                IconColumn::make('file_available')
                    ->label('File còn lưu')
                    ->state(fn (ExportModel $record): bool => static::hasDownloadableFile($record))
                    ->boolean(),
                TextColumn::make('processed_rows')
                    ->label('Đã xử lý')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('successful_rows')
                    ->label('Thành công')
                    ->numeric()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('failed_rows')
                    ->label('Lỗi')
                    ->state(fn (ExportModel $record): int => $record->getFailedRowsCount())
                    ->numeric()
                    ->color(fn (int $state): string => $state > 0 ? 'danger' : 'success'),
                TextColumn::make('total_rows')
                    ->label('Tổng dòng')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Người export')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'processing' => 'Đang xử lý',
                        'completed' => 'Hoàn tất',
                        'completed_with_errors' => 'Hoàn tất có lỗi',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'processing' => $query->whereNull('completed_at'),
                            'completed' => $query->whereNotNull('completed_at')->whereColumn('successful_rows', 'total_rows'),
                            'completed_with_errors' => $query->whereNotNull('completed_at')->whereColumn('successful_rows', '<', 'total_rows'),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Chi tiết')
                    ->modalHeading(fn (ExportModel $record): string => 'Chi tiết export #' . $record->getKey())
                    ->modalWidth(Width::SixExtraLarge)
                    ->slideOver()
                    ->modalContent(fn (ExportModel $record) => view('filament.import-export-history.export-detail', [
                        'record' => $record->loadMissing('user'),
                        'fileAvailable' => static::hasDownloadableFile($record),
                        'failedJobs' => static::getRelatedFailedJobs($record),
                    ])),
                Action::make('downloadXlsx')
                    ->label('Tải Excel')
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('success')
                    ->visible(fn (ExportModel $record): bool => static::hasDownloadableFile($record))
                    ->url(fn (ExportModel $record): string => static::downloadUrl($record), shouldOpenInNewTab: true),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tổng quan export')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('file_name')->label('File export')->placeholder('Chưa tạo tên file'),
                        TextEntry::make('exporter')->label('Loại dữ liệu')->formatStateUsing(fn (?string $state): string => static::formatExporter($state)),
                        TextEntry::make('created_at')->label('Bắt đầu')->dateTime('d/m/Y H:i'),
                        TextEntry::make('completed_at')->label('Hoàn tất')->dateTime('d/m/Y H:i')->placeholder('Chưa hoàn tất'),
                        TextEntry::make('processed_rows')->label('Đã xử lý')->numeric(),
                        TextEntry::make('successful_rows')->label('Thành công')->numeric(),
                        TextEntry::make('total_rows')->label('Tổng dòng')->numeric(),
                        TextEntry::make('failed_rows')->label('Dòng lỗi')->state(fn (ExportModel $record): int => $record->getFailedRowsCount()),
                        TextEntry::make('file_disk')->label('Disk lưu file'),
                        TextEntry::make('user.email')->label('Người export')->placeholder('-'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExportHistories::route('/'),
            'view' => ViewExportHistory::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function formatExporter(?string $exporter): string
    {
        return match ($exporter) {
            \App\Filament\Exports\ProductExporter::class => 'Sản phẩm',
            \App\Filament\Exports\PostExporter::class => 'Bài viết',
            default => class_basename((string) $exporter),
        };
    }

    public static function statusLabel(ExportModel $record): string
    {
        if (blank($record->completed_at)) {
            return 'Đang xử lý';
        }

        if (! static::hasDownloadableFile($record)) {
            return 'Hoàn tất nhưng thiếu file';
        }

        return $record->getFailedRowsCount() > 0 ? 'Hoàn tất có lỗi' : 'Hoàn tất';
    }

    public static function statusColor(ExportModel $record): string
    {
        if (blank($record->completed_at)) {
            return 'warning';
        }

        if (! static::hasDownloadableFile($record)) {
            return 'danger';
        }

        return $record->getFailedRowsCount() > 0 ? 'danger' : 'success';
    }

    public static function hasDownloadableFile(ExportModel $record): bool
    {
        if (blank($record->file_name) || blank($record->file_disk)) {
            return false;
        }

        $directory = $record->getFileDirectory();
        $fileName = "{$record->file_name}.xlsx";
        $disk = $record->getFileDisk();

        return $disk->exists($directory . DIRECTORY_SEPARATOR . $fileName)
            || $disk->exists($directory . DIRECTORY_SEPARATOR . 'headers.csv');
    }

    public static function downloadUrl(ExportModel $record): string
    {
        return URL::signedRoute('filament.exports.download', [
            'authGuard' => Filament::getAuthGuard(),
            'export' => $record,
            'format' => ExportFormat::Xlsx,
        ], absolute: false);
    }

    public static function getRelatedFailedJobs(ExportModel $record): \Illuminate\Support\Collection
    {
        $tag = 'export' . $record->getKey();

        return DB::table('failed_jobs')
            ->where('payload', 'like', "%{$tag}%")
            ->orWhere('exception', 'like', "%{$tag}%")
            ->latest('id')
            ->limit(5)
            ->get();
    }
}
