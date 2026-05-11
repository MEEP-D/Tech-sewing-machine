<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_pages');
        });

        static::deleted(function () {
            Cache::forget('site_pages');
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
}
