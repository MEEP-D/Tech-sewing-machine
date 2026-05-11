<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeoMeta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seoable_id', 'seoable_type',
        'meta_title', 'meta_description',
        'og_title', 'og_description', 'og_image',
        'canonical_url', 'focus_keyword',
        'schema_markup', 'no_index', 'no_follow',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'no_index'      => 'boolean',
        'no_follow'     => 'boolean',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get effective robots tag value.
     */
    public function getRobotsAttribute(): string
    {
        $parts = [];
        $parts[] = $this->no_index  ? 'noindex'  : 'index';
        $parts[] = $this->no_follow ? 'nofollow' : 'follow';
        return implode(', ', $parts);
    }
}
