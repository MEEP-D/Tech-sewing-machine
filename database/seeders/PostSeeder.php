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
        $admin = User::query()->where('is_admin', true)->first() ?? User::query()->first();
        $category = Category::query()
            ->where('type', 'news')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        if (! $admin || ! $category) {
            return;
        }

        $items = [
            ['Cách bảo trì máy may 1 kim Juki đúng cách tại xưởng', 'cach-bao-tri-may-may-1-kim-juki', 'Checklist vệ sinh, tra dầu, cân chỉnh cơ bản để máy may 1 kim vận hành ổn định và giảm lỗi bỏ mũi.', 'assets/frontend/images/anh7.jpg', 'news', 2, true, 560],
            ['Xử lý lỗi bỏ mũi trên máy vắt sổ: nguyên nhân và giải pháp', 'xu-ly-loi-bo-mui-tren-may-vat-so', 'Lỗi bỏ mũi thường đến từ kim/chỉ, timing móc và đường dẫn chỉ. Bài viết tổng hợp cách kiểm tra nhanh.', 'assets/frontend/images/anh6.jpg', 'news', 1, false, 420],
            ['Lịch demo máy may công nghiệp tại TP.HCM (tháng này)', 'lich-demo-may-may-cong-nghiep-tphcm-thang-nay', 'Đăng ký lịch demo theo nhóm máy: 1 kim, vắt sổ, lập trình. Có kỹ thuật hỗ trợ setup tại xưởng.', 'assets/frontend/images/anh8.jpg', 'event', 0, false, 220],
            ['5 lỗi vận hành phổ biến khi chạy chuyền may đầu ca', '5-loi-van-hanh-pho-bien-khi-chay-chuyen-may-dau-ca', 'Tổng hợp lỗi thao tác đầu ca và cách kiểm tra trước khi vào đơn hàng để tránh dừng chuyền đột ngột.', 'assets/frontend/images/anh1.jpg', 'news', 3, false, 310],
            ['Kinh nghiệm chọn kim may phù hợp theo chất liệu vải', 'kinh-nghiem-chon-kim-may-phu-hop-theo-chat-lieu-vai', 'Chọn sai cỡ kim dễ gây đứt chỉ, bỏ mũi và nhăn đường may. Đây là bảng gợi ý theo từng nhóm vải.', 'assets/frontend/images/anh2.jpg', 'news', 4, false, 295],
            ['Tối ưu tốc độ máy lập trình khi may chi tiết cong', 'toi-uu-toc-do-may-lap-trinh-khi-may-chi-tiet-cong', 'Thiết lập tốc độ và lực ép phù hợp giúp đường may cong mượt hơn, giảm rung và giảm hỏng hàng.', 'assets/frontend/images/anh3.jpg', 'news', 5, false, 260],
            ['Bảo dưỡng cụm cắt chỉ tự động: quy trình 15 phút', 'bao-duong-cum-cat-chi-tu-dong-quy-trinh-15-phut', 'Quy trình nhanh cho kỹ thuật viên hiện trường để cụm cắt chỉ hoạt động ổn định trong ca dài.', 'assets/frontend/images/anh4.jpg', 'news', 6, false, 250],
            ['So sánh máy 1 kim cơ và điện tử cho xưởng vừa', 'so-sanh-may-1-kim-co-va-dien-tu-cho-xuong-vua', 'Phân tích chi phí đầu tư, năng suất và độ ổn định để chọn cấu hình máy phù hợp quy mô xưởng.', 'assets/frontend/images/anh5.jpg', 'news', 7, false, 380],
            ['Checklist bàn giao máy mới cho tổ trưởng chuyền', 'checklist-ban-giao-may-moi-cho-to-truong-chuyen', 'Danh sách kiểm tra trước khi nghiệm thu: an toàn điện, thông số mũi, phụ tùng và hướng dẫn vận hành.', 'assets/frontend/images/anh6.jpg', 'news', 8, false, 215],
            ['Thiết lập chỉ dưới để hạn chế xù đường may áo thun', 'thiet-lap-chi-duoi-de-han-che-xu-duong-may-ao-thun', 'Điều chỉnh căng chỉ và tốc độ kéo vải đúng chuẩn giúp đường may áo thun phẳng và bền hơn.', 'assets/frontend/images/anh7.jpg', 'news', 9, false, 270],
            ['Lịch hội thảo tối ưu năng suất chuyền may quý này', 'lich-hoi-thao-toi-uu-nang-suat-chuyen-may-quy-nay', 'Cập nhật lịch hội thảo kỹ thuật về cân bằng chuyền, chuẩn thao tác và tối ưu chất lượng đầu ra.', 'assets/frontend/images/anh8.jpg', 'seminar', 10, true, 460],
            ['Quy trình xử lý nhanh khi máy báo lỗi E7/E9', 'quy-trinh-xu-ly-nhanh-khi-may-bao-loi-e7-e9', 'Hướng dẫn thao tác tại chuyền để khoanh vùng lỗi trong 3 bước và quyết định khi nào cần gọi kỹ thuật.', 'assets/frontend/images/anh2.jpg', 'news', 11, false, 340],
            ['Tư vấn cấu hình máy cho đơn hàng jacket xuất khẩu', 'tu-van-cau-hinh-may-cho-don-hang-jacket-xuat-khau', 'Gợi ý bộ máy và phụ trợ giúp giảm thời gian đổi mã, phù hợp nhà máy có nhiều kiểu cổ tay/bo gấu.', 'assets/frontend/images/anh3.jpg', 'news', 12, false, 365],
            ['Khi nào nên nâng cấp servo cho máy may cơ?', 'khi-nao-nen-nang-cap-servo-cho-may-may-co', 'Nâng cấp motor servo giúp tiết kiệm điện và giảm ồn, nhưng cần lưu ý tải máy và tần suất vận hành.', 'assets/frontend/images/anh4.jpg', 'news', 13, false, 305],
        ];

        foreach ($items as [$title, $slug, $excerpt, $thumbnail, $type, $daysAgo, $featured, $views]) {
            Post::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => $excerpt,
                    'content' => '<p>' . $excerpt . ' Nội dung mẫu được tạo để kiểm thử giao diện và trải nghiệm đọc tin trên website.</p>',
                    'thumbnail' => $thumbnail,
                    'category_id' => $category->id,
                    'author_id' => $admin->id,
                    'status' => 'published',
                    'type' => $type,
                    'published_at' => now()->subDays($daysAgo),
                    'event_date' => in_array($type, ['event', 'seminar', 'fair'], true) ? now()->addDays(7 + $daysAgo)->toDateString() : null,
                    'event_location' => in_array($type, ['event', 'seminar', 'fair'], true) ? 'TP. Hồ Chí Minh' : null,
                    'is_featured' => $featured,
                    'view_count' => $views,
                ]
            );
        }
    }
}
