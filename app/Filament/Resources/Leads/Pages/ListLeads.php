<?php

namespace App\Filament\Resources\Leads\Pages;

use App\Filament\Resources\Leads\LeadResource;
use App\Filament\Resources\Leads\Widgets\LeadsStatsOverview;
use App\Filament\Resources\Leads\Widgets\LeadsStatusChart;
use App\Filament\Resources\Leads\Widgets\LeadsTrendChart;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            LeadsStatsOverview::class,
            LeadsStatusChart::class,
            LeadsTrendChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'default' => 1,
            'xl' => 2,
        ];
    }
}
