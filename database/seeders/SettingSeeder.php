<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Contact / brand
            ['key' => 'company_name', 'label' => 'Tên doanh nghiệp', 'value' => 'Tech Sewing Machine', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_phone', 'label' => 'Số điện thoại liên hệ', 'value' => '0909 123 456', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_hotline', 'label' => 'Hotline', 'value' => '0903 000 000', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'label' => 'Email liên hệ', 'value' => 'info@techsewing.vn', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'label' => 'Địa chỉ văn phòng', 'value' => '123 Đường ABC, Quận Tân Bình, TP. Hồ Chí Minh', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'contact_working_hours', 'label' => 'Giờ làm việc', 'value' => 'Thứ 2 - Thứ 7: 08:00 - 17:30', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_zalo', 'label' => 'Zalo', 'value' => '0909 123 456', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_facebook', 'label' => 'Facebook', 'value' => 'https://facebook.com/techsewing', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_youtube', 'label' => 'YouTube', 'value' => 'https://youtube.com/@techsewing', 'type' => 'text', 'group' => 'contact'],

            // Branding assets (paths)
            ['key' => 'site_logo', 'label' => 'Logo sáng', 'value' => 'site/logo.png', 'type' => 'text', 'group' => 'branding'],
            ['key' => 'site_logo_dark', 'label' => 'Logo tối', 'value' => 'site/logo-dark.png', 'type' => 'text', 'group' => 'branding'],
            ['key' => 'site_logo_mobile', 'label' => 'Logo mobile', 'value' => 'site/logo-mobile.png', 'type' => 'text', 'group' => 'branding'],
            ['key' => 'site_favicon', 'label' => 'Favicon', 'value' => 'site/favicon.png', 'type' => 'text', 'group' => 'branding'],

            // SEO defaults
            ['key' => 'site_title', 'label' => 'Tiêu đề trang web (SEO)', 'value' => 'TechSewing - Giải pháp máy may công nghiệp', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'site_description', 'label' => 'Mô tả trang web (SEO)', 'value' => 'Cung cấp máy may công nghiệp, tư vấn dây chuyền, demo và hỗ trợ kỹ thuật cho xưởng may. Danh mục sản phẩm, tin tức và hướng dẫn vận hành.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_default_title', 'label' => 'SEO title mặc định', 'value' => 'Tech Sewing Machine - Thiết bị may mặc công nghiệp', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_default_description', 'label' => 'SEO description mặc định', 'value' => 'Chuyên cung cấp máy may lập trình, máy vắt sổ, máy một kim và thiết bị may mặc công nghiệp. Tư vấn, demo, báo giá và hỗ trợ kỹ thuật.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_default_og_image', 'label' => 'SEO OG image mặc định', 'value' => 'images/og-default.jpg', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_default_canonical', 'label' => 'SEO canonical mặc định', 'value' => config('app.url'), 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_organization_name', 'label' => 'SEO organization name', 'value' => 'Tech Sewing Machine', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_organization_url', 'label' => 'SEO organization URL', 'value' => config('app.url'), 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_robots_default', 'label' => 'SEO robots default', 'value' => 'index, follow', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_enable_schema', 'label' => 'Bật schema', 'value' => '1', 'type' => 'boolean', 'group' => 'seo'],
            ['key' => 'seo_enable_og', 'label' => 'Bật OG', 'value' => '1', 'type' => 'boolean', 'group' => 'seo'],

            // Homepage
            ['key' => 'special_product_id', 'label' => 'ID sản phẩm đặc biệt (Trang chủ)', 'value' => '1', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_hero_image', 'label' => 'Ảnh hero trang chủ', 'value' => 'assets/frontend/images/anh1.jpg', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_hero_image_enabled', 'label' => 'Bật hero image', 'value' => '1', 'type' => 'boolean', 'group' => 'homepage'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

