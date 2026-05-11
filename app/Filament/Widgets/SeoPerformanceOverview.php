<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\SeoMeta;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SeoPerformanceOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalContent = Product::count() + Post::count() + Page::count();
        $seoMetaCount = SeoMeta::count();
        $coverage = $totalContent > 0 ? round(($seoMetaCount / $totalContent) * 100, 1) : 0;

        $missingMetaDescription = SeoMeta::query()
            ->where(function ($query) {
                $query->whereNull('meta_description')->orWhere('meta_description', '');
            })
            ->count();

        $missingOgImage = SeoMeta::query()
            ->where(function ($query) {
                $query->whereNull('og_image')->orWhere('og_image', '');
            })
            ->count();

        $missingCanonical = SeoMeta::query()
            ->where(function ($query) {
                $query->whereNull('canonical_url')->orWhere('canonical_url', '');
            })
            ->count();

        $focusKeywordFilled = SeoMeta::query()
            ->whereNotNull('focus_keyword')
            ->where('focus_keyword', '!=', '')
            ->count();

        $noIndexCount = SeoMeta::query()->where('no_index', true)->count();

        return [
            Stat::make('Độ phủ SEO', "{$coverage}%")
                ->description("{$seoMetaCount}/{$totalContent} nội dung có SEO meta")
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($coverage >= 80 ? 'success' : ($coverage >= 50 ? 'warning' : 'danger')),

            Stat::make('Thiếu Meta Description', (string) $missingMetaDescription)
                ->description('Số bản ghi SEO chưa có mô tả')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($missingMetaDescription === 0 ? 'success' : 'warning'),

            Stat::make('Thiếu OG Image', (string) $missingOgImage)
                ->description('Bản ghi SEO chưa có ảnh chia sẻ')
                ->descriptionIcon('heroicon-m-photo')
                ->color($missingOgImage === 0 ? 'success' : 'warning'),

            Stat::make('Thiếu Canonical URL', (string) $missingCanonical)
                ->description('Bản ghi SEO chưa có canonical')
                ->descriptionIcon('heroicon-m-link')
                ->color($missingCanonical === 0 ? 'success' : 'warning'),

            Stat::make('Có Focus Keyword', (string) $focusKeywordFilled)
                ->description("{$seoMetaCount} bản ghi SEO tổng")
                ->descriptionIcon('heroicon-m-hashtag')
                ->color('primary'),

            Stat::make('Đang Noindex', (string) $noIndexCount)
                ->description('Trang đang chặn index')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color($noIndexCount > 0 ? 'danger' : 'success'),
        ];
    }
}
