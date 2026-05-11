<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first() ?? User::first();
        $category = Category::where('slug', 'huong-dan-ky-thuat')->first();

        $posts = [
            [
                'title' => 'Cách bảo trì máy may 1 kim Juki đúng cách tại xưởng',
                'slug' => 'cach-bao-tri-may-may-1-kim-juki',
                'excerpt' => 'Checklist vệ sinh, tra dầu, cân chỉnh cơ bản để máy may 1 kim vận hành ổn định và giảm lỗi bỏ mũi.',
                'content' => '<p>Bảo trì định kỳ giúp máy chạy êm, giảm hao mòn và hạn chế lỗi kỹ thuật. Dưới đây là checklist theo tuần/tháng, kèm các lưu ý cho kỹ thuật viên.</p>',
                'thumbnail' => 'assets/frontend/images/anh7.jpg',
                'category_id' => $category?->id,
                'author_id' => $admin?->id,
                'status' => 'published',
                'type' => 'news',
                'published_at' => now()->subDays(2),
                'event_date' => null,
                'event_location' => null,
                'is_featured' => true,
                'view_count' => 560,
            ],
            [
                'title' => 'Xử lý lỗi bỏ mũi trên máy vắt sổ: nguyên nhân và giải pháp',
                'slug' => 'xu-ly-loi-bo-mui-tren-may-vat-so',
                'excerpt' => 'Lỗi bỏ mũi thường đến từ kim/chỉ, timing móc và đường dẫn chỉ. Bài viết tổng hợp cách kiểm tra nhanh.',
                'content' => '<p>Hãy kiểm tra kim trước (đúng hệ kim, không cong), sau đó kiểm tra đường dẫn chỉ và timing. Với từng dòng máy sẽ có thông số khuyến nghị khác nhau.</p>',
                'thumbnail' => 'assets/frontend/images/anh6.jpg',
                'category_id' => $category?->id,
                'author_id' => $admin?->id,
                'status' => 'published',
                'type' => 'news',
                'published_at' => now()->subDay(),
                'event_date' => null,
                'event_location' => null,
                'is_featured' => false,
                'view_count' => 420,
            ],
            [
                'title' => 'Lịch demo máy may công nghiệp tại TP.HCM (tháng này)',
                'slug' => 'lich-demo-may-may-cong-nghiep-tphcm-thang-nay',
                'excerpt' => 'Đăng ký lịch demo theo nhóm máy: 1 kim, vắt sổ, lập trình. Có kỹ thuật hỗ trợ setup tại xưởng.',
                'content' => '<p>Tech Sewing Machine tổ chức demo theo lịch. Bạn có thể để lại thông tin và nhu cầu để được tư vấn phù hợp.</p>',
                'thumbnail' => 'assets/frontend/images/anh8.jpg',
                'category_id' => $category?->id,
                'author_id' => $admin?->id,
                'status' => 'published',
                'type' => 'event',
                'published_at' => now()->subHours(10),
                'event_date' => now()->addDays(7)->format('Y-m-d'),
                'event_location' => 'TP. Hồ Chí Minh',
                'is_featured' => false,
                'view_count' => 120,
            ],
        ];

        foreach ($posts as $data) {
            Post::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}

