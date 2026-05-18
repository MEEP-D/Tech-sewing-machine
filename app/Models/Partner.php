<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use ResolvesMediaUrl;

    protected $fillable = [
        'name',
        'logo',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        $path = $this->logo;

        if (!$path) {
            return null;
        }

        return $this->resolveMediaUrl($path);
    }
}
