<?php

namespace Tests\Unit;

use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_grouped_returns_menus_by_location(): void
    {
        Menu::create([
            'location' => 'header',
            'label' => 'Trang chủ',
            'url' => '/',
            'target' => '_self',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Menu::create([
            'location' => 'footer',
            'label' => 'Liên hệ',
            'url' => '/lien-he',
            'target' => '_self',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $grouped = app(MenuService::class)->grouped();

        $this->assertArrayHasKey('header', $grouped);
        $this->assertArrayHasKey('footer', $grouped);
    }

    public function test_tree_orders_children_by_sort_order(): void
    {
        $parent = Menu::create([
            'location' => 'header',
            'label' => 'Sản phẩm',
            'url' => '/san-pham',
            'target' => '_self',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Menu::create([
            'location' => 'header',
            'label' => 'B',
            'url' => '/b',
            'target' => '_self',
            'parent_id' => $parent->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Menu::create([
            'location' => 'header',
            'label' => 'A',
            'url' => '/a',
            'target' => '_self',
            'parent_id' => $parent->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $menus = app(MenuService::class)->grouped()['header'];
        $tree = (new MenuService())->tree($menus);

        $this->assertCount(1, $tree);
        $this->assertSame('Sản phẩm', $tree[0]['label']);
        $this->assertSame('A', $tree[0]['children'][0]['label']);
        $this->assertSame('B', $tree[0]['children'][1]['label']);
    }
}
