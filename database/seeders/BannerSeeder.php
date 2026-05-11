<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'key' => 'homepage-hero',
                'title' => 'Tech Sewing Machine',
                'subtitle' => 'Giải pháp máy may công nghiệp',
                'image' => 'assets/frontend/images/anh1.jpg',
                'link' => '/lien-he',
                'button_text' => 'Nhận tư vấn ngay',
                'size_label' => 'Desktop hero',
                'recommended_size' => '1920x900',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'key' => 'homepage-cta',
                'title' => 'Sẵn sàng nâng cấp xưởng may?',
                'subtitle' => 'Tư vấn dây chuyền, demo và báo giá',
                'image' => 'assets/frontend/images/anh6.jpg',
                'link' => '/lien-he',
                'button_text' => 'Gửi yêu cầu',
                'size_label' => 'CTA banner',
                'recommended_size' => '1600x700',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::firstOrCreate(['key' => $banner['key']], $banner);
        }
    }
}

