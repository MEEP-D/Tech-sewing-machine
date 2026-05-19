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
            ['key' => 'contact_address', 'label' => 'Địa chỉ văn phòng', 'value' => 'TP. Ho Chi Minh, Viet Nam', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'contact_working_hours', 'label' => 'Giờ làm việc', 'value' => 'Thu 2 - Thu 7: 08:00 - 17:30', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_facebook', 'label' => 'Facebook', 'value' => 'https://facebook.com/techsewing', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_youtube', 'label' => 'YouTube', 'value' => 'https://youtube.com/@techsewing', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'site_title', 'label' => 'Tiêu đề website', 'value' => 'TechSewing - Giái phap may may cong nghiep', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'site_description', 'label' => 'Mô tả website', 'value' => 'Cung cap may may cong nghiep, giai phap tu dong hoa, tu van ky thuat va bao tri toan dien.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_default_title', 'label' => 'SEO title mặc định', 'value' => 'Tech Sewing Machine - Thiet bi may mac cong nghiep', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_default_description', 'label' => 'SEO description mặc định', 'value' => 'Danh mục may may cong nghiep da dang, giai phap day chuyen va dich vu ky thuat nhanh.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_default_og_image', 'label' => 'SEO OG image mặc định', 'value' => 'assets/frontend/images/anh1.jpg', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'home_hero_image', 'label' => 'Ảnh hero trang chu', 'value' => 'assets/frontend/images/anh2.jpg', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_hero_image_enabled', 'label' => 'Bật hero image', 'value' => '1', 'type' => 'boolean', 'group' => 'homepage'],
            ['key' => 'home_slogan_title', 'label' => 'Tiêu đề slogan trang chủ', 'value' => 'Giái phap cong nghe may mac thong minh', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_slogan_subtitle', 'label' => 'Mô tả slogan trang chủ', 'value' => 'Dong hanh cung doanh nghiep may mac voi he thong may chuyen dung va doi ngu ky thuat thuc chien.', 'type' => 'textarea', 'group' => 'homepage'],
            ['key' => 'home_partners_title', 'label' => 'Tiêu đề đối tác', 'value' => 'Doi tac cong nghe hang dau', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_service_title', 'label' => 'Tiêu đề dịch vụ', 'value' => 'Bao hanh va dich vu', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'home_service_description', 'label' => 'Mô tả dịch vụ', 'value' => 'Dich vu trien khai, bao tri va dao tao van hanh nhanh cho nha may may.', 'type' => 'textarea', 'group' => 'homepage'],
            ['key' => 'page_products_kicker', 'label' => 'Dòng phụ trang sản phẩm', 'value' => 'Product experience', 'type' => 'text', 'group' => 'pages'],
            ['key' => 'page_products_heading', 'label' => 'Tiêu đề trang sản phẩm', 'value' => 'Kham pha lineup may may cong nghiep', 'type' => 'text', 'group' => 'pages'],
            ['key' => 'page_products_desc', 'label' => 'Mô tả trang sản phẩm', 'value' => 'Danh mục san pham duoc to chuc theo mega menu de de tim va de quan tri.', 'type' => 'textarea', 'group' => 'pages'],
            ['key' => 'header_quote_label', 'label' => 'Nút báo giá header', 'value' => 'Bao gia', 'type' => 'text', 'group' => 'menu'],
            ['key' => 'header_products_menu_style', 'label' => 'Kiểu menu sản phẩm', 'value' => 'mega-menu', 'type' => 'text', 'group' => 'menu'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
