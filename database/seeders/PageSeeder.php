<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Giới thiệu',
                'slug' => 'gioi-thieu',
                'excerpt' => 'Tech Sewing Machine cung cấp máy may công nghiệp, tư vấn dây chuyền và hỗ trợ kỹ thuật.',
                'content' => '<p><strong>Tech Sewing Machine</strong> đồng hành cùng xưởng may từ khâu chọn máy, setup dây chuyền đến bảo trì sau bán hàng. Chúng tôi tập trung vào hiệu suất, độ ổn định và khả năng vận hành lâu dài.</p>',
                'image' => 'assets/frontend/images/anh2.jpg',
                'is_active' => true,
                'layout' => 'content',
                'layout_mode' => 'content',
                'container_class' => 'section-shell',
                'bg_color' => null,
                'text_color' => null,
                'spacing_top' => 'pt-12',
                'spacing_bottom' => 'pb-12',
                'cache_enabled' => true,
                'cache_ttl' => 3600,
                'style_config' => ['variant' => 'default'],
            ],
            [
                'title' => 'Chính sách bảo hành',
                'slug' => 'chinh-sach-bao-hanh',
                'excerpt' => 'Điều kiện bảo hành và quy trình hỗ trợ kỹ thuật sau bán hàng.',
                'content' => '<p>Chính sách bảo hành áp dụng theo model và tình trạng sử dụng. Vui lòng liên hệ hotline để được tiếp nhận và xử lý nhanh nhất.</p>',
                'image' => 'assets/frontend/images/anh3.jpg',
                'is_active' => true,
                'layout' => 'content',
                'layout_mode' => 'content',
                'container_class' => 'section-shell',
                'spacing_top' => 'pt-12',
                'spacing_bottom' => 'pb-12',
                'cache_enabled' => true,
                'cache_ttl' => 86400,
                'style_config' => ['variant' => 'policy'],
            ],
            [
                'title' => 'Chính sách vận chuyển',
                'slug' => 'chinh-sach-van-chuyen',
                'excerpt' => 'Thông tin giao hàng, lắp đặt và hướng dẫn vận hành.',
                'content' => '<p>Tech Sewing Machine hỗ trợ vận chuyển, bàn giao và hướng dẫn vận hành tận nơi theo khu vực và giá trị đơn hàng.</p>',
                'image' => 'assets/frontend/images/anh4.jpg',
                'is_active' => true,
                'layout' => 'content',
                'layout_mode' => 'content',
                'container_class' => 'section-shell',
                'spacing_top' => 'pt-12',
                'spacing_bottom' => 'pb-12',
                'cache_enabled' => true,
                'cache_ttl' => 86400,
                'style_config' => ['variant' => 'policy'],
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(['slug' => $page['slug']], $page);
        }
    }
}

