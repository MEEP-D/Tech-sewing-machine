<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\SeoMeta;
use Filament\Widgets\ChartWidget;

class SeoPerformanceChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected ?string $heading = 'Biểu đồ hiệu quả SEO';

    protected ?string $description = 'Tỷ lệ có SEO meta theo nhóm nội dung';

    protected ?string $maxHeight = '210px';

    protected function getData(): array
    {
        $typeMap = [
            Product::class => 'Sản phẩm',
            Post::class => 'Bài viết',
            Page::class => 'Trang',
        ];

        $totalByType = [
            Product::class => Product::count(),
            Post::class => Post::count(),
            Page::class => Page::count(),
        ];

        $seoByType = SeoMeta::query()
            ->selectRaw('seoable_type, COUNT(*) as total')
            ->whereIn('seoable_type', array_keys($typeMap))
            ->groupBy('seoable_type')
            ->pluck('total', 'seoable_type')
            ->all();

        $labels = [];
        $coverageData = [];

        foreach ($typeMap as $class => $label) {
            $total = $totalByType[$class] ?? 0;
            $hasSeo = (int) ($seoByType[$class] ?? 0);
            $coverage = $total > 0 ? round(($hasSeo / $total) * 100, 1) : 0;

            $labels[] = $label;
            $coverageData[] = $coverage;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Độ phủ SEO (%)',
                    'data' => $coverageData,
                    'backgroundColor' => ['#60a5fa', '#34d399', '#f59e0b'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
