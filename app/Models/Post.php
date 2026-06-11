<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use App\Models\Concerns\RendersRichContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, ResolvesMediaUrl, RendersRichContent, SoftDeletes;

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

    protected static function booted(): void
    {
        static::saving(function (Post $post): void {
            if ($post->status === 'published' && blank($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    // ─── Relationships ────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function newsletterCampaign(): HasOne
    {
        return $this->hasOne(NewsletterCampaign::class);
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

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->thumbnail);
    }

    public function getEmailThumbnailUrlAttribute(): ?string
    {
        return $this->toPublicAbsoluteUrl($this->thumbnail_url);
    }

    public function getRenderedContentAttribute(): string
    {
        return $this->renderRichContent($this->content);
    }

    private function toPublicAbsoluteUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $url = trim($url);
        if ($url === '') {
            return null;
        }

        $publicBase = rtrim((string) (env('APP_PUBLIC_URL') ?: config('app.url')), '/');
        if ($publicBase === '') {
            return $url;
        }

        $baseParts = parse_url($publicBase);
        if ($baseParts === false || empty($baseParts['scheme']) || empty($baseParts['host'])) {
            return $url;
        }

        if (str_starts_with($url, '//')) {
            return $baseParts['scheme'] . ':' . $url;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            $urlParts = parse_url($url);
            if ($urlParts !== false && ! empty($urlParts['host']) && in_array($urlParts['host'], ['127.0.0.1', 'localhost'], true)) {
                return $publicBase . '/' . ltrim((string) ($urlParts['path'] ?? ''), '/');
            }

            return $url;
        }

        return $publicBase . '/' . ltrim($url, '/');
    }
}
