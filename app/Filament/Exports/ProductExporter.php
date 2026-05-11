<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('name')->label('Tên sản phẩm'),
            ExportColumn::make('slug')->label('Slug'),
            ExportColumn::make('sku')->label('SKU'),
            ExportColumn::make('category.name')->label('Danh mục'),
            ExportColumn::make('price')->label('Giá'),
            ExportColumn::make('brand')->label('Thương hiệu'),
            ExportColumn::make('origin')->label('Xuất xứ'),
            ExportColumn::make('short_description')->label('Mô tả ngắn'),
            ExportColumn::make('description')
                ->label('Mô tả chi tiết')
                ->formatStateUsing(fn (?string $state): string => strip_tags((string) $state)),
            ExportColumn::make('specifications')
                ->label('Thông số kỹ thuật')
                ->state(fn (Product $record): string => json_encode($record->specifications ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)),
            ExportColumn::make('thumbnail')->label('Ảnh đại diện'),
            ExportColumn::make('gallery')
                ->label('Bộ sưu tập ảnh')
                ->state(fn (Product $record): string => implode(', ', $record->gallery ?? [])),
            ExportColumn::make('status')->label('Trạng thái'),
            ExportColumn::make('is_featured')->label('Nổi bật'),
            ExportColumn::make('is_new')->label('Mới'),
            ExportColumn::make('sort_order')->label('Thứ tự'),
            ExportColumn::make('view_count')->label('Lượt xem'),
            ExportColumn::make('created_at')->label('Ngày tạo'),
            ExportColumn::make('updated_at')->label('Ngày cập nhật'),
        ];
    }

    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public function getJobBatchName(): ?string
    {
        return 'Export sản phẩm';
    }

    public function getFormats(): array
    {
        return [ExportFormat::Xlsx];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export sản phẩm hoàn tất: ' . Number::format($export->successful_rows) . ' dòng.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' dòng lỗi.';
        }

        return $body;
    }
}
