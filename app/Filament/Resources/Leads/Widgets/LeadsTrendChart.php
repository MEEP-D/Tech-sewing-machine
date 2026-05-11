<?php

namespace App\Filament\Resources\Leads\Widgets;

use App\Models\Lead;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class LeadsTrendChart extends ChartWidget
{
    protected ?string $heading = 'Xu hướng liên hệ 14 ngày';

    protected ?string $maxHeight = '240px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $start = now()->subDays(13)->startOfDay();
        $rows = Lead::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd');

        $labels = [];
        $data = [];

        for ($i = 13; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('d/m');
            $data[] = (int) ($rows[$key] ?? 0);
        }

        return [
            'datasets' => [[
                'label' => 'Lead / ngày',
                'data' => $data,
                'borderColor' => '#3b82f6',
                'backgroundColor' => 'rgba(59,130,246,0.15)',
                'fill' => true,
                'tension' => 0.35,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
