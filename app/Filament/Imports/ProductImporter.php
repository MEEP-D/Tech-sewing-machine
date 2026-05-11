<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;

class ProductImporter extends Importer
{
    /**
     * Chỉ định nghĩa các cột do ADMIN quản lý.
     * Loại trừ:
     *   - view_count : user interaction — tự tăng khi user xem trang sản phẩm
     *   - sort_order : nên set thủ công trong admin để không xáo trộn thứ tự hiện có
     */
    protected static ?string $model = Product::class;

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
            ImportColumn::make('name')
                ->label('Tên sản phẩm')
                ->rules(['required', 'max:255']),
            ImportColumn::make('slug')
                ->label('Slug')
                ->rules(['required', 'max:255']),
            ImportColumn::make('sku')
                ->label('SKU')
                ->rules(['max:255'])
                ->ignoreBlankState(),
            ImportColumn::make('short_description')
                ->label('Mô tả ngắn')
                ->ignoreBlankState(),
            ImportColumn::make('description')
                ->label('Mô tả chi tiết')
                ->ignoreBlankState(),
            ImportColumn::make('price')
                ->label('Giá')
                ->ignoreBlankState(),
            ImportColumn::make('brand')
                ->label('Thương hiệu')
                ->ignoreBlankState(),
            ImportColumn::make('origin')
                ->label('Xuất xứ')
                ->ignoreBlankState(),
            ImportColumn::make('thumbnail')
                ->label('Ảnh đại diện')
                ->ignoreBlankState(),
            ImportColumn::make('category')
                ->label('Danh mục')
                ->relationship(resolveUsing: 'name')
                ->ignoreBlankState(),
            ImportColumn::make('status')
                ->label('Trạng thái')
                ->rules(['in:draft,published,archived'])
                ->ignoreBlankState(),
            ImportColumn::make('is_featured')
                ->label('Nổi bật')
                ->boolean()
                ->ignoreBlankState(),
            ImportColumn::make('is_new')
                ->label('Mới')
                ->boolean()
                ->ignoreBlankState(),
            ImportColumn::make('sort_order')
                ->label('Thứ tự sắp xếp')
                ->integer()
                ->ignoreBlankState(),
        ];
    }

    public function resolveRecord(): Product
    {
        $query = Product::query()->withTrashed();

        if (filled($this->data['id'] ?? null)) {
            $record = (clone $query)->whereKey($this->data['id'])->first();

            if ($record) {
                return $record;
            }
        }

        if (filled($this->data['slug'] ?? null)) {
            $record = (clone $query)->where('slug', $this->data['slug'])->first();

            if ($record) {
                return $record;
            }
        }

        if (filled($this->data['sku'] ?? null)) {
            $record = (clone $query)->where('sku', $this->data['sku'])->first();

            if ($record) {
                return $record;
            }
        }

        return new Product();
    }

    protected function beforeValidate(): void
    {
        if ($this->isDeleteAction()) {
            if (! $this->record?->exists) {
                throw ValidationException::withMessages([
                    'import_action' => 'Không tìm thấy sản phẩm để xóa (cần id hoặc slug hoặc sku hợp lệ).',
                ]);
            }

            return;
        }

        if (blank($this->data['name'] ?? null) || blank($this->data['slug'] ?? null)) {
            throw ValidationException::withMessages([
                'name' => 'Tên sản phẩm là bắt buộc cho hành động upsert.',
                'slug' => 'Slug là bắt buộc cho hành động upsert.',
            ]);
        }

        $recordKey = $this->record?->getKey();

        $slugExists = Product::query()
            ->withTrashed()
            ->where('slug', $this->data['slug'])
            ->when(filled($recordKey), fn (Builder $query) => $query->whereKeyNot($recordKey))
            ->exists();

        if ($slugExists) {
            throw ValidationException::withMessages([
                'slug' => 'Slug đã tồn tại ở sản phẩm khác.',
            ]);
        }

        if (filled($this->data['sku'] ?? null)) {
            $skuExists = Product::query()
                ->withTrashed()
                ->where('sku', $this->data['sku'])
                ->when(filled($recordKey), fn (Builder $query) => $query->whereKeyNot($recordKey))
                ->exists();

            if ($skuExists) {
                throw ValidationException::withMessages([
                    'sku' => 'SKU đã tồn tại ở sản phẩm khác.',
                ]);
            }
        }
    }

    protected function beforeSave(): void
    {
        if ($this->isDeleteAction()) {
            return;
        }

        if ($this->record?->trashed()) {
            $this->record->restore();
        }
    }

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
        return 'Import sản phẩm';
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import sản phẩm hoàn tất: ' . Number::format($import->successful_rows) . ' dòng thành công.';

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
