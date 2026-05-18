<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminLeadInboxWidget;
use App\Filament\Widgets\AdminQuickActionsWidget;
use App\Filament\Widgets\AdminRecentContentWidget;
use App\Filament\Widgets\NewsletterQueueStatusWidget;
use App\Filament\Widgets\SeoPerformanceChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class AdminDashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Admin';

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'Tổng quan nhanh, thao tác nhanh và nội dung mới nhất.';
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'default' => 1,
            'lg' => 2,
            'xl' => 4,
        ];
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            SeoPerformanceChart::class,
            AdminQuickActionsWidget::class,
            NewsletterQueueStatusWidget::class,
            AdminLeadInboxWidget::class,
            AdminRecentContentWidget::class,
        ];
    }
}
