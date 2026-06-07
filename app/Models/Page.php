<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Page extends Model
{
    use HasFactory, ResolvesMediaUrl, SoftDeletes;

    private const PAGE_CACHE_KEYS = [
        'site_pages',
        'site_pages_v2',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            foreach (self::PAGE_CACHE_KEYS as $cacheKey) {
                Cache::forget($cacheKey);
            }
        });

        static::deleted(function () {
            foreach (self::PAGE_CACHE_KEYS as $cacheKey) {
                Cache::forget($cacheKey);
            }
        });
    }

    protected $fillable = ['title', 'slug', 'content', 'excerpt', 'image', 'is_active', 'layout', 'layout_mode', 'container_class', 'bg_color', 'text_color', 'spacing_top', 'spacing_bottom', 'cache_enabled', 'cache_ttl', 'style_config'];

    protected $casts = [
        'is_active' => 'boolean',
        'cache_enabled' => 'boolean',
        'cache_ttl' => 'integer',
        'style_config' => 'array',
    ];

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    // Builder nodes removed; keep page as traditional content model.

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->image);
    }
}
