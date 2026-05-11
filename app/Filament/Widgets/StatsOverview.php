<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Tổng sản phẩm', Product::count())
                ->description('Tất cả sản phẩm hiện có')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('Tổng bài viết', Post::count())
                ->description('Tin tức, sự kiện, hội thảo')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),
            Stat::make('Tổng lượt xem', Product::sum('view_count') + Post::sum('view_count'))
                ->description('Toàn bộ lượt xem nội dung')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning'),
        ];
    }
}
