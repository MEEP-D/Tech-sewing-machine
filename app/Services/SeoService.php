<?php

namespace App\Services;

use App\Models\Setting;

class SeoService
{
    protected array $defaults = [
        'meta_title' => 'Tech Sewing Machine - Thiết Bị May Mặc Công Nghiệp',
        'meta_description' => 'Chuyên cung cấp máy may lập trình, máy vắt sổ, máy một kim và các thiết bị may mặc công nghệ mới. Tin tức hội chợ, hội thảo ngành may mặc.',
        'og_image' => '/images/og-default.svg',
    ];

    public function forModel($model): array
    {
        $seo = $model->seoMeta ?? null;
        $name = $model->name ?? $model->title ?? '';
        $url = url($model->url ?? request()->path());

        return [
            'meta_title' => $seo?->meta_title ?: ($name . ' | Tech Sewing Machine'),
            'meta_description' => $seo?->meta_description ?: ($model->short_description ?? $model->excerpt ?? $this->defaults['meta_description']),
            'og_title' => $seo?->og_title ?: $name,
            'og_description' => $seo?->og_description ?: ($model->short_description ?? $model->excerpt ?? ''),
            'og_image' => $seo?->og_image ?: ($model->thumbnail ?? $this->defaultOgImageUrl()),
            'canonical_url' => $seo?->canonical_url ?: $url,
            'robots' => $seo?->robots ?? 'index, follow',
            'focus_keyword' => $seo?->focus_keyword ?? '',
            'schema_markup' => $seo?->schema_markup ?? [],
            'no_index' => $seo?->no_index ?? false,
            'no_follow' => $seo?->no_follow ?? false,
        ];
    }

    public function productSchema($product): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags((string) $product->short_description),
            'sku' => $product->sku,
            'brand' => ['@type' => 'Brand', 'name' => $product->brand ?? 'Tech Sewing Machine'],
            'image' => $product->thumbnail ? asset($product->thumbnail) : null,
            'url' => $product->url,
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'VND',
                'price' => preg_replace('/[^0-9]/', '', (string) $product->price) ?: '0',
                'availability' => 'https://schema.org/InStock',
                'seller' => ['@type' => 'Organization', 'name' => 'Tech Sewing Machine'],
            ],
        ];
    }

    public function articleSchema($post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $post->title,
            'description' => strip_tags((string) $post->excerpt),
            'image' => $post->thumbnail_url,
            'datePublished' => optional($post->published_at)->toIso8601String(),
            'dateModified' => optional($post->updated_at)->toIso8601String(),
            'author' => ['@type' => 'Person', 'name' => $post->author?->name ?? 'Admin'],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Tech Sewing Machine',
                'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
            ],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $post->url],
        ];
    }

    public function breadcrumbSchema(array $items): array
    {
        $listItems = collect($items)->values()->map(fn ($item, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'item' => $item['url'],
        ])->all();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }

    public function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Tech Sewing Machine',
            'url' => config('app.url'),
            'logo' => asset('images/logo.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'availableLanguage' => 'Vietnamese',
            ],
            'sameAs' => [],
        ];
    }

    public function defaults(string $title = '', string $description = ''): array
    {
        return [
            'meta_title' => ($title ?: 'Tech Sewing Machine') . ' | Thiết Bị May Mặc Công Nghiệp',
            'meta_description' => $description ?: $this->defaults['meta_description'],
            'og_title' => $title ?: 'Tech Sewing Machine',
            'og_description' => $description ?: $this->defaults['meta_description'],
            'og_image' => $this->defaultOgImageUrl(),
            'canonical_url' => url()->current(),
            'robots' => 'index, follow',
            'schema_markup' => [],
        ];
    }

    private function defaultOgImageUrl(): string
    {
        $configuredOgImage = Setting::getValue('seo_default_og_image');

        if (is_string($configuredOgImage) && filled($configuredOgImage)) {
            return asset('storage/' . ltrim($configuredOgImage, '/'));
        }

        return asset($this->defaults['og_image']);
    }
}
