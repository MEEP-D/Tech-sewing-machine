<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
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
}
