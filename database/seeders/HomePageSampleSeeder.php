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
        Setting::updateOrCreate(['key' => 'header_quote_label'], ['label' => 'Nhãn báo giá header', 'group' => 'homepage', 'type' => 'text', 'value' => 'Bao gia']);
        Setting::updateOrCreate(['key' => 'footer_about_title'], ['label' => 'Tiêu đề footer giới thiệu', 'group' => 'homepage', 'type' => 'text', 'value' => 'Ve Chung Toi']);
        Setting::updateOrCreate(['key' => 'footer_about_text'], ['label' => 'Nội dung footer giới thiệu', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'Cung cap giai phap may may cong nghiep thong minh, toi uu nang suat va chat luong san xuat.']);
        Setting::updateOrCreate(['key' => 'home_slogan_title'], ['label' => 'Tiêu đề slogan trang chủ', 'group' => 'homepage', 'type' => 'text', 'value' => 'May May Thong Minh - Chuyen Nghiep - Uy Tin - Hien Dai']);
        Setting::updateOrCreate(['key' => 'home_slogan_subtitle'], ['label' => 'Mô tả slogan trang chủ', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'Tien phong cong nghe tu dong hoa, nang tam hieu suat nganh may mac Viet Nam.']);
        Setting::updateOrCreate(['key' => 'home_partners_title'], ['label' => 'Tiêu đề đối tác trang chủ', 'group' => 'homepage', 'type' => 'text', 'value' => 'Doi Tac Cong Nghe Hang Dau']);
        Setting::updateOrCreate(['key' => 'home_service_title'], ['label' => 'Tiêu đề dịch vụ trang chủ', 'group' => 'homepage', 'type' => 'text', 'value' => 'Bao hanh va Dich vu']);
        Setting::updateOrCreate(['key' => 'home_service_description'], ['label' => 'Mô tả dịch vụ trang chủ', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'He thong showroom, phan phoi va xuong dich vu rong khap, dap ung nhanh nhu cau van hanh cua doanh nghiep.']);
        Setting::updateOrCreate(['key' => 'home_service_primary_cta'], ['label' => 'Nút CTA chính dịch vụ trang chủ', 'group' => 'homepage', 'type' => 'text', 'value' => 'DAT LICH BAO DUONG']);
        Setting::updateOrCreate(['key' => 'home_service_secondary_cta'], ['label' => 'Nút CTA phụ dịch vụ trang chủ', 'group' => 'homepage', 'type' => 'text', 'value' => 'CHINH SACH']);
        Setting::updateOrCreate(['key' => 'about_title'], ['label' => 'Tiêu đề giới thiệu', 'group' => 'homepage', 'type' => 'text', 'value' => 'Ve Chung Toi']);
        Setting::updateOrCreate(['key' => 'about_subtitle'], ['label' => 'Mô tả giới thiệu', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'Hanh trinh khang dinh vi the dan dau trong cong nghe may may cong nghiep.']);
        Setting::updateOrCreate(['key' => 'about_company_name'], ['label' => 'Tên công ty giới thiệu', 'group' => 'homepage', 'type' => 'text', 'value' => 'Cong ty TNHH Dat Hung Viet Nam']);
        Setting::updateOrCreate(['key' => 'about_intro'], ['label' => 'Giới thiệu ngắn', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'May May Thong Minh la don vi cung cap giai phap may may cong nghiep thong minh, tu dong hoa san xuat va toi uu van hanh cho doanh nghiep may mac.']);
        Setting::updateOrCreate(['key' => 'about_slogan'], ['label' => 'Slogan giới thiệu', 'group' => 'homepage', 'type' => 'text', 'value' => 'UY TIN - SAN PHAM CHINH HANG - CHAT LUONG CAM KET - CONG NGHE THONG MINH']);
        Setting::updateOrCreate(['key' => 'about_body'], ['label' => 'Nội dung giới thiệu', 'group' => 'homepage', 'type' => 'textarea', 'value' => 'Chung toi tap trung vao thiet bi chinh hang, trien khai ky thuat thuc te tai xuong va dong hanh bao tri lau dai de doanh nghiep duy tri nang suat on dinh.']);
        Setting::updateOrCreate(['key' => 'contact_page_title'], ['label' => 'Tiêu đề trang liên hệ', 'group' => 'homepage', 'type' => 'text', 'value' => 'Lien he']);
        Setting::updateOrCreate(['key' => 'contact_page_subtitle'], ['label' => 'Mô tả trang liên hệ', 'group' => 'homepage', 'type' => 'text', 'value' => 'Thong tin lien he']);
        Setting::updateOrCreate(['key' => 'home_faqs'], ['label' => 'FAQ trang chủ', 'group' => 'homepage', 'type' => 'json', 'value' => json_encode([
            ['question' => 'May may thong minh co de su dung khong?', 'answer' => 'Giáo dien than thien, co doi ky thuat lap dat va dao tao van hanh tai cho.'],
            ['question' => 'Chính sách bảo hành nhu the nao?', 'answer' => 'San pham chinh hang bao hanh theo tung dong may va dieu khoan tai hop dong.'],
            ['question' => 'Co ho tro giao hang va lap dat khong?', 'answer' => 'Ho tro giao hang toan quoc, lap dat tan noi theo khu vuc.'],
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
