<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->upsertMenu(['location' => 'header', 'label' => 'Trang chủ', 'parent_id' => null], ['url' => '/', 'route_name' => 'home', 'target' => '_self', 'sort_order' => 1, 'is_active' => true, 'icon' => 'home', 'css_class' => null, 'meta_config' => ['is_primary' => true]]);
        $this->upsertMenu(['location' => 'header', 'label' => 'Giới thiệu', 'parent_id' => null], ['url' => '/gioi-thieu', 'route_name' => 'about', 'target' => '_self', 'sort_order' => 2, 'is_active' => true, 'icon' => 'info', 'css_class' => null, 'meta_config' => []]);
        $products = $this->upsertMenu(['location' => 'header', 'label' => 'Sản phẩm', 'parent_id' => null], ['url' => '/san-pham', 'route_name' => 'products.index', 'target' => '_self', 'sort_order' => 3, 'is_active' => true, 'icon' => 'grid', 'css_class' => null, 'meta_config' => ['has_dropdown' => true, 'menu_type' => 'mega']]);
        $this->upsertMenu(['location' => 'header', 'label' => 'Kỹ thuật', 'parent_id' => null], ['url' => '/tin-tuc/danh-muc/huong-dan-ky-thuat', 'route_name' => null, 'target' => '_self', 'sort_order' => 4, 'is_active' => true, 'icon' => 'wrench', 'css_class' => null, 'meta_config' => []]);
        $this->upsertMenu(['location' => 'header', 'label' => 'Tin tức', 'parent_id' => null], ['url' => '/tin-tuc', 'route_name' => 'news.index', 'target' => '_self', 'sort_order' => 5, 'is_active' => true, 'icon' => 'newspaper', 'css_class' => null, 'meta_config' => []]);
        $this->upsertMenu(['location' => 'header', 'label' => 'Liên hệ', 'parent_id' => null], ['url' => '/lien-he', 'route_name' => 'contact', 'target' => '_self', 'sort_order' => 6, 'is_active' => true, 'icon' => 'phone', 'css_class' => 'v-nav-cta', 'meta_config' => ['variant' => 'cta']]);

        $megaColumns = [
            'Máy Lập Trình Và Tự Động' => ['Máy Lập Trình Smart' => ['Khổ Nhỏ', 'Khổ Trung', 'Khổ Lớn', 'Máy Túi Quần Jean'], 'Trang Trí Hoa Văn Smart' => [], 'Máy Dán Túi Tự Động' => [], 'Hệ Thống Máy In Tự Động' => [], 'Máy Ép Seam Hot Air' => []],
            'Máy May Công Nghiệp' => ['Máy May 1 Kim' => ['Bruce', 'TSO', 'Durkopp Adler'], 'Máy May 2 Kim' => ['Kai Lai', 'Brother', 'Siruba', 'Durkopp Adler 2 Kim'], 'Máy Vắt Sổ' => [], 'Máy Đánh Bóng' => []],
            'Phòng Cắt Và Xử Lý Vải' => ['Thiết Bị Phòng Cắt' => ['Máy Trải Vải Tự Động', 'Phòng CAD', 'Máy Cắt Đầu Bàn', 'Máy Xử Lý Vải'], 'Máy Trải Vải Tự Động Cao Cấp' => [], 'Máy Cắt Dây Viền' => [], 'Máy Kiểm Vải Đa Năng' => []],
            'Giải Pháp Chuyên Dụng' => ['Máy Mổ Túi Tự Động' => [], 'Máy Nối Thun Tự Động' => [], 'Máy Ép Nhiệt Sơ Mi' => [], 'Máy Khuy Mắt Phụng' => [], 'Máy Trang Trí Ống Quần Jean' => [], 'Máy Thêu Vi Tính' => []],
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
            ['label' => 'Giới thiệu', 'url' => '/gioi-thieu', 'sort_order' => 1],
            ['label' => 'Chính sách bảo hành', 'url' => '/trang/chinh-sach-bao-hanh', 'sort_order' => 2],
            ['label' => 'Chính sách vận chuyển', 'url' => '/trang/chinh-sach-van-chuyen', 'sort_order' => 3],
            ['label' => 'Liên hệ', 'url' => '/lien-he', 'sort_order' => 4],
        ];

        foreach ($footerItems as $item) {
            $this->upsertMenu(['location' => 'footer', 'label' => $item['label'], 'parent_id' => null], ['url' => $item['url'], 'route_name' => null, 'target' => '_self', 'sort_order' => $item['sort_order'], 'is_active' => true, 'icon' => null, 'css_class' => null, 'meta_config' => []]);
        }
    }

    private function upsertMenu(array $identity, array $values): Menu
    {
        $menu = Menu::query()->where($identity)->first();
        if (! $menu && array_key_exists('sort_order', $values)) {
            $menu = Menu::query()
                ->where('location', $identity['location'])
                ->where('parent_id', $identity['parent_id'])
                ->where('sort_order', $values['sort_order'])
                ->when(array_key_exists('url', $values), fn ($query) => $query->where('url', $values['url']))
                ->first();
        }

        if ($menu) {
            $menu->fill(array_merge($identity, $values))->save();
            return $menu;
        }

        return Menu::create(array_merge($identity, $values));
    }
}
