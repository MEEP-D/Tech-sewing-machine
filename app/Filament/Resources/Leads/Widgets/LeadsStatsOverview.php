<?php

namespace App\Filament\Resources\Leads\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadsStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Lead::count();
        $new = Lead::where('status', 'new')->count();
        $contacted = Lead::where('status', 'contacted')->count();
        $qualified = Lead::where('status', 'qualified')->count();
        $closed = Lead::where('status', 'closed')->count();

        return [
            Stat::make('Tổng liên hệ', (string) $total)->color('primary'),
            Stat::make('Mới', (string) $new)->color('danger'),
            Stat::make('Đã liên hệ', (string) $contacted)->color('warning'),
            Stat::make('Đủ điều kiện', (string) $qualified)->color('success'),
            Stat::make('Đã đóng', (string) $closed)->color('gray'),
        ];
    }
}
