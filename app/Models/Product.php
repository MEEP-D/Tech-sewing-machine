<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use App\Models\Concerns\RendersRichContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory, ResolvesMediaUrl, RendersRichContent, SoftDeletes;

    private const PRODUCT_FILTER_CACHE_KEY = 'product_filter_data_v1';
    public const AVAILABILITY_BADGE_READY = 'giao_ngay';
    public const AVAILABILITY_BADGE_PREORDER = 'dat_truoc';
    public const AVAILABILITY_BADGE_LABELS = [
        self::AVAILABILITY_BADGE_READY => 'Giao ngay',
        self::AVAILABILITY_BADGE_PREORDER => 'Đặt trước',
    ];

    protected $fillable = [
        'name', 'slug', 'code', 'sku', 'short_description', 'long_description', 'description',
        'price', 'brand', 'origin', 'specifications', 'image', 'thumbnail',
        'gallery', 'specification_images', 'video_id', 'category_id', 'status', 'is_featured', 'is_new', 'is_hot',
        'is_exclusive', 'show_in_banner_switcher', 'sort_order', 'view_count', 'discount_percent', 'installment_percent',
        'availability_badge', 'support_prompt',
        'cta_primary_label', 'cta_primary_url', 'cta_secondary_label', 'cta_secondary_url',
        'overview_heading', 'overview_content', 'seo_heading', 'seo_content',
        'usage_guide_content', 'usage_guide_video_id', 'usage_guide_attachment',
    ];

    protected $casts = [
        'specifications' => 'array',
        'gallery'        => 'array',
        'specification_images' => 'array',
        'is_featured'    => 'boolean',
        'is_new'         => 'boolean',
        'is_hot'         => 'boolean',
        'is_exclusive'   => 'boolean',
        'show_in_banner_switcher' => 'boolean',
        'discount_percent' => 'integer',
        'installment_percent' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (Product $product): void {
            Cache::forget(self::PRODUCT_FILTER_CACHE_KEY);
            Cache::forget('front_menu_categories_v1');

            if (! $product->is_exclusive) {
                return;
            }

            static::query()
                ->whereKeyNot($product->getKey())
                ->where('is_exclusive', true)
                ->update(['is_exclusive' => false]);
        });

        static::deleted(function (): bool {
            Cache::forget(self::PRODUCT_FILTER_CACHE_KEY);
            Cache::forget('front_menu_categories_v1');

            return true;
        });

        static::restored(function (): bool {
            Cache::forget(self::PRODUCT_FILTER_CACHE_KEY);
            Cache::forget('front_menu_categories_v1');

            return true;
        });
    }

    // ─── Relationships ────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort_order');
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

    public static function availabilityBadgeOptions(): array
    {
        return self::AVAILABILITY_BADGE_LABELS;
    }

    public static function extractYoutubeId(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = trim((string) $value);

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})~', $value, $matches)) {
            return $matches[1];
        }

        return $value;
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function getUrlAttribute(): string
    {
        return route('products.show', $this->slug);
    }

    public function getDisplayImageAttribute(): ?string
    {
        return $this->image ?: $this->thumbnail;
    }

    public function getDisplayImageUrlAttribute(): ?string
    {
        $path = $this->display_image;

        if (!$path) {
            return null;
        }

        return $this->resolveMediaUrl($path);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function getPriceValueAttribute(): ?int
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $this->price);

        return $digits !== '' ? (int) $digits : null;
    }

    public function getDiscountedPriceValueAttribute(): ?int
    {
        $priceValue = $this->price_value;
        $discountPercent = max(0, (int) $this->discount_percent);

        if ($priceValue === null) {
            return null;
        }

        if ($discountPercent <= 0) {
            return $priceValue;
        }

        return (int) round($priceValue * (100 - min($discountPercent, 100)) / 100);
    }

    public function getFormattedPriceAttribute(): ?string
    {
        return $this->price_value !== null
            ? number_format($this->price_value, 0, ',', '.') . ' đ'
            : null;
    }

    public function getFormattedDiscountedPriceAttribute(): ?string
    {
        return $this->discounted_price_value !== null
            ? number_format($this->discounted_price_value, 0, ',', '.') . ' đ'
            : null;
    }

    public function getAvailabilityBadgeLabelAttribute(): ?string
    {
        return self::AVAILABILITY_BADGE_LABELS[$this->availability_badge] ?? null;
    }

    public function getRenderedDescriptionAttribute(): string
    {
        return $this->renderRichContent($this->description ?: $this->long_description);
    }

    public function getRenderedLongDescriptionAttribute(): string
    {
        return $this->renderRichContent($this->long_description);
    }

    public function getRenderedOverviewContentAttribute(): string
    {
        return $this->renderRichContent($this->overview_content);
    }

    public function getRenderedSeoContentAttribute(): string
    {
        return $this->renderRichContent($this->seo_content);
    }

    public function getRenderedUsageGuideContentAttribute(): string
    {
        return $this->renderRichContent($this->usage_guide_content);
    }

    public function getUsageGuideAttachmentUrlAttribute(): ?string
    {
        if (! $this->usage_guide_attachment) {
            return null;
        }

        return $this->resolveMediaUrl($this->usage_guide_attachment);
    }

    public function getUsageGuideAttachmentExtensionAttribute(): ?string
    {
        if (! $this->usage_guide_attachment) {
            return null;
        }

        $path = parse_url($this->usage_guide_attachment, PHP_URL_PATH) ?: $this->usage_guide_attachment;

        return strtolower((string) pathinfo($path, PATHINFO_EXTENSION)) ?: null;
    }

    public function getUsageGuideAttachmentFilenameAttribute(): ?string
    {
        if (! $this->usage_guide_attachment) {
            return null;
        }

        $path = parse_url($this->usage_guide_attachment, PHP_URL_PATH) ?: $this->usage_guide_attachment;
        $filename = basename((string) $path);

        return $filename !== '' ? urldecode($filename) : null;
    }
}
