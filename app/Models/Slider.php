<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use ResolvesMediaUrl;

    protected $fillable = [
        'image',
        'title',
        'subtitle',
        'link',
        'is_active',
        'show_overlay',
        'show_title',
        'show_subtitle',
        'show_button',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_overlay' => 'boolean',
        'show_title' => 'boolean',
        'show_subtitle' => 'boolean',
        'show_button' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        $path = $this->image;

        if (! $path) {
            return null;
        }

        return $this->resolveMediaUrl($path);
    }
}
