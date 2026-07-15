<?php

namespace App\Filament\Imports;

use App\Models\Post;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;

class PostImporter extends Importer
{
    protected static ?string $model = Post::class;

    /**
     * Chỉ định nghĩa các cột do ADMIN quản lý.
     * Loại trừ: view_count (user interaction - tự tăng khi user xem trang)
     */
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')
                ->label('ID')
                ->integer()
                ->ignoreBlankState(),

            ImportColumn::make('import_action')
                ->label('Hành động import (upsert|delete)')
                ->rules(['in:upsert,delete'])
                ->castStateUsing(fn ($state) => filled($state) ? strtolower(trim((string) $state)) : 'upsert'),

            ImportColumn::make('title')
                ->label('Tiêu đề')
                ->rules(['required', 'max:255']),

            ImportColumn::make('slug')
                ->label('Slug')
                ->rules(['required', 'max:255']),

            ImportColumn::make('excerpt')
                ->label('Mô tả ngắn')
                ->ignoreBlankState(),

            ImportColumn::make('content')
                ->label('Nội dung (HTML)')
                ->ignoreBlankState(),

            ImportColumn::make('thumbnail')
                ->label('Ảnh đại diện (đường dẫn)')
                ->ignoreBlankState(),

            ImportColumn::make('category')
                ->label('Danh mục (tên chính xác)')
                ->relationship(resolveUsing: 'name')
                ->ignoreBlankState(),

            ImportColumn::make('status')
                ->label('Trạng thái (draft|published|archived)')
                ->rules(['in:draft,published,archived'])
                ->ignoreBlankState(),

            ImportColumn::make('type')
                ->label('Loại bài viết (news|event|fair|seminar)')
                ->rules(['in:news,event,fair,seminar'])
                ->ignoreBlankState(),

            ImportColumn::make('published_at')
                ->label('Ngày xuất bản (Y-m-d H:i:s)')
                ->rules(['nullable', 'date'])
                ->ignoreBlankState(),

            ImportColumn::make('event_date')
                ->label('Ngày sự kiện')
                ->ignoreBlankState(),

            ImportColumn::make('event_location')
                ->label('Địa điểm sự kiện')
                ->ignoreBlankState(),

            ImportColumn::make('is_featured')
                ->label('Nổi bật (1/0 hoặc true/false)')
                ->boolean()
                ->ignoreBlankState(),
        ];
    }

    /**
     * Tìm record theo thứ tự ưu tiên: ID → slug
     * Bao gồm cả bản ghi đã soft-delete để có thể restore.
     */
    public function resolveRecord(): Post
    {
        $query = Post::query()->withTrashed();

        // Ưu tiên 1: tìm theo ID
        if (filled($this->data['id'] ?? null)) {
            $record = (clone $query)->whereKey($this->data['id'])->first();
            if ($record) {
                return $record;
            }
        }

        // Ưu tiên 2: tìm theo slug
        if (filled($this->data['slug'] ?? null)) {
            $record = (clone $query)->where('slug', $this->data['slug'])->first();
            if ($record) {
                return $record;
            }
        }

        return new Post();
    }

    /**
     * Validate trước khi xử lý — kiểm tra slug unique và các điều kiện bắt buộc.
     */
    protected function beforeValidate(): void
    {
        // Nếu là delete action: chỉ cần record tồn tại
        if ($this->isDeleteAction()) {
            if (! $this->record?->exists) {
                throw ValidationException::withMessages([
                    'import_action' => 'Không tìm thấy bài viết để xóa (cần id hoặc slug hợp lệ).',
                ]);
            }

            return;
        }

        // Upsert: bắt buộc title và slug
        if (blank($this->data['title'] ?? null) || blank($this->data['slug'] ?? null)) {
            throw ValidationException::withMessages([
                'title' => 'Tiêu đề là bắt buộc cho hành động upsert.',
                'slug'  => 'Slug là bắt buộc cho hành động upsert.',
            ]);
        }

        // Kiểm tra slug không trùng với bài viết KHÁC
        $recordKey = $this->record?->getKey();

        $slugExists = Post::query()
            ->withTrashed()
            ->where('slug', $this->data['slug'])
            ->when(filled($recordKey), fn (Builder $query) => $query->whereKeyNot($recordKey))
            ->exists();

        if ($slugExists) {
            throw ValidationException::withMessages([
                'slug' => 'Slug "' . $this->data['slug'] . '" đã tồn tại ở bài viết khác.',
            ]);
        }
    }

    /**
     * Trước khi save: restore bài viết đã bị xóa mềm nếu upsert.
     */
    protected function beforeSave(): void
    {
        if ($this->isDeleteAction()) {
            return;
        }

        if ($this->record?->trashed()) {
            $this->record->restore();
        }
    }

    /**
     * Override save để xử lý delete action riêng.
     */
    public function saveRecord(): void
    {
        if ($this->isDeleteAction()) {
            if ($this->record?->exists && ! $this->record->trashed()) {
                $this->record->delete();
            }

            return;
        }

        parent::saveRecord();
    }

    public function getJobQueue(): ?string
    {
        return 'imports';
    }

    public function getJobBatchName(): ?string
    {
        return 'Nhập bài viết';
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Nhập bài viết hoàn tất: ' . Number::format($import->successful_rows) . ' dòng thành công.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' dòng lỗi.';
        }

        return $body;
    }

    protected function isDeleteAction(): bool
    {
        return ($this->data['import_action'] ?? 'upsert') === 'delete';
    }
}
