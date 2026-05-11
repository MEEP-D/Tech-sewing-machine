<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AdminQuickActionsWidget extends Widget
{
    protected static ?int $sort = 2;

    protected string $view = 'filament.widgets.admin-quick-actions-widget';

    protected int|string|array $columnSpan = 1;

}
