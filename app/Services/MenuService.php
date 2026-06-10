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

    public function tree(array $menus, ?int $parentId = null, array $visitedIds = [], int $depth = 0): array
    {
        if ($depth > 20) {
            return [];
        }

        return collect($menus)
            ->where('parent_id', $parentId)
            ->sortBy('sort_order')
            ->reject(function ($menu) use ($visitedIds) {
                $id = data_get($menu, 'id');

                return ! is_int($id) && ! ctype_digit((string) $id)
                    ? false
                    : in_array((int) $id, $visitedIds, true);
            })
            ->map(function ($menu) use ($menus, $visitedIds, $depth) {
                $id = (int) data_get($menu, 'id', 0);
                $nextVisitedIds = $id > 0 ? [...$visitedIds, $id] : $visitedIds;

                $menu['children'] = $this->tree($menus, $id > 0 ? $id : null, $nextVisitedIds, $depth + 1);

                return $menu;
            })
            ->values()
            ->all();
    }
}
