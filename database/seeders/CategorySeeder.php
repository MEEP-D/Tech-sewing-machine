<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $productTree = [
            ['name' => 'Máy Lập Trình Và Tự Động', 'slug' => 'may-lap-trinh-va-tu-dong', 'children' => [
                ['name' => 'Máy Lập Trình Smart', 'slug' => 'may-lap-trinh-smart', 'children' => [
                    ['name' => 'Khổ Nhỏ', 'slug' => 'kho-nho'],
                    ['name' => 'Khổ Trung', 'slug' => 'kho-trung'],
                    ['name' => 'Khổ Lớn', 'slug' => 'kho-lon'],
                    ['name' => 'Máy Túi Quần Jean', 'slug' => 'may-tui-quan-jean'],
                ]],
                ['name' => 'Trang Trí Hoa Văn Smart', 'slug' => 'trang-tri-hoa-van-smart'],
                ['name' => 'Máy Dán Túi Tự Động', 'slug' => 'may-dan-tui-tu-dong'],
                ['name' => 'Hệ Thống Máy In Tự Động', 'slug' => 'he-thong-may-in-tu-dong'],
                ['name' => 'Máy Ép Seam Hot Air', 'slug' => 'may-ep-seam-hot-air'],
            ]],
            ['name' => 'Máy May Công Nghiệp', 'slug' => 'may-may-cong-nghiep', 'children' => [
                ['name' => 'Máy May 1 Kim', 'slug' => 'may-may-1-kim', 'children' => [
                    ['name' => 'Bruce', 'slug' => 'bruce'],
                    ['name' => 'TSO', 'slug' => 'tso'],
                    ['name' => 'Durkopp Adler', 'slug' => 'durkopp-adler-1-kim'],
                ]],
                ['name' => 'Máy May 2 Kim', 'slug' => 'may-may-2-kim', 'children' => [
                    ['name' => 'Kai Lai', 'slug' => 'kai-lai'],
                    ['name' => 'Brother', 'slug' => 'brother-2-kim'],
                    ['name' => 'Siruba', 'slug' => 'siruba-2-kim'],
                    ['name' => 'Durkopp Adler 2 Kim', 'slug' => 'durkopp-adler-2-kim'],
                ]],
                ['name' => 'Máy Vắt Sổ', 'slug' => 'may-vat-so'],
                ['name' => 'Máy Đánh Bóng', 'slug' => 'may-danh-bong'],
            ]],
            ['name' => 'Phòng Cắt Và Xử Lý Vải', 'slug' => 'phong-cat-va-xu-ly-vai', 'children' => [
                ['name' => 'Thiết Bị Phòng Cắt', 'slug' => 'thiet-bi-phong-cat', 'children' => [
                    ['name' => 'Máy Trải Vải Tự Động', 'slug' => 'may-trai-vai-tu-dong'],
                    ['name' => 'Phòng CAD', 'slug' => 'phong-cad'],
                    ['name' => 'Máy Cắt Đầu Bàn', 'slug' => 'may-cat-dau-ban'],
                    ['name' => 'Máy Xử Lý Vải', 'slug' => 'may-xu-ly-vai'],
                ]],
                ['name' => 'Máy Trải Vải Tự Động Cao Cấp', 'slug' => 'may-trai-vai-tu-dong-cao-cap'],
                ['name' => 'Máy Cắt Dây Viền', 'slug' => 'may-cat-day-vien'],
                ['name' => 'Máy Kiểm Vải Đa Năng', 'slug' => 'may-kiem-vai-da-nang'],
            ]],
            ['name' => 'Giải Pháp Chuyên Dụng', 'slug' => 'giai-phap-chuyen-dung', 'children' => [
                ['name' => 'Máy Mổ Túi Tự Động', 'slug' => 'may-mo-tui-tu-dong'],
                ['name' => 'Máy Nối Thun Tự Động', 'slug' => 'may-noi-thun-tu-dong'],
                ['name' => 'Máy Ép Nhiệt Sơ Mi', 'slug' => 'may-ep-nhiet-so-mi'],
                ['name' => 'Máy Khuy Mắt Phụng', 'slug' => 'may-khuy-mat-phung'],
                ['name' => 'Máy Trang Trí Ống Quần Jean', 'slug' => 'may-trang-tri-ong-quan-jean'],
                ['name' => 'Máy Thêu Vi Tính', 'slug' => 'may-theu-vi-tinh'],
            ]],
        ];

        $sort = 1;
        foreach ($productTree as $node) {
            $this->syncCategoryNode($node, null, $sort++);
        }

        $newsCategories = ['Tin tức ngành may', 'Hội chợ dệt may', 'Hội thảo ngành may', 'Sản phẩm mới', 'Hướng dẫn kỹ thuật'];
        foreach ($newsCategories as $index => $name) {
            Category::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => $name, 'type' => 'news', 'parent_id' => null, 'image' => null, 'is_active' => true, 'sort_order' => $index + 1]);
        }
    }

    private function syncCategoryNode(array $node, ?int $parentId, int $sortOrder): void
    {
        $category = Category::updateOrCreate(['slug' => $node['slug']], ['name' => $node['name'], 'description' => $node['name'], 'type' => 'product', 'parent_id' => $parentId, 'image' => null, 'is_active' => true, 'sort_order' => $sortOrder]);
        foreach (array_values($node['children'] ?? []) as $index => $child) {
            $this->syncCategoryNode($child, $category->id, $index + 1);
        }
    }
}
