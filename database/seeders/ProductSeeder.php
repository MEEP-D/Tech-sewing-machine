<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'may-may-lap-trinh' => Category::where('slug', 'may-may-lap-trinh')->first(),
            'may-vat-so' => Category::where('slug', 'may-vat-so')->first(),
            'may-mot-kim' => Category::where('slug', 'may-mot-kim')->first(),
            'may-hai-kim' => Category::where('slug', 'may-hai-kim')->first(),
        ];

        $products = [
            [
                'name' => 'Máy may lập trình Brother BAS-311H',
                'slug' => 'may-may-lap-trinh-brother-bas-311h',
                'sku' => 'BAS-311H',
                'short_description' => 'Máy may lập trình Brother BAS-311H cho đường may chính xác, tối ưu năng suất. Phù hợp xưởng may, gia công chi tiết.',
                'description' => '<p>Máy may lập trình <strong>Brother BAS-311H</strong> phù hợp may logo, patch, chi tiết lặp lại. Hỗ trợ tư vấn cữ gá và tối ưu quy trình.</p>',
                'price' => '95.000.000',
                'brand' => 'Brother',
                'origin' => 'Nhật Bản',
                'specifications' => [
                    ['key' => 'Tốc độ may', 'value' => '2.700 mũi/phút'],
                    ['key' => 'Điện nguồn', 'value' => '220V - 50Hz'],
                    ['key' => 'Ứng dụng', 'value' => 'May lập trình / pattern'],
                ],
                'thumbnail' => 'assets/frontend/images/anh2.jpg',
                'gallery' => [
                    'assets/frontend/images/anh3.jpg',
                    'assets/frontend/images/anh4.jpg',
                ],
                'category_id' => $categories['may-may-lap-trinh']?->id,
                'status' => 'published',
                'is_featured' => true,
                'is_new' => false,
                'sort_order' => 1,
                'view_count' => 0,
            ],
            [
                'name' => 'Máy vắt sổ 5 chỉ Juki MO-6714S',
                'slug' => 'may-vat-so-5-chi-juki-mo-6714s',
                'sku' => 'MO-6714S',
                'short_description' => 'Máy vắt sổ 5 chỉ Juki MO-6714S bền bỉ, tốc độ cao, phù hợp vải dệt kim và co giãn.',
                'description' => '<p><strong>Juki MO-6714S</strong> hỗ trợ đường vắt sổ đẹp, ổn định. Phù hợp chuyền may áo thun, đồ thể thao.</p>',
                'price' => '48.000.000',
                'brand' => 'Juki',
                'origin' => 'Nhật Bản',
                'specifications' => [
                    ['key' => 'Số chỉ', 'value' => '5 chỉ'],
                    ['key' => 'Tốc độ may', 'value' => '6.500 mũi/phút'],
                    ['key' => 'Ứng dụng', 'value' => 'Vắt sổ / dệt kim'],
                ],
                'thumbnail' => 'assets/frontend/images/anh5.jpg',
                'gallery' => [
                    'assets/frontend/images/anh6.jpg',
                    'assets/frontend/images/anh7.jpg',
                ],
                'category_id' => $categories['may-vat-so']?->id,
                'status' => 'published',
                'is_featured' => true,
                'is_new' => true,
                'sort_order' => 2,
                'view_count' => 0,
            ],
            [
                'name' => 'Máy may một kim Juki DDL-900C',
                'slug' => 'may-may-mot-kim-juki-ddl-900c',
                'sku' => 'DDL-900C',
                'short_description' => 'Máy may 1 kim Juki DDL-900C cho đường may êm, ổn định, phù hợp sản xuất số lượng lớn.',
                'description' => '<p><strong>Juki DDL-900C</strong> là lựa chọn phổ biến cho chuyền may cơ bản. Tối ưu bảo trì, vận hành dễ.</p>',
                'price' => '28.500.000',
                'brand' => 'Juki',
                'origin' => 'Nhật Bản',
                'specifications' => [
                    ['key' => 'Tốc độ may', 'value' => '5.500 mũi/phút'],
                    ['key' => 'Chiều dài mũi', 'value' => '0 - 5 mm'],
                    ['key' => 'Ứng dụng', 'value' => 'May 1 kim / cơ bản'],
                ],
                'thumbnail' => 'assets/frontend/images/anh1.jpg',
                'gallery' => [
                    'assets/frontend/images/anh2.jpg',
                    'assets/frontend/images/anh8.jpg',
                ],
                'category_id' => $categories['may-mot-kim']?->id,
                'status' => 'published',
                'is_featured' => false,
                'is_new' => false,
                'sort_order' => 3,
                'view_count' => 0,
            ],
            [
                'name' => 'Máy may hai kim Siruba D007S',
                'slug' => 'may-may-hai-kim-siruba-d007s',
                'sku' => 'D007S',
                'short_description' => 'Máy may 2 kim Siruba D007S cho đường may kép đẹp, phù hợp jeans/jacket và sản phẩm dày.',
                'description' => '<p><strong>Siruba D007S</strong> tối ưu cho đường may đôi cần độ thẩm mỹ. Hỗ trợ tư vấn kim/chỉ và cữ gá.</p>',
                'price' => '35.000.000',
                'brand' => 'Siruba',
                'origin' => 'Đài Loan',
                'specifications' => [
                    ['key' => 'Số kim', 'value' => '2 kim'],
                    ['key' => 'Tốc độ may', 'value' => '4.500 mũi/phút'],
                    ['key' => 'Ứng dụng', 'value' => 'Jeans / jacket'],
                ],
                'thumbnail' => 'assets/frontend/images/anh4.jpg',
                'gallery' => [
                    'assets/frontend/images/anh5.jpg',
                    'assets/frontend/images/anh6.jpg',
                ],
                'category_id' => $categories['may-hai-kim']?->id,
                'status' => 'published',
                'is_featured' => false,
                'is_new' => true,
                'sort_order' => 4,
                'view_count' => 0,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}

