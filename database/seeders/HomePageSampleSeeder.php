<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductSpec;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class HomePageSampleSeeder extends Seeder
{
    public function run(): void
    {
        if (Schema::hasTable('sliders')) {
            $sliders = [
                [
                    'image' => 'assets/frontend/images/anh1.jpg',
                    'title' => 'Giải Pháp Công Nghệ May Mặc Thông Minh',
                    'subtitle' => 'Tiên phong trong lĩnh vực tự động hóa ngành may tại Việt Nam.',
                    'link' => route('products.index'),
                    'sort_order' => 1,
                    'is_active' => true,
                ],
                [
                    'image' => 'assets/frontend/images/anh2.jpg',
                    'title' => 'Hiệu Suất Vượt Trội - Chất Lượng Đỉnh Cao',
                    'subtitle' => 'Hệ thống máy may lập trình Smart giúp tối ưu quy trình sản xuất.',
                    'link' => route('products.index'),
                    'sort_order' => 2,
                    'is_active' => true,
                ],
                [
                    'image' => 'assets/frontend/images/anh3.jpg',
                    'title' => 'Đối Tác Tin Cậy Của Mọi Doanh Nghiệp',
                    'subtitle' => 'Hỗ trợ kỹ thuật tận tâm và cung cấp giải pháp tối ưu chi phí.',
                    'link' => route('about'),
                    'sort_order' => 3,
                    'is_active' => true,
                ],
            ];

            foreach ($sliders as $data) {
                Slider::updateOrCreate(
                    ['sort_order' => $data['sort_order']],
                    $data
                );
            }
        }

        $partnerNames = ['Brother', 'JUKI', 'Siruba', 'Jack', 'Pegasus', 'Bruce', 'Hikari', 'Smart Tech'];
        foreach ($partnerNames as $index => $name) {
            Partner::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $index + 1, 'is_active' => true]
            );
        }

        Setting::updateOrCreate(['key' => 'contact_hotline'], ['label' => 'Hotline', 'group' => 'general', 'type' => 'text', 'value' => '0902 806 599']);
        Setting::updateOrCreate(['key' => 'contact_email'], ['label' => 'Email', 'group' => 'general', 'type' => 'text', 'value' => 'info@maymaythongminh.vn']);
        Setting::updateOrCreate(['key' => 'contact_address'], ['label' => 'Địa chỉ', 'group' => 'general', 'type' => 'text', 'value' => 'TP. Hồ Chí Minh, Việt Nam']);
        Setting::updateOrCreate(['key' => 'header_quote_label'], ['label' => 'Nhãn báo giá header', 'group' => 'homepage', 'type' => 'text', 'value' => 'Báo giá']);
        Setting::updateOrCreate(['key' => 'footer_about_title'], ['label' => 'Tiêu đề footer giới thiệu', 'group' => 'homepage', 'type' => 'text', 'value' => 'Về chúng tôi']);
        Setting::updateOrCreate(['key' => 'footer_about_text'], ['label' => 'Nội dung footer giới thiệu', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'Cung cấp giải pháp máy may công nghiệp thông minh, tối ưu năng suất và chất lượng sản xuất.']);
        Setting::updateOrCreate(['key' => 'home_partners_title'], ['label' => 'Tiêu đề đối tác trang chủ', 'group' => 'homepage', 'type' => 'text', 'value' => 'Đối tác công nghệ hàng đầu']);
        Setting::updateOrCreate(['key' => 'home_service_title'], ['label' => 'Tiêu đề dịch vụ trang chủ', 'group' => 'homepage', 'type' => 'text', 'value' => 'Bảo hành và dịch vụ']);
        Setting::updateOrCreate(['key' => 'home_service_description'], ['label' => 'Mô tả dịch vụ trang chủ', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'Hệ thống showroom, phân phối và xưởng dịch vụ rộng khắp, đáp ứng nhanh nhu cầu vận hành của doanh nghiệp.']);
        Setting::updateOrCreate(['key' => 'home_service_primary_cta'], ['label' => 'Nút CTA chính dịch vụ trang chủ', 'group' => 'homepage', 'type' => 'text', 'value' => 'ĐẶT LỊCH BẢO DƯỠNG']);
        Setting::updateOrCreate(['key' => 'home_service_secondary_cta'], ['label' => 'Nút CTA phụ dịch vụ trang chủ', 'group' => 'homepage', 'type' => 'text', 'value' => 'CHÍNH SÁCH']);
        Setting::updateOrCreate(['key' => 'about_title'], ['label' => 'Tiêu đề giới thiệu', 'group' => 'homepage', 'type' => 'text', 'value' => 'Về chúng tôi']);
        Setting::updateOrCreate(['key' => 'about_subtitle'], ['label' => 'Mô tả giới thiệu', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'Hành trình khẳng định vị thế dẫn đầu trong công nghệ máy may công nghiệp.']);
        Setting::updateOrCreate(['key' => 'about_company_name'], ['label' => 'Tên công ty giới thiệu', 'group' => 'homepage', 'type' => 'text', 'value' => 'Công ty TNHH Đạt Hưng Việt Nam']);
        Setting::updateOrCreate(['key' => 'about_intro'], ['label' => 'Giới thiệu ngắn', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'Máy May Thông Minh là đơn vị cung cấp giải pháp máy may công nghiệp thông minh, tự động hóa sản xuất và tối ưu vận hành cho doanh nghiệp may mặc.']);
        Setting::updateOrCreate(['key' => 'about_slogan'], ['label' => 'Slogan giới thiệu', 'group' => 'homepage', 'type' => 'text', 'value' => 'UY TÍN - SẢN PHẨM CHÍNH HÃNG - CHẤT LƯỢNG CAM KẾT - CÔNG NGHỆ THÔNG MINH']);
        Setting::updateOrCreate(['key' => 'about_body'], ['label' => 'Nội dung giới thiệu', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'Chúng tôi tập trung vào thiết bị chính hãng, triển khai kỹ thuật thực tế tại xưởng và đồng hành bảo trì lâu dài để doanh nghiệp duy trì năng suất ổn định.']);
        Setting::updateOrCreate(['key' => 'contact_page_title'], ['label' => 'Tiêu đề trang liên hệ', 'group' => 'homepage', 'type' => 'text', 'value' => 'Liên hệ']);
        Setting::updateOrCreate(['key' => 'contact_page_subtitle'], ['label' => 'Mô tả trang liên hệ', 'group' => 'homepage', 'type' => 'text', 'value' => 'Thông tin liên hệ']);
        Setting::updateOrCreate(['key' => 'home_faqs'], ['label' => 'FAQ trang chủ', 'group' => 'homepage', 'type' => 'json', 'value' => json_encode([
            ['question' => 'Máy may thông minh có dễ sử dụng không?', 'answer' => 'Giao diện thân thiện, có đội kỹ thuật lắp đặt và đào tạo vận hành tại chỗ.'],
            ['question' => 'Chính sách bảo hành như thế nào?', 'answer' => 'Sản phẩm chính hãng bảo hành theo từng dòng máy và điều khoản tại hợp đồng.'],
            ['question' => 'Có hỗ trợ giao hàng và lắp đặt không?', 'answer' => 'Hỗ trợ giao hàng toàn quốc, lắp đặt tận nơi theo khu vực.'],
        ])]);

        $highlight = Product::query()->where('sku', 'X-1209D')->first();
        if (! $highlight) {
            $highlight = Product::query()->first();
        }

        if ($highlight) {
            $highlight->update([
                'code' => 'X-1209D',
                'sku' => 'X-1209D',
                'name' => 'Máy Lấy Dấu Tự Động X-1209D',
                'short_description' => 'Giải pháp in sang dấu tự động độ chính xác cao.',
                'long_description' => 'Dòng máy sang dấu tự động X-1209D mang lại độ chính xác kỹ thuật cao, phù hợp cho nhiều chất liệu.',
                'image' => 'assets/frontend/images/anh1.jpg',
                'video_id' => 'W4ycLub-9c0',
                'is_new' => true,
                'is_hot' => true,
                'is_exclusive' => true,
                'status' => 'published',
            ]);

            if (Schema::hasTable('product_specs')) {
                $specs = [
                    ['key' => 'Kích thước làm việc', 'value' => '1200*900mm'],
                    ['key' => 'Điện áp / Công suất', 'value' => '220V / 4KW'],
                    ['key' => 'Kích thước chân máy', 'value' => '3.7x2.2x2.2m'],
                    ['key' => 'Trọng lượng máy', 'value' => '1000KG'],
                    ['key' => 'Áp suất khí nén', 'value' => '0.6MPa'],
                    ['key' => 'Tốc độ tối đa', 'value' => '1500mm/s'],
                    ['key' => 'Dữ liệu đầu vào', 'value' => 'PLT/DXF/HPGL'],
                    ['key' => 'Phương pháp cấp liệu', 'value' => 'Băng tải 3 giai đoạn'],
                ];

                foreach ($specs as $index => $spec) {
                    ProductSpec::updateOrCreate(
                        ['product_id' => $highlight->id, 'key' => $spec['key']],
                        ['value' => $spec['value'], 'sort_order' => $index + 1]
                    );
                }
            }
        }
    }
}
