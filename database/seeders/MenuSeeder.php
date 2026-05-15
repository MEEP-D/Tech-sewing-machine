<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->upsertMenu(['location' => 'header', 'label' => 'Trang Chu', 'parent_id' => null], ['url' => '/', 'route_name' => 'home', 'target' => '_self', 'sort_order' => 1, 'is_active' => true, 'icon' => 'home', 'css_class' => null, 'meta_config' => ['is_primary' => true]]);
        $this->upsertMenu(['location' => 'header', 'label' => 'Gioi Thieu', 'parent_id' => null], ['url' => '/gioi-thieu', 'route_name' => 'about', 'target' => '_self', 'sort_order' => 2, 'is_active' => true, 'icon' => 'info', 'css_class' => null, 'meta_config' => []]);
        $products = $this->upsertMenu(['location' => 'header', 'label' => 'San Pham', 'parent_id' => null], ['url' => '/san-pham', 'route_name' => 'products.index', 'target' => '_self', 'sort_order' => 3, 'is_active' => true, 'icon' => 'grid', 'css_class' => null, 'meta_config' => ['has_dropdown' => true, 'menu_type' => 'mega']]);
        $this->upsertMenu(['location' => 'header', 'label' => 'Ky Thuat', 'parent_id' => null], ['url' => '/tin-tuc/danh-muc/huong-dan-ky-thuat', 'route_name' => null, 'target' => '_self', 'sort_order' => 4, 'is_active' => true, 'icon' => 'wrench', 'css_class' => null, 'meta_config' => []]);
        $this->upsertMenu(['location' => 'header', 'label' => 'Tin Tuc', 'parent_id' => null], ['url' => '/tin-tuc', 'route_name' => 'news.index', 'target' => '_self', 'sort_order' => 5, 'is_active' => true, 'icon' => 'newspaper', 'css_class' => null, 'meta_config' => []]);
        $this->upsertMenu(['location' => 'header', 'label' => 'Lien He', 'parent_id' => null], ['url' => '/lien-he', 'route_name' => 'contact', 'target' => '_self', 'sort_order' => 6, 'is_active' => true, 'icon' => 'phone', 'css_class' => 'v-nav-cta', 'meta_config' => ['variant' => 'cta']]);

        $megaColumns = [
            'May Lap Trinh Va Tu Dong' => ['May Lap Trinh Smart' => ['Kho Nho', 'Kho Trung', 'Kho Lon', 'May Tui Quan Jean'], 'Trang Tri Hoa Van Smart' => [], 'May Dan Tui Tu Dong' => [], 'He Thong May In Tu Dong' => [], 'May Ep Seam Hot Air' => []],
            'May May Cong Nghiep' => ['May May 1 Kim' => ['Bruce', 'TSO', 'Durkopp Adler'], 'May May 2 Kim' => ['Kai Lai', 'Brother', 'Siruba', 'Durkopp Adler 2 Kim'], 'May Vat So' => [], 'May Danh Bong' => []],
            'Phong Cat Va Xu Ly Vai' => ['Thiet Bi Phong Cat' => ['May Trai Vai Tu Dong', 'Phong CAD', 'May Cat Dau Ban', 'May Xu Ly Vai'], 'May Trai Vai Tu Dong Cao Cap' => [], 'May Cat Day Vien' => [], 'May Kiem Vai Da Nang' => []],
            'Giai Phap Chuyen Dung' => ['May Mo Tui Tu Dong' => [], 'May Noi Thun Tu Dong' => [], 'May Ep Nhiet So Mi' => [], 'May Khuy Mat Phung' => [], 'May Trang Tri Ong Quan Jean' => [], 'May Theu Vi Tinh' => []],
        ];

        $allCategories = Category::query()->where('type', 'product')->get()->keyBy('name');

        $columnSort = 1;
        foreach ($megaColumns as $columnName => $groups) {
            $column = $this->upsertMenu(['location' => 'header', 'label' => $columnName, 'parent_id' => $products->id], ['url' => null, 'route_name' => null, 'target' => '_self', 'sort_order' => $columnSort++, 'is_active' => true, 'icon' => null, 'css_class' => 'mega-col-title', 'meta_config' => ['mega_column' => true]]);

            $groupSort = 1;
            foreach ($groups as $groupName => $subItems) {
                $groupCategory = $allCategories->get($groupName);
                $groupMenu = $this->upsertMenu(['location' => 'header', 'label' => $groupName, 'parent_id' => $column->id], ['url' => $groupCategory ? '/san-pham/danh-muc/' . $groupCategory->slug : null, 'route_name' => null, 'target' => '_self', 'sort_order' => $groupSort++, 'is_active' => true, 'icon' => null, 'css_class' => null, 'meta_config' => ['has_children' => count($subItems) > 0]]);

                foreach (array_values($subItems) as $subIndex => $subName) {
                    $subCategory = $allCategories->get($subName);
                    $this->upsertMenu(['location' => 'header', 'label' => $subName, 'parent_id' => $groupMenu->id], ['url' => $subCategory ? '/san-pham/danh-muc/' . $subCategory->slug : null, 'route_name' => null, 'target' => '_self', 'sort_order' => $subIndex + 1, 'is_active' => true, 'icon' => null, 'css_class' => 'mega-sub-link', 'meta_config' => []]);
                }
            }
        }

        $footerItems = [
            ['label' => 'Gioi Thieu', 'url' => '/gioi-thieu', 'sort_order' => 1],
            ['label' => 'Chinh sach bao hanh', 'url' => '/trang/chinh-sach-bao-hanh', 'sort_order' => 2],
            ['label' => 'Chinh sach van chuyen', 'url' => '/trang/chinh-sach-van-chuyen', 'sort_order' => 3],
            ['label' => 'Lien He', 'url' => '/lien-he', 'sort_order' => 4],
        ];

        foreach ($footerItems as $item) {
            $this->upsertMenu(['location' => 'footer', 'label' => $item['label'], 'parent_id' => null], ['url' => $item['url'], 'route_name' => null, 'target' => '_self', 'sort_order' => $item['sort_order'], 'is_active' => true, 'icon' => null, 'css_class' => null, 'meta_config' => []]);
        }
    }

    private function upsertMenu(array $identity, array $values): Menu
    {
        $menu = Menu::query()->where($identity)->first();
        if ($menu) {
            $menu->fill($values)->save();
            return $menu;
        }

        return Menu::create(array_merge($identity, $values));
    }
}
