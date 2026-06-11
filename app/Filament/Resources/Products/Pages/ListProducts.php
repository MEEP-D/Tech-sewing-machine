<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Actions\ImportSpreadsheetAction;
use App\Filament\Exports\ProductExporter;
use App\Filament\Imports\Jobs\ProductImportCsv;
use App\Filament\Imports\ProductImporter;
use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportSpreadsheetAction::make()
                ->label('Import sản phẩm')
                ->importer(ProductImporter::class)
                ->job(ProductImportCsv::class)
                ->chunkSize(100000),
            ExportAction::make()
                ->label('Export sản phẩm')
                ->modalHeading('Chọn trường xuất sản phẩm')
                ->modalSubmitActionLabel('Xuất Excel')
                ->exporter(ProductExporter::class)
                ->formats([ExportFormat::Xlsx])
                ->columnMappingColumns(4)
                ->fileName(fn (): string => 'san-pham-' . now()->format('Ymd-His')),
        ];
    }
}
