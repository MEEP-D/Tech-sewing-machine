<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\Widget;

class AdminLeadInboxWidget extends Widget
{
    protected static ?int $sort = 5;

    protected string $view = 'filament.widgets.admin-lead-inbox-widget';

    protected int|string|array $columnSpan = 1;

    protected function getViewData(): array
    {
        return [
            'newLeadsCount' => Lead::query()->where('status', 'new')->count(),
            'latestLeads' => Lead::query()
                ->latest('id')
                ->limit(3)
                ->get(['id', 'name', 'phone', 'created_at', 'status']),
        ];
    }
}
