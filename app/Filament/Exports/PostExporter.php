<?php

namespace App\Filament\Exports;

use App\Models\Post;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PostExporter extends Exporter
{
    protected static ?string $model = Post::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('title')->label('Tiêu đề'),
            ExportColumn::make('slug')->label('Slug'),
            ExportColumn::make('category.name')->label('Danh mục'),
            ExportColumn::make('author.name')->label('Tác giả'),
            ExportColumn::make('author.email')->label('Email tác giả'),
            ExportColumn::make('status')->label('Trạng thái'),
            ExportColumn::make('type')->label('Loại'),
            ExportColumn::make('excerpt')->label('Mô tả tóm tắt'),
            ExportColumn::make('content')
                ->label('Nội dung chi tiết')
                ->formatStateUsing(fn (?string $state): string => strip_tags((string) $state)),
            ExportColumn::make('thumbnail')->label('Ảnh bìa'),
            ExportColumn::make('published_at')->label('Ngày xuất bản'),
            ExportColumn::make('event_date')->label('Ngày diễn ra'),
            ExportColumn::make('event_location')->label('Địa điểm'),
            ExportColumn::make('is_featured')->label('Nổi bật'),
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
        return 'Xuất bài viết';
    }

    public function getFormats(): array
    {
        return [ExportFormat::Xlsx];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Xuất bài viết hoàn tất: ' . Number::format($export->successful_rows) . ' dòng.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' dòng lỗi.';
        }

        return $body;
    }
}
