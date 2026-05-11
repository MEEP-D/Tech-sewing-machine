<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'thumbnail',
        'category_id', 'author_id', 'status', 'type',
        'published_at', 'event_date', 'event_location',
        'is_featured', 'view_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured'  => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function getUrlAttribute(): string
    {
        return route('news.show', $this->slug);
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->content));
        return (int) max(1, round($words / 200));
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
