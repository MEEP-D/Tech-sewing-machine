<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'label' => 'Ten doanh nghiep', 'value' => 'Tech Sewing Machine', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_phone', 'label' => 'So dien thoai lien he', 'value' => '0909 123 456', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_hotline', 'label' => 'Hotline', 'value' => '0902 806 599', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'label' => 'Email lien he', 'value' => 'info@techsewing.vn', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'label' => 'Dia chi van phong', 'value' => 'TP. Ho Chi Minh, Viet Nam', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'contact_working_hours', 'label' => 'Gio lam viec', 'value' => 'Thu 2 - Thu 7: 08:00 - 17:30', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_facebook', 'label' => 'Facebook', 'value' => 'https://facebook.com/techsewing', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_youtube', 'label' => 'YouTube', 'value' => 'https://youtube.com/@techsewing', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'site_title', 'label' => 'Tieu de website', 'value' => 'TechSewing - Giai phap may may cong nghiep', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'site_description', 'label' => 'Mo ta website', 'value' => 'Cung cap may may cong nghiep, giai phap tu dong hoa, tu van ky thuat va bao tri toan dien.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_default_title', 'label' => 'SEO title mac dinh', 'value' => 'Tech Sewing Machine - Thiet bi may mac cong nghiep', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_default_description', 'label' => 'SEO description mac dinh', 'value' => 'Danh muc may may cong nghiep da dang, giai phap day chuyen va dich vu ky thuat nhanh.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_default_og_image', 'label' => 'SEO OG image mac dinh', 'value' => 'assets/frontend/images/anh1.jpg', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'home_hero_image', 'label' => 'Anh hero trang chu', 'value' => 'assets/frontend/images/anh2.jpg', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_hero_image_enabled', 'label' => 'Bat hero image', 'value' => '1', 'type' => 'boolean', 'group' => 'homepage'],
            ['key' => 'home_slogan_title', 'label' => 'Tieu de slogan trang chu', 'value' => 'Giai phap cong nghe may mac thong minh', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_slogan_subtitle', 'label' => 'Mo ta slogan trang chu', 'value' => 'Dong hanh cung doanh nghiep may mac voi he thong may chuyen dung va doi ngu ky thuat thuc chien.', 'type' => 'textarea', 'group' => 'homepage'],
            ['key' => 'home_partners_title', 'label' => 'Tieu de doi tac', 'value' => 'Doi tac cong nghe hang dau', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_service_title', 'label' => 'Tieu de dich vu', 'value' => 'Bao hanh va dich vu', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_service_description', 'label' => 'Mo ta dich vu', 'value' => 'Dich vu trien khai, bao tri va dao tao van hanh nhanh cho nha may may.', 'type' => 'textarea', 'group' => 'homepage'],
            ['key' => 'page_products_kicker', 'label' => 'Dong phu trang san pham', 'value' => 'Product experience', 'type' => 'text', 'group' => 'pages'],
            ['key' => 'page_products_heading', 'label' => 'Tieu de trang san pham', 'value' => 'Kham pha lineup may may cong nghiep', 'type' => 'text', 'group' => 'pages'],
            ['key' => 'page_products_desc', 'label' => 'Mo ta trang san pham', 'value' => 'Danh muc san pham duoc to chuc theo mega menu de de tim va de quan tri.', 'type' => 'textarea', 'group' => 'pages'],
            ['key' => 'header_quote_label', 'label' => 'Nut bao gia header', 'value' => 'Bao gia', 'type' => 'text', 'group' => 'menu'],
            ['key' => 'header_products_menu_style', 'label' => 'Kieu menu san pham', 'value' => 'mega-menu', 'type' => 'text', 'group' => 'menu'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
