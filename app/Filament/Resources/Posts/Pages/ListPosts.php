<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Actions\ImportSpreadsheetAction;
use App\Filament\Exports\PostExporter;
use App\Filament\Imports\PostImporter;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Support\VietnameseAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            VietnameseAction::create(CreateAction::make(), 'bài viết'),
            ImportSpreadsheetAction::make()
                ->label('Nhập bài viết')
                ->importer(PostImporter::class),
            ExportAction::make()
                ->label('Xuất bài viết')
                ->modalHeading('Chọn trường xuất bài viết')
                ->modalSubmitActionLabel('Xuất Excel')
                ->exporter(PostExporter::class)
                ->formats([ExportFormat::Xlsx])
                ->columnMappingColumns(4)
                ->fileName(fn (): string => 'bai-viet-' . now()->format('Ymd-His')),
        ];
    }
}
