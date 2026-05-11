<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $home = Menu::firstOrCreate([
            'location' => 'header',
            'label' => 'Trang chủ',
        ], [
            'url' => '/',
            'route_name' => 'home',
            'target' => '_self',
            'sort_order' => 1,
            'is_active' => true,
            'icon' => 'home',
            'css_class' => null,
            'meta_config' => ['is_primary' => true],
        ]);

        $products = Menu::firstOrCreate([
            'location' => 'header',
            'label' => 'Sản phẩm',
        ], [
            'url' => '/san-pham',
            'route_name' => 'products.index',
            'target' => '_self',
            'sort_order' => 2,
            'is_active' => true,
            'icon' => 'grid',
            'css_class' => null,
            'meta_config' => ['has_dropdown' => true],
        ]);

        Menu::firstOrCreate([
            'location' => 'header',
            'label' => 'Tin tức',
        ], [
            'url' => '/tin-tuc',
            'route_name' => 'news.index',
            'target' => '_self',
            'sort_order' => 3,
            'is_active' => true,
            'icon' => 'newspaper',
            'css_class' => null,
            'meta_config' => [],
        ]);

        Menu::firstOrCreate([
            'location' => 'header',
            'label' => 'Liên hệ',
        ], [
            'url' => '/lien-he',
            'route_name' => 'contact',
            'target' => '_self',
            'sort_order' => 4,
            'is_active' => true,
            'icon' => 'phone',
            'css_class' => 'v-nav-cta',
            'meta_config' => ['variant' => 'cta'],
        ]);

        Menu::firstOrCreate([
            'location' => 'header',
            'label' => 'Danh mục nổi bật',
        ], [
            'url' => null,
            'route_name' => null,
            'target' => '_self',
            'parent_id' => $products->id,
            'sort_order' => 1,
            'is_active' => true,
            'icon' => 'sparkles',
            'css_class' => null,
            'meta_config' => ['caption' => 'Top categories'],
        ]);

        $footerItems = [
            ['label' => 'Giới thiệu', 'url' => '/gioi-thieu', 'sort_order' => 1],
            ['label' => 'Chính sách bảo hành', 'url' => '/trang/chinh-sach-bao-hanh', 'sort_order' => 2],
            ['label' => 'Chính sách vận chuyển', 'url' => '/trang/chinh-sach-van-chuyen', 'sort_order' => 3],
        ];

        foreach ($footerItems as $item) {
            Menu::firstOrCreate([
                'location' => 'footer',
                'label' => $item['label'],
            ], [
                'url' => $item['url'],
                'target' => '_self',
                'sort_order' => $item['sort_order'],
                'is_active' => true,
                'meta_config' => [],
            ]);
        }
    }
}

