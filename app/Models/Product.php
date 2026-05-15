<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'code', 'sku', 'short_description', 'long_description', 'description',
        'price', 'brand', 'origin', 'specifications', 'image', 'thumbnail',
        'gallery', 'video_id', 'category_id', 'status', 'is_featured', 'is_new', 'is_hot',
        'is_exclusive', 'sort_order', 'view_count', 'support_prompt',
        'cta_primary_label', 'cta_primary_url', 'cta_secondary_label', 'cta_secondary_url',
        'overview_heading', 'overview_content', 'seo_heading', 'seo_content',
    ];

    protected $casts = [
        'specifications' => 'array',
        'gallery'        => 'array',
        'is_featured'    => 'boolean',
        'is_new'         => 'boolean',
        'is_hot'         => 'boolean',
        'is_exclusive'   => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (Product $product): void {
            if (! $product->is_exclusive) {
                return;
            }

            static::query()
                ->whereKeyNot($product->getKey())
                ->where('is_exclusive', true)
                ->update(['is_exclusive' => false]);
        });
    }

    // ─── Relationships ────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort_order');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function getUrlAttribute(): string
    {
        return route('products.show', $this->slug);
    }

    public function getDisplayImageAttribute(): ?string
    {
        return $this->image ?: $this->thumbnail;
    }

    public function getDisplayImageUrlAttribute(): ?string
    {
        $path = $this->display_image;

        if (!$path) {
            return null;
        }

        $path = str_replace('\\', '/', trim((string) $path));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'public/')) {
            return asset('storage/' . ltrim(substr($path, 7), '/'));
        }

        if (
            str_starts_with($path, 'assets/')
            || str_starts_with($path, 'images/')
            || str_starts_with($path, 'upload/')
        ) {
            return asset($path);
        }

        if (str_starts_with($path, '/')) {
            return asset(ltrim($path, '/'));
        }

        return Storage::disk('public')->url($path);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
