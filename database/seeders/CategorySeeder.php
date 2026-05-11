<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $productCategories = [
            ['name' => 'Máy may lập trình', 'slug' => 'may-may-lap-trinh', 'type' => 'product', 'description' => 'Máy may lập trình tự động cho xưởng may: độ chính xác cao, tối ưu năng suất.'],
            ['name' => 'Máy vắt sổ', 'slug' => 'may-vat-so', 'type' => 'product', 'description' => 'Máy vắt sổ công nghiệp 3/4/5 chỉ, chuyên vải dệt kim và vải co giãn.'],
            ['name' => 'Máy một kim', 'slug' => 'may-mot-kim', 'type' => 'product', 'description' => 'Máy may 1 kim công nghiệp: bền bỉ, dễ vận hành, phù hợp nhiều chuyền may.'],
            ['name' => 'Máy hai kim', 'slug' => 'may-hai-kim', 'type' => 'product', 'description' => 'Máy may 2 kim cho đường may kép, ứng dụng jeans/jacket/quần áo dày.'],
            ['name' => 'Máy thùa khuyết', 'slug' => 'may-thua-khuyet', 'type' => 'product', 'description' => 'Máy thùa khuyết tự động và bán tự động, mũi đều, tốc độ ổn định.'],
            ['name' => 'Máy đính cúc', 'slug' => 'may-dinh-cuc', 'type' => 'product', 'description' => 'Máy đính cúc đa dạng kiểu cúc, phù hợp áo sơ mi, đồng phục và thời trang.'],
            ['name' => 'Phụ kiện & linh kiện', 'slug' => 'phu-kien-linh-kien', 'type' => 'product', 'description' => 'Kim, suốt, ổ, chân vịt, dao vắt sổ và linh kiện thay thế chính hãng.'],
        ];

        $newsCategories = [
            ['name' => 'Tin tức ngành may', 'slug' => 'tin-tuc-nganh-may', 'type' => 'news', 'description' => 'Tin tức, xu hướng và chuyển động công nghệ ngành may mặc.'],
            ['name' => 'Hội chợ dệt may', 'slug' => 'hoi-cho-det-may', 'type' => 'news', 'description' => 'Thông tin hội chợ, triển lãm, lịch sự kiện dệt may trong và ngoài nước.'],
            ['name' => 'Hội thảo ngành may', 'slug' => 'hoi-thao-nganh-may', 'type' => 'news', 'description' => 'Hội thảo kỹ thuật, quy trình và tối ưu vận hành chuyền may.'],
            ['name' => 'Sản phẩm mới', 'slug' => 'san-pham-moi', 'type' => 'news', 'description' => 'Giới thiệu model mới, tính năng mới và ứng dụng thực tế.'],
            ['name' => 'Hướng dẫn & kỹ thuật', 'slug' => 'huong-dan-ky-thuat', 'type' => 'news', 'description' => 'Hướng dẫn vận hành, bảo trì, xử lý lỗi và checklist cho kỹ thuật viên.'],
        ];

        foreach (array_merge($productCategories, $newsCategories) as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true, 'sort_order' => 0, 'image' => null, 'parent_id' => null])
            );
        }
    }
}

