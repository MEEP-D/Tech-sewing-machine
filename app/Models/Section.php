<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Cache;

class Section extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('home_page_sections');
        });

        static::deleted(function () {
            Cache::forget('home_page_sections');
        });
    }

    protected $fillable = [
        'key', 'title', 'subtitle', 'content', 'image',
        'type', 'is_active', 'sort_order',
        'container_class', 'bg_color', 'text_color',
        'spacing_top', 'spacing_bottom', 'style_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'style_config' => 'array',
    ];

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }
}
