<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'label' => 'Tên doanh nghiệp', 'value' => 'Tech Sewing Machine', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_phone', 'label' => 'Số điện thoại liên hệ', 'value' => '0909 123 456', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_hotline', 'label' => 'Hotline', 'value' => '0902 806 599', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'label' => 'Email liên hệ', 'value' => 'info@techsewing.vn', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'label' => 'Địa chỉ văn phòng', 'value' => 'TP. Hồ Chí Minh, Việt Nam', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'contact_working_hours', 'label' => 'Giờ làm việc', 'value' => 'Thứ 2 - Thứ 7: 08:00 - 17:30', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_facebook', 'label' => 'Facebook', 'value' => 'https://facebook.com/techsewing', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_youtube', 'label' => 'YouTube', 'value' => 'https://youtube.com/@techsewing', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'site_title', 'label' => 'Tiêu đề website', 'value' => 'TechSewing - Giải pháp máy may công nghiệp', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'site_description', 'label' => 'Mô tả website', 'value' => 'Cung cấp máy may công nghiệp, giải pháp tự động hóa, tư vấn kỹ thuật và bảo trì toàn diện.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_default_title', 'label' => 'SEO title mặc định', 'value' => 'Tech Sewing Machine - Thiết bị may mặc công nghiệp', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_default_description', 'label' => 'SEO description mặc định', 'value' => 'Danh mục máy may công nghiệp đa dạng, giải pháp dây chuyền và dịch vụ kỹ thuật nhanh.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_default_og_image', 'label' => 'SEO OG image mặc định', 'value' => 'assets/frontend/images/anh1.jpg', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'home_hero_image', 'label' => 'Ảnh hero trang chủ', 'value' => 'assets/frontend/images/anh2.jpg', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_hero_image_enabled', 'label' => 'Bật hero image', 'value' => '1', 'type' => 'boolean', 'group' => 'homepage'],
            ['key' => 'home_partners_title', 'label' => 'Tiêu đề đối tác', 'value' => 'Đối tác công nghệ hàng đầu', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_service_title', 'label' => 'Tiêu đề dịch vụ', 'value' => 'Bảo hành và dịch vụ', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_service_description', 'label' => 'Mô tả dịch vụ', 'value' => 'Dịch vụ triển khai, bảo trì và đào tạo vận hành nhanh cho nhà máy may.', 'type' => 'textarea', 'group' => 'homepage'],
            ['key' => 'page_products_kicker', 'label' => 'Dòng phụ trang sản phẩm', 'value' => 'Trải nghiệm sản phẩm', 'type' => 'text', 'group' => 'pages'],
            ['key' => 'page_products_heading', 'label' => 'Tiêu đề trang sản phẩm', 'value' => 'Khám phá danh mục máy may công nghiệp', 'type' => 'text', 'group' => 'pages'],
            ['key' => 'page_products_desc', 'label' => 'Mô tả trang sản phẩm', 'value' => 'Danh mục sản phẩm được tổ chức theo mega menu để dễ tìm và dễ quản trị.', 'type' => 'textarea', 'group' => 'pages'],
            ['key' => 'header_quote_label', 'label' => 'Nút báo giá header', 'value' => 'Báo giá', 'type' => 'text', 'group' => 'menu'],
            ['key' => 'header_products_menu_style', 'label' => 'Kiểu menu sản phẩm', 'value' => 'mega-menu', 'type' => 'text', 'group' => 'menu'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
