<?php

namespace App\Filament\Resources\Leads\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;

class LeadsStatusChart extends ChartWidget
{
    protected ?string $heading = 'Phân bố trạng thái liên hệ';

    protected ?string $maxHeight = '240px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $labels = ['Mới', 'Đã liên hệ', 'Đủ điều kiện', 'Đã đóng'];
        $data = [
            Lead::where('status', 'new')->count(),
            Lead::where('status', 'contacted')->count(),
            Lead::where('status', 'qualified')->count(),
            Lead::where('status', 'closed')->count(),
        ];

        return [
            'datasets' => [[
                'label' => 'Số lượng',
                'data' => $data,
                'backgroundColor' => ['#ef4444', '#f59e0b', '#10b981', '#64748b'],
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
