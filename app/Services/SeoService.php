<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Str;

class SeoService
{
    protected array $defaults = [
        'meta_title' => 'Tech Sewing Machine | Thiet Bi May Mac Cong Nghiep',
        'meta_description' => 'Chuyen cung cap may may lap trinh, may vat so, may mot kim va cac thiet bi may mac cong nghe moi. Tin tuc hoi cho, hoi thao nganh may mac.',
        'og_image' => '/images/og-default.svg',
    ];

    protected ?array $resolvedDefaults = null;

    public function forModel($model): array
    {
        $seo = $model->seoMeta ?? null;
        $name = trim((string) ($model->name ?? $model->title ?? ''));
        $url = url($model->url ?? request()->path());
        $defaults = $this->seoDefaults();

        return [
            'meta_title' => $seo?->meta_title ?: ($name !== '' ? $name . ' | ' . $defaults['site_title'] : $defaults['meta_title']),
            'meta_description' => $seo?->meta_description ?: ($model->short_description ?? $model->excerpt ?? $defaults['meta_description']),
            'og_title' => $seo?->og_title ?: ($name !== '' ? $name : $defaults['site_title']),
            'og_description' => $seo?->og_description ?: ($model->short_description ?? $model->excerpt ?? $defaults['meta_description']),
            'og_image' => $seo?->og_image ?: ($model->thumbnail ?? $this->defaultOgImageUrl()),
            'canonical_url' => $seo?->canonical_url ?: $url,
            'robots' => $seo?->robots ?? $this->robotsDefault(),
            'focus_keyword' => $seo?->focus_keyword ?? '',
            'schema_markup' => $seo?->schema_markup ?? [],
            'no_index' => $seo?->no_index ?? false,
            'no_follow' => $seo?->no_follow ?? false,
        ];
    }

    public function productSchema($product): array
    {
        $siteTitle = $this->siteTitle();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags((string) $product->short_description),
            'sku' => $product->sku,
            'brand' => ['@type' => 'Brand', 'name' => $product->brand ?? $siteTitle],
            'image' => $product->thumbnail ? asset($product->thumbnail) : null,
            'url' => $product->url,
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'VND',
                'price' => preg_replace('/[^0-9]/', '', (string) $product->price) ?: '0',
                'availability' => 'https://schema.org/InStock',
                'seller' => ['@type' => 'Organization', 'name' => $siteTitle],
            ],
        ];
    }

    public function articleSchema($post): array
    {
        $siteTitle = $this->siteTitle();

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
                'name' => $siteTitle,
                'logo' => ['@type' => 'ImageObject', 'url' => $this->organizationLogoUrl()],
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
        $organizationName = trim((string) Setting::getValue('seo_organization_name', ''));
        $organizationUrl = trim((string) Setting::getValue('seo_organization_url', ''));

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $organizationName !== '' ? $organizationName : $this->siteTitle(),
            'url' => $organizationUrl !== '' ? $organizationUrl : config('app.url'),
            'logo' => $this->organizationLogoUrl(),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'availableLanguage' => 'vi',
            ],
            'sameAs' => [],
        ];
    }

    public function defaults(string $title = '', string $description = ''): array
    {
        $defaults = $this->seoDefaults();
        $siteTitle = $defaults['site_title'];

        $metaTitle = $defaults['meta_title'];
        if ($title !== '') {
            $metaTitle = Str::contains(Str::lower($title), Str::lower($siteTitle))
                ? $title
                : $title . ' | ' . $siteTitle;
        }

        return [
            'meta_title' => $metaTitle,
            'meta_description' => $description ?: $defaults['meta_description'],
            'og_title' => $title !== '' ? $title : $siteTitle,
            'og_description' => $description ?: $defaults['meta_description'],
            'og_image' => $this->defaultOgImageUrl(),
            'canonical_url' => url()->current(),
            'robots' => $this->robotsDefault(),
            'schema_markup' => [],
        ];
    }

    private function seoDefaults(): array
    {
        if (is_array($this->resolvedDefaults)) {
            return $this->resolvedDefaults;
        }

        $siteTitle = trim((string) Setting::getValue('site_title', config('app.name')));
        $defaultTitle = trim((string) Setting::getValue('seo_default_title', ''));
        $defaultDescription = trim((string) Setting::getValue('seo_default_description', ''));
        $siteDescription = trim((string) Setting::getValue('site_description', ''));

        return $this->resolvedDefaults = [
            'site_title' => $siteTitle !== '' ? $siteTitle : config('app.name'),
            'meta_title' => $defaultTitle !== '' ? $defaultTitle : $this->defaults['meta_title'],
            'meta_description' => $defaultDescription !== '' ? $defaultDescription : ($siteDescription !== '' ? $siteDescription : $this->defaults['meta_description']),
        ];
    }

    private function siteTitle(): string
    {
        return $this->seoDefaults()['site_title'];
    }

    private function organizationLogoUrl(): string
    {
        $siteLogo = Setting::getValue('site_logo');

        if (is_string($siteLogo) && filled($siteLogo)) {
            if (str_starts_with($siteLogo, 'http://') || str_starts_with($siteLogo, 'https://')) {
                return $siteLogo;
            }

            if (str_starts_with($siteLogo, 'assets/')) {
                return asset($siteLogo);
            }

            return asset('storage/' . ltrim($siteLogo, '/'));
        }

        return asset('images/logo.png');
    }

    private function robotsDefault(): string
    {
        $robots = trim((string) Setting::getValue('seo_robots_default', 'index,follow'));

        return $robots !== '' ? $robots : 'index,follow';
    }

    private function defaultOgImageUrl(): string
    {
        $configuredOgImage = Setting::getValue('seo_default_og_image');

        if (is_string($configuredOgImage) && filled($configuredOgImage)) {
            if (str_starts_with($configuredOgImage, 'http://') || str_starts_with($configuredOgImage, 'https://')) {
                return $configuredOgImage;
            }

            if (str_starts_with($configuredOgImage, 'assets/')) {
                return asset($configuredOgImage);
            }

            return asset('storage/' . ltrim($configuredOgImage, '/'));
        }

        return asset($this->defaults['og_image']);
    }
}
