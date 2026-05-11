<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_menus_v2');
        });

        static::deleted(function () {
            Cache::forget('site_menus_v2');
        });
    }

    protected $fillable = [
        'location', 'label', 'url', 'route_name', 'target',
        'parent_id', 'sort_order', 'is_active',
        'icon', 'css_class', 'meta_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta_config' => 'array',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }
}
