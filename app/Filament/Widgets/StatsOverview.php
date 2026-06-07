<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\SeoMeta;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 2;

    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $totalPosts = Post::count();
        $totalPages = Page::count();
        $totalContent = $totalProducts + $totalPosts + $totalPages;

        $totalViews = (int) Product::sum('view_count') + (int) Post::sum('view_count');
        $avgViews = $totalContent > 0 ? round($totalViews / $totalContent, 1) : 0;

        $seoMetaCount = SeoMeta::count();
        $seoCoverage = $totalContent > 0 ? round(($seoMetaCount / $totalContent) * 100, 1) : 0;

        $missingCanonical = SeoMeta::query()
            ->where(function ($query) {
                $query->whereNull('canonical_url')->orWhere('canonical_url', '');
            })->count();

        $missingOg = SeoMeta::query()
            ->where(function ($query) {
                $query->whereNull('og_image')->orWhere('og_image', '');
            })->count();

        $noIndexCount = SeoMeta::query()->where('no_index', true)->count();
        $focusKeywordCount = SeoMeta::query()
            ->whereNotNull('focus_keyword')
            ->where('focus_keyword', '!=', '')
            ->count();

        return [
            Stat::make('Tổng sản phẩm', (string) $totalProducts)
                ->description('Tất cả sản phẩm hiện có')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Tổng bài viết', (string) $totalPosts)
                ->description('Tin tức, sự kiện, hội thảo')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),

            Stat::make('Tổng lượt xem', number_format($totalViews))
                ->description('Toàn bộ lượt xem nội dung')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning'),

            Stat::make('TB lượt xem / nội dung', (string) $avgViews)
                ->description("Trên {$totalContent} nội dung")
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),

            Stat::make('Độ phủ SEO', "{$seoCoverage}%")
                ->description("{$seoMetaCount}/{$totalContent} có SEO meta")
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($seoCoverage >= 80 ? 'success' : ($seoCoverage >= 50 ? 'warning' : 'danger')),

            Stat::make('Thiếu canonical', (string) $missingCanonical)
                ->description('Cần bổ sung canonical URL')
                ->descriptionIcon('heroicon-m-link')
                ->color($missingCanonical === 0 ? 'success' : 'warning'),

            Stat::make('Thiếu OG image', (string) $missingOg)
                ->description('Cần bổ sung ảnh chia sẻ')
                ->descriptionIcon('heroicon-m-photo')
                ->color($missingOg === 0 ? 'success' : 'warning'),

            Stat::make('Noindex / Focus keyword', "{$noIndexCount} / {$focusKeywordCount}")
                ->description('Noindex và số bản ghi có từ khóa')
                ->descriptionIcon('heroicon-m-adjustments-horizontal')
                ->color($noIndexCount > 0 ? 'danger' : 'success'),
        ];
    }
}
