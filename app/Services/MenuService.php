<?php

namespace App\Services;

use App\Models\Menu;

class MenuService
{
    public function grouped(): array
    {
        return Menu::query()
            ->where('is_active', true)
            ->orderBy('location')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Menu $menu) => [
                'id' => $menu->id,
                'location' => $menu->location,
                'label' => $menu->label,
                'url' => $menu->url,
                'route_name' => $menu->route_name,
                'target' => $menu->target,
                'parent_id' => $menu->parent_id,
                'sort_order' => $menu->sort_order,
                'icon' => $menu->icon,
                'css_class' => $menu->css_class,
            ])
            ->groupBy('location')
            ->toArray();
    }

    public function tree(array $menus, ?int $parentId = null): array
    {
        return collect($menus)
            ->where('parent_id', $parentId)
            ->sortBy('sort_order')
            ->map(function ($menu) use ($menus) {
                $menu['children'] = $this->tree($menus, $menu['id']);
                return $menu;
            })
            ->values()
            ->all();
    }
}
