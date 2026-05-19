<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categorySlugs = [
            'may-lap-trinh-smart', 'kho-nho', 'kho-trung', 'kho-lon', 'may-tui-quan-jean',
            'trang-tri-hoa-van-smart', 'may-dan-tui-tu-dong', 'he-thong-may-in-tu-dong', 'may-ep-seam-hot-air',
            'may-may-1-kim', 'bruce', 'tso', 'durkopp-adler-1-kim',
            'may-may-2-kim', 'kai-lai', 'brother-2-kim', 'siruba-2-kim', 'durkopp-adler-2-kim',
            'may-vat-so', 'may-danh-bong', 'thiet-bi-phong-cat', 'may-trai-vai-tu-dong', 'phong-cad',
            'may-cat-dau-ban', 'may-xu-ly-vai', 'may-trai-vai-tu-dong-cao-cap', 'may-cat-day-vien',
            'may-kiem-vai-da-nang', 'may-mo-tui-tu-dong', 'may-noi-thun-tu-dong', 'may-ep-nhiet-so-mi',
            'may-khuy-mat-phung', 'may-trang-tri-ong-quan-jean', 'may-theu-vi-tinh',
        ];

        $categoryMap = Category::query()->whereIn('slug', $categorySlugs)->pluck('id', 'slug');

        $imagePool = [
            'assets/frontend/images/anh1.jpg', 'assets/frontend/images/anh2.jpg', 'assets/frontend/images/anh3.jpg',
            'assets/frontend/images/anh4.jpg', 'assets/frontend/images/anh5.jpg', 'assets/frontend/images/anh6.jpg',
            'assets/frontend/images/anh7.jpg', 'assets/frontend/images/anh8.jpg',
        ];

        $productDefs = [
            ['name' => 'May Lap Trinh Smart S-220', 'sku' => 'S-220', 'category_slug' => 'kho-nho', 'brand' => 'Smart', 'price' => '122.000.000'],
            ['name' => 'May Lap Trinh Smart S-320', 'sku' => 'S-320', 'category_slug' => 'kho-trung', 'brand' => 'Smart', 'price' => '145.000.000'],
            ['name' => 'May Lap Trinh Smart S-520', 'sku' => 'S-520', 'category_slug' => 'kho-lon', 'brand' => 'Smart', 'price' => '172.000.000'],
            ['name' => 'May Tui Quan Jean Auto J-Pocket', 'sku' => 'JP-88', 'category_slug' => 'may-tui-quan-jean', 'brand' => 'Smart', 'price' => '168.000.000'],
            ['name' => 'May Trang Tri Hoa Van Smart Deco-7', 'sku' => 'DECO-7', 'category_slug' => 'trang-tri-hoa-van-smart', 'brand' => 'Smart', 'price' => '110.000.000'],
            ['name' => 'May Dan Tui Tu Dong PocketBond X1', 'sku' => 'PB-X1', 'category_slug' => 'may-dan-tui-tu-dong', 'brand' => 'KaiLi', 'price' => '138.000.000'],
            ['name' => 'He Thong In Tu Dong PrintFlow 900', 'sku' => 'PF-900', 'category_slug' => 'he-thong-may-in-tu-dong', 'brand' => 'TSO', 'price' => '198.000.000'],
            ['name' => 'May Ep Seam Hot Air HSA-900', 'sku' => 'HSA-900', 'category_slug' => 'may-ep-seam-hot-air', 'brand' => 'Brother', 'price' => '92.000.000'],
            ['name' => 'May May 1 Kim Bruce B1 Direct', 'sku' => 'B1-D', 'category_slug' => 'bruce', 'brand' => 'Bruce', 'price' => '19.800.000'],
            ['name' => 'May May 1 Kim TSO EcoLine', 'sku' => 'TSO-E1', 'category_slug' => 'tso', 'brand' => 'TSO', 'price' => '18.900.000'],
            ['name' => 'May May 1 Kim Durkopp Adler 281', 'sku' => 'DA-281', 'category_slug' => 'durkopp-adler-1-kim', 'brand' => 'Durkopp Adler', 'price' => '55.000.000'],
            ['name' => 'May May 2 Kim Kai Lai KL-842', 'sku' => 'KL-842', 'category_slug' => 'kai-lai', 'brand' => 'Kai Lai', 'price' => '27.500.000'],
            ['name' => 'May May 2 Kim Brother T-8720', 'sku' => 'BR-8720', 'category_slug' => 'brother-2-kim', 'brand' => 'Brother', 'price' => '37.000.000'],
            ['name' => 'May May 2 Kim Siruba D007S', 'sku' => 'D007S', 'category_slug' => 'siruba-2-kim', 'brand' => 'Siruba', 'price' => '35.000.000'],
            ['name' => 'May May 2 Kim Durkopp Adler 867', 'sku' => 'DA-867', 'category_slug' => 'durkopp-adler-2-kim', 'brand' => 'Durkopp Adler', 'price' => '79.000.000'],
            ['name' => 'May Vat So Pegasus M952', 'sku' => 'PG-M952', 'category_slug' => 'may-vat-so', 'brand' => 'Pegasus', 'price' => '46.000.000'],
            ['name' => 'May Danh Bong AutoFill B-10', 'sku' => 'AF-B10', 'category_slug' => 'may-danh-bong', 'brand' => 'Hikari', 'price' => '58.000.000'],
            ['name' => 'May Trai Vai Tu Dong SpreadMax 2.2', 'sku' => 'SM-22', 'category_slug' => 'may-trai-vai-tu-dong', 'brand' => 'Smart', 'price' => '120.000.000'],
            ['name' => 'He Thong Phong CAD Garment CAD Pro', 'sku' => 'CAD-PRO', 'category_slug' => 'phong-cad', 'brand' => 'TSO', 'price' => '150.000.000'],
            ['name' => 'May Cat Dau Ban CutEdge 125', 'sku' => 'CE-125', 'category_slug' => 'may-cat-dau-ban', 'brand' => 'Kai Lai', 'price' => '44.000.000'],
            ['name' => 'May Xu Ly Vai SteamFix 600', 'sku' => 'SF-600', 'category_slug' => 'may-xu-ly-vai', 'brand' => 'Brother', 'price' => '68.000.000'],
            ['name' => 'May Cat Day Vien TrimPro V8', 'sku' => 'TP-V8', 'category_slug' => 'may-cat-day-vien', 'brand' => 'Juki', 'price' => '24.500.000'],
            ['name' => 'May Kiem Vai Da Nang ScanTex 3D', 'sku' => 'ST-3D', 'category_slug' => 'may-kiem-vai-da-nang', 'brand' => 'Smart', 'price' => '84.000.000'],
            ['name' => 'May Mo Tui Tu Dong PocketCut 6A', 'sku' => 'PC-6A', 'category_slug' => 'may-mo-tui-tu-dong', 'brand' => 'Brother', 'price' => '102.000.000'],
            ['name' => 'May Noi Thun Tu Dong ElasticJoin E2', 'sku' => 'EJ-E2', 'category_slug' => 'may-noi-thun-tu-dong', 'brand' => 'Siruba', 'price' => '96.000.000'],
            ['name' => 'May Ep Nhiet So Mi PressShirt X', 'sku' => 'PS-X', 'category_slug' => 'may-ep-nhiet-so-mi', 'brand' => 'Hikari', 'price' => '73.000.000'],
            ['name' => 'May Khuy Mat Phung Eyelet Pro 9', 'sku' => 'EP-9', 'category_slug' => 'may-khuy-mat-phung', 'brand' => 'Juki', 'price' => '89.000.000'],
            ['name' => 'May Trang Tri Ong Quan Jean DecoHem J2', 'sku' => 'DH-J2', 'category_slug' => 'may-trang-tri-ong-quan-jean', 'brand' => 'Bruce', 'price' => '66.000.000'],
            ['name' => 'May Theu Vi Tinh Embroid Pro 12', 'sku' => 'EMB-12', 'category_slug' => 'may-theu-vi-tinh', 'brand' => 'Brother', 'price' => '132.000.000'],
        ];

        foreach ($productDefs as $index => $def) {
            $slug = str($def['name'])->slug()->toString();
            $thumb = $imagePool[$index % count($imagePool)];
            $image = $imagePool[($index + 1) % count($imagePool)];
            $gallery = [$imagePool[($index + 2) % count($imagePool)], $imagePool[($index + 3) % count($imagePool)], $imagePool[($index + 4) % count($imagePool)]];

            Product::updateOrCreate(['sku' => $def['sku']], [
                'name' => $def['name'],
                'slug' => $slug,
                'code' => $def['sku'],
                'sku' => $def['sku'],
                'short_description' => $def['name'] . ' phu hop cho nha may may mac can hieu suat va do on dinh cao.',
                'long_description' => 'Thiet bi ' . $def['name'] . ' duoc cau hinh san de trien khai nhanh tai chuyen may, toi uu thoi gian setup va chi phi van hanh.',
                'description' => '<p><strong>' . e($def['name']) . '</strong> la dong may chuyen dung cho xuong may hien dai, van hanh on dinh va de bao tri.</p>',
                'price' => $def['price'],
                'brand' => $def['brand'],
                'origin' => 'Nhap khau chinh hang',
                'specifications' => [
                    ['key' => 'Toc do van hanh', 'value' => (2200 + ($index * 90)) . ' mui/phut'],
                    ['key' => 'Nguon dien', 'value' => '220V - 50Hz'],
                    ['key' => 'Ung dung', 'value' => $def['name']],
                ],
                'thumbnail' => $thumb,
                'image' => $image,
                'gallery' => $gallery,
                'video_id' => 'W4ycLub-9c0',
                'category_id' => $categoryMap[$def['category_slug']] ?? null,
                'status' => 'published',
                'is_featured' => $index < 8,
                'is_new' => $index % 2 === 0,
                'is_hot' => $index % 3 === 0,
                'is_exclusive' => $index === 0,
                'sort_order' => $index + 1,
                'view_count' => 0,
                'support_prompt' => 'Ban can tu van them ve thong so, nang suat va cau hinh phu hop voi ' . $def['name'] . ' khong?',
                'cta_primary_label' => 'Nhan tu van ky thuat',
                'cta_primary_url' => '/lien-he',
                'cta_secondary_label' => 'Xem them san pham cung nhom',
                'cta_secondary_url' => '/san-pham/danh-muc/' . $def['category_slug'],
                'overview_heading' => 'Tong quan san pham',
                'overview_content' => '<p>' . e($def['name']) . ' phu hop cho doanh nghiep can tang nang suat va dong nhat chat luong duong may.</p>',
                'seo_heading' => 'Loi ich trien khai thuc te',
                'seo_content' => '<p>Giái phap toi uu cho nha may may cong nghiep voi yeu cau van hanh lien tuc.</p>',
            ]);
        }
    }
}

