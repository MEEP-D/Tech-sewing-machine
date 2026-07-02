<?php

namespace App\Filament\Imports;

use App\Models\Category;
use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')
                ->label('ID')
                ->integer()
                ->ignoreBlankState(),
            ImportColumn::make('product_id')
                ->label('ID sản phẩm nguồn')
                ->integer()
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('import_action')
                ->label('Hành động import (upsert|delete)')
                ->rules(['in:upsert,delete'])
                ->fillRecordUsing(fn () => null)
                ->castStateUsing(fn ($state) => filled($state) ? strtolower(trim((string) $state)) : 'upsert'),

            ImportColumn::make('product_code')
                ->label('Mã sản phẩm')
                ->rules(['max:255'])
                ->ignoreBlankState()
                ->fillRecordUsing(function (Product $record, $state): void {
                    $record->code = $state;
                }),
            ImportColumn::make('product_name')
                ->label('Tên sản phẩm')
                ->rules(['required', 'max:255'])
                ->fillRecordUsing(fn (Product $record, $state) => $record->name = $state),
            ImportColumn::make('name')
                ->label('Tên')
                ->rules(['max:255'])
                ->ignoreBlankState(),
            ImportColumn::make('slug')
                ->label('Slug')
                ->rules(['max:255'])
                ->ignoreBlankState(),
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
            ImportColumn::make('unit')
                ->label('Đơn vị')
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('brand')
                ->label('Thương hiệu')
                ->ignoreBlankState(),
            ImportColumn::make('origin')
                ->label('Xuất xứ')
                ->ignoreBlankState(),
            ImportColumn::make('thumbnail')
                ->label('Ảnh đại diện')
                ->ignoreBlankState(),
            ImportColumn::make('image_url')
                ->label('URL ảnh')
                ->ignoreBlankState()
                ->fillRecordUsing(function (Product $record, $state): void {
                    $record->image = $state;
                    $record->thumbnail = $state;
                }),
            ImportColumn::make('video_url')
                ->label('URL video')
                ->ignoreBlankState()
                ->fillRecordUsing(fn (Product $record, $state) => $record->video_id = static::extractYoutubeId($state)),

            ImportColumn::make('category')
                ->label('Danh mục')
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('category_id')
                ->label('ID danh mục')
                ->integer()
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('category_name')
                ->label('Tên danh mục')
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('category_slug')
                ->label('Slug danh mục')
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),

            ImportColumn::make('status')
                ->label('Trạng thái')
                ->rules(['in:draft,published,archived'])
                ->ignoreBlankState(),
            ImportColumn::make('product_url')
                ->label('URL sản phẩm')
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('source_category_url')
                ->label('URL danh mục nguồn')
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('detail_status')
                ->label('Trạng thái chi tiết')
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('debug')
                ->label('Debug')
                ->ignoreBlankState()
                ->fillRecordUsing(fn () => null),

            ImportColumn::make('is_featured')
                ->label('Nổi bật')
                ->boolean()
                ->ignoreBlankState(),
            ImportColumn::make('is_new')
                ->label('Mới')
                ->boolean()
                ->ignoreBlankState(),
            ImportColumn::make('sort_order')
                ->label('Thứ tự')
                ->integer()
                ->ignoreBlankState(),
        ];
    }

    public function resolveRecord(): Product
    {
        $this->normalizeSourceData();

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

        foreach (['sku', 'product_code'] as $key) {
            if (filled($this->data[$key] ?? null)) {
                $record = (clone $query)
                    ->where('sku', $this->data[$key])
                    ->orWhere('code', $this->data[$key])
                    ->first();

                if ($record) {
                    return $record;
                }
            }
        }

        return new Product();
    }

    protected function beforeValidate(): void
    {
        if ($this->isDeleteAction()) {
            if (! $this->record?->exists) {
                throw ValidationException::withMessages([
                    'import_action' => 'Không tìm thấy sản phẩm để xóa. Cần id, product_id, slug, sku hoặc product_code hợp lệ.',
                ]);
            }

            return;
        }

        if (blank($this->data['name'] ?? null) || blank($this->data['slug'] ?? null)) {
            throw ValidationException::withMessages([
                'name' => 'Tên sản phẩm là bắt buộc cho upsert.',
                'slug' => 'Không thể tạo slug sản phẩm từ slug, product_url hoặc product_name.',
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

    protected function beforeFill(): void
    {
        if ($this->isDeleteAction()) {
            return;
        }

        $this->record->forceFill([
            'name' => $this->data['name'],
            'slug' => $this->data['slug'],
            'code' => $this->data['code'] ?? $this->record->code,
            'sku' => $this->data['sku'] ?? $this->record->sku,
            'image' => $this->data['image'] ?? $this->record->image,
            'thumbnail' => $this->data['thumbnail'] ?? $this->record->thumbnail,
            'video_id' => $this->data['video_id'] ?? $this->record->video_id,
            'status' => $this->data['status'] ?? $this->record->status ?? 'published',
            'category_id' => $this->data['category_id'] ?? $this->record->category_id,
        ]);
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
        return null;
    }

    public function getJobConnection(): ?string
    {
        return 'sync';
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

    protected function normalizeSourceData(): void
    {
        $this->data['name'] = $this->data['name'] ?? $this->data['product_name'] ?? null;

        $externalProductId = $this->normalizeExternalId($this->data['product_id'] ?? null)
            ?? $this->extractQueryId($this->data['product_url'] ?? null, 'idproduct');

        $baseSlug = $this->data['slug']
            ?? (filled($this->data['name'] ?? null) ? Str::slug($this->data['name']) : null)
            ?? $this->slugFromUrl($this->data['product_url'] ?? null)
            ?? (filled($externalProductId) ? 'product' : null);

        if (filled($externalProductId) && filled($baseSlug)) {
            $idSuffix = '-p' . $externalProductId;
            $this->data['slug'] = Str::endsWith($baseSlug, $idSuffix) ? $baseSlug : ($baseSlug . $idSuffix);
        } else {
            $this->data['slug'] = $baseSlug;
        }

        if (filled($this->data['product_code'] ?? null)) {
            $this->data['code'] = $this->data['code'] ?? $this->data['product_code'];
        }

        if (filled($this->data['sku'] ?? null)) {
            $this->data['sku'] = trim((string) $this->data['sku']);
        } else {
            $this->data['sku'] = null;
        }

        if (filled($this->data['image_url'] ?? null)) {
            $this->data['image'] = $this->data['image'] ?? $this->data['image_url'];
            $this->data['thumbnail'] = $this->data['thumbnail'] ?? $this->data['image_url'];
        }

        if (filled($this->data['video_url'] ?? null)) {
            $this->data['video_id'] = $this->data['video_id'] ?? static::extractYoutubeId($this->data['video_url']);
        }

        if (blank($this->data['status'] ?? null)) {
            $this->data['status'] = 'published';
        }

        if ($this->isDeleteAction()) {
            return;
        }

        $category = $this->resolveOrCreateCategory();

        if ($category) {
            $this->data['category_id'] = $category->getKey();
        }
    }

    protected function resolveOrCreateCategory(): ?Category
    {
        if (filled($this->data['category_id'] ?? null)) {
            $category = Category::query()->withTrashed()->find($this->data['category_id']);

            if ($category) {
                if ($category->trashed()) {
                    $category->restore();
                }

                return $category;
            }
        }

        $categoryName = $this->data['category_name'] ?? $this->data['category'] ?? null;
        $categorySlug = $this->data['category_slug'] ?? null;

        if (blank($categorySlug) && filled($this->data['source_category_url'] ?? null)) {
            $categorySlug = $this->slugFromUrl($this->data['source_category_url']);
        }

        if (blank($categorySlug) && filled($categoryName)) {
            $categorySlug = Str::slug($categoryName);
        }

        if (blank($categorySlug) && blank($categoryName)) {
            return null;
        }

        $categorySlug = $categorySlug ?: Str::slug($categoryName);
        $categoryName = $categoryName ?: Str::headline(str_replace('-', ' ', $categorySlug));

        $category = Category::query()
            ->withTrashed()
            ->where(function (Builder $query) use ($categorySlug, $categoryName): void {
                $query->where('slug', $categorySlug)
                    ->orWhere(function (Builder $query) use ($categoryName): void {
                        $query->where('name', $categoryName)->where('type', 'product');
                    });
            })
            ->first();

        if (! $category) {
            return Category::query()->create([
                'name' => $categoryName,
                'slug' => $categorySlug,
                'type' => 'product',
                'is_active' => true,
            ]);
        }

        if ($category->trashed()) {
            $category->restore();
        }

        $category->fill([
            'name' => $category->name ?: $categoryName,
            'slug' => $category->slug ?: $categorySlug,
            'type' => 'product',
            'is_active' => true,
        ])->save();

        return $category;
    }

    protected function slugFromUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $path = parse_url(trim($url), PHP_URL_PATH) ?: trim($url);
        $slug = trim((string) Str::of($path)->before('?')->afterLast('/'), " \t\n\r\0\x0B/");

        return filled($slug) ? Str::slug($slug) : null;
    }

    protected function normalizeExternalId(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $normalized = preg_replace('/[^0-9]/', '', (string) $value);

        return filled($normalized) ? ltrim($normalized, '0') ?: '0' : null;
    }

    protected function extractQueryId(?string $url, string $key): ?string
    {
        if (blank($url)) {
            return null;
        }

        $query = parse_url(trim($url), PHP_URL_QUERY);

        if (! is_string($query) || $query === '') {
            return null;
        }

        parse_str($query, $params);

        return $this->normalizeExternalId($params[$key] ?? null);
    }

    protected static function extractYoutubeId(?string $value): ?string
    {
        return Product::extractYoutubeId($value);
    }
}
