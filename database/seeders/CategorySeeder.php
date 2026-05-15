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
            ['name' => 'May Lap Trinh Va Tu Dong', 'slug' => 'may-lap-trinh-va-tu-dong', 'children' => [
                ['name' => 'May Lap Trinh Smart', 'slug' => 'may-lap-trinh-smart', 'children' => [
                    ['name' => 'Kho Nho', 'slug' => 'kho-nho'],
                    ['name' => 'Kho Trung', 'slug' => 'kho-trung'],
                    ['name' => 'Kho Lon', 'slug' => 'kho-lon'],
                    ['name' => 'May Tui Quan Jean', 'slug' => 'may-tui-quan-jean'],
                ]],
                ['name' => 'Trang Tri Hoa Van Smart', 'slug' => 'trang-tri-hoa-van-smart'],
                ['name' => 'May Dan Tui Tu Dong', 'slug' => 'may-dan-tui-tu-dong'],
                ['name' => 'He Thong May In Tu Dong', 'slug' => 'he-thong-may-in-tu-dong'],
                ['name' => 'May Ep Seam Hot Air', 'slug' => 'may-ep-seam-hot-air'],
            ]],
            ['name' => 'May May Cong Nghiep', 'slug' => 'may-may-cong-nghiep', 'children' => [
                ['name' => 'May May 1 Kim', 'slug' => 'may-may-1-kim', 'children' => [
                    ['name' => 'Bruce', 'slug' => 'bruce'],
                    ['name' => 'TSO', 'slug' => 'tso'],
                    ['name' => 'Durkopp Adler', 'slug' => 'durkopp-adler-1-kim'],
                ]],
                ['name' => 'May May 2 Kim', 'slug' => 'may-may-2-kim', 'children' => [
                    ['name' => 'Kai Lai', 'slug' => 'kai-lai'],
                    ['name' => 'Brother', 'slug' => 'brother-2-kim'],
                    ['name' => 'Siruba', 'slug' => 'siruba-2-kim'],
                    ['name' => 'Durkopp Adler 2 Kim', 'slug' => 'durkopp-adler-2-kim'],
                ]],
                ['name' => 'May Vat So', 'slug' => 'may-vat-so'],
                ['name' => 'May Danh Bong', 'slug' => 'may-danh-bong'],
            ]],
            ['name' => 'Phong Cat Va Xu Ly Vai', 'slug' => 'phong-cat-va-xu-ly-vai', 'children' => [
                ['name' => 'Thiet Bi Phong Cat', 'slug' => 'thiet-bi-phong-cat', 'children' => [
                    ['name' => 'May Trai Vai Tu Dong', 'slug' => 'may-trai-vai-tu-dong'],
                    ['name' => 'Phong CAD', 'slug' => 'phong-cad'],
                    ['name' => 'May Cat Dau Ban', 'slug' => 'may-cat-dau-ban'],
                    ['name' => 'May Xu Ly Vai', 'slug' => 'may-xu-ly-vai'],
                ]],
                ['name' => 'May Trai Vai Tu Dong Cao Cap', 'slug' => 'may-trai-vai-tu-dong-cao-cap'],
                ['name' => 'May Cat Day Vien', 'slug' => 'may-cat-day-vien'],
                ['name' => 'May Kiem Vai Da Nang', 'slug' => 'may-kiem-vai-da-nang'],
            ]],
            ['name' => 'Giai Phap Chuyen Dung', 'slug' => 'giai-phap-chuyen-dung', 'children' => [
                ['name' => 'May Mo Tui Tu Dong', 'slug' => 'may-mo-tui-tu-dong'],
                ['name' => 'May Noi Thun Tu Dong', 'slug' => 'may-noi-thun-tu-dong'],
                ['name' => 'May Ep Nhiet So Mi', 'slug' => 'may-ep-nhiet-so-mi'],
                ['name' => 'May Khuy Mat Phung', 'slug' => 'may-khuy-mat-phung'],
                ['name' => 'May Trang Tri Ong Quan Jean', 'slug' => 'may-trang-tri-ong-quan-jean'],
                ['name' => 'May Theu Vi Tinh', 'slug' => 'may-theu-vi-tinh'],
            ]],
        ];

        $sort = 1;
        foreach ($productTree as $node) {
            $this->syncCategoryNode($node, null, $sort++);
        }

        $newsCategories = ['Tin tuc nganh may', 'Hoi cho det may', 'Hoi thao nganh may', 'San pham moi', 'Huong dan ky thuat'];
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
