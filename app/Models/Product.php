<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'sku', 'short_description', 'description',
        'price', 'brand', 'origin', 'specifications', 'thumbnail',
        'gallery', 'category_id', 'status', 'is_featured', 'is_new',
        'sort_order', 'view_count',
    ];

    protected $casts = [
        'specifications' => 'array',
        'gallery'        => 'array',
        'is_featured'    => 'boolean',
        'is_new'         => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
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

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
