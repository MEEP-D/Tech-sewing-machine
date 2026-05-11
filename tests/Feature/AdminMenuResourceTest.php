<?php

namespace Tests\Feature;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMenuResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_fields_persist_correctly(): void
    {
        $menu = Menu::create([
            'location' => 'header',
            'label' => 'Trang chủ',
            'url' => '/',
            'route_name' => 'home',
            'target' => '_self',
            'parent_id' => null,
            'sort_order' => 1,
            'icon' => 'heroicon-o-home',
            'css_class' => 'font-semibold',
            'meta_config' => ['x' => 1],
            'is_active' => true,
        ]);

        $this->assertSame('header', $menu->location);
        $this->assertSame('Trang chủ', $menu->label);
        $this->assertSame('_self', $menu->target);
        $this->assertTrue($menu->is_active);
        $this->assertSame(['x' => 1], $menu->meta_config);
    }

    public function test_menu_children_relationship_works(): void
    {
        $parent = Menu::create([
            'location' => 'header',
            'label' => 'Sản phẩm',
            'url' => '/san-pham',
            'route_name' => null,
            'target' => '_self',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $child = Menu::create([
            'location' => 'header',
            'label' => 'Máy may',
            'url' => '/san-pham/may-may',
            'route_name' => null,
            'target' => '_self',
            'parent_id' => $parent->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $this->assertTrue($parent->children->contains($child));
    }
}
