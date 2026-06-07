<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, ResolvesMediaUrl, SoftDeletes;

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('front_menu_categories_v1');
            Cache::forget('product_filter_data_v1');
        });

        static::deleted(function () {
            Cache::forget('front_menu_categories_v1');
            Cache::forget('product_filter_data_v1');
        });

        static::restored(function () {
            Cache::forget('front_menu_categories_v1');
            Cache::forget('product_filter_data_v1');
        });
    }

    protected $fillable = [
        'name', 'slug', 'description', 'type',
        'parent_id', 'image', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function getUrlAttribute(): string
    {
        return $this->type === 'news'
            ? route('news.category', $this->slug)
            : route('products.category', $this->slug);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->image);
    }
}
