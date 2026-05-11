<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Filament\Widgets\Widget;

class AdminRecentContentWidget extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.admin-recent-content-widget';

    protected int|string|array $columnSpan = 1;

    protected function getViewData(): array
    {
        return [
            'latestProducts' => Product::query()->latest('id')->limit(3)->get(['id', 'name', 'created_at']),
            'latestPosts' => Post::query()->latest('id')->limit(3)->get(['id', 'title', 'created_at']),
            'latestPages' => Page::query()->latest('id')->limit(3)->get(['id', 'title', 'created_at']),
        ];
    }
}
