<x-filament-panels::page>
    @php
        $guideSections = [
            [
                'title' => '1) Cấu hình website',
                'items' => [
                    ['label' => 'Tiêu đề website', 'text' => 'Tên mặc định cho website/SEO. Nên đặt theo thương hiệu và lĩnh vực chính.'],
                    ['label' => 'Mô tả website', 'text' => 'Mô tả tổng quan, nên tối ưu khoảng 120-160 ký tự.'],
                    ['label' => 'Loại logo', 'text' => 'Chọn image nếu dùng file logo, chọn text nếu hiển thị tên chữ.'],
                    ['label' => 'Logo sáng', 'text' => 'Logo chính ở header. Khuyến nghị PNG nền trong, kích thước 360-600 x 80-140, dưới 300KB.'],
                    ['label' => 'Favicon', 'text' => 'Biểu tượng tab trình duyệt. Khuyến nghị 48x48 hoặc 64x64, định dạng ICO/PNG.'],
                    ['label' => 'Ảnh hero', 'text' => 'Banner lớn đầu trang. Khuyến nghị 1920x900 hoặc 1920x1080, JPG/WebP, dưới 500KB.'],
                ],
            ],
            [
                'title' => '2) SEO Settings',
                'items' => [
                    ['label' => 'SEO title mặc định', 'text' => 'Dùng khi bài/trang chưa có SEO riêng, nên dài 50-60 ký tự.'],
                    ['label' => 'SEO description mặc định', 'text' => 'Nên dài 120-160 ký tự, chứa từ khóa chính.'],
                    ['label' => 'Canonical', 'text' => 'URL chuẩn của website, ví dụ: https://domain.com.'],
                    ['label' => 'OG image', 'text' => 'Ảnh chia sẻ Facebook/Zalo, khuyến nghị 1200x630, dưới 500KB.'],
                    ['label' => 'Robots', 'text' => 'Với website production, thường để index,follow.'],
                ],
            ],
            [
                'title' => '3) Sliders (trang chủ)',
                'items' => [
                    ['label' => 'Title/Subtitle', 'text' => 'Nội dung chính hiển thị trên banner.'],
                    ['label' => 'Image', 'text' => 'Ảnh nền cho mỗi slide. Nên giữ cùng tỉ lệ cho tất cả slide.'],
                    ['label' => 'Link', 'text' => 'URL khi người dùng bấm CTA.'],
                    ['label' => 'is_active', 'text' => 'Bật/tắt hiển thị slide.'],
                    ['label' => 'sort_order', 'text' => 'Số nhỏ hiển thị trước.'],
                ],
            ],
            [
                'title' => '4) Categories / Menus',
                'items' => [
                    ['label' => 'Categories', 'text' => 'Tạo danh mục cha trước, danh mục con sau. Kiểm tra trạng thái active trước khi xuất bản.'],
                    ['label' => 'Type', 'text' => 'Danh mục sản phẩm cần đúng type để hiển thị đúng ở trang sản phẩm/menu.'],
                    ['label' => 'Menus', 'text' => 'Điều hướng đầu trang, đảm bảo URL đúng và thứ tự hợp lý.'],
                ],
            ],
            [
                'title' => '5) Products',
                'items' => [
                    ['label' => 'Tên, slug, mã SKU/code', 'text' => 'Thông tin nhận diện sản phẩm cần nhất quán.'],
                    ['label' => 'Giá', 'text' => 'Có thể là chuỗi như Liên hệ hoặc giá trị số theo quy trình của bạn.'],
                    ['label' => 'Ảnh đại diện', 'text' => 'Khuyến nghị 1200x1200 và đồng nhất tỉ lệ giữa các sản phẩm.'],
                    ['label' => 'Mô tả ngắn/dài', 'text' => 'Mô tả ngắn cho danh sách, mô tả dài cho trang chi tiết.'],
                    ['label' => 'Trạng thái', 'text' => 'Chỉ sản phẩm active/published mới hiển thị ngoài frontend.'],
                ],
            ],
            [
                'title' => '6) Bài viết / Trang',
                'items' => [
                    ['label' => 'Posts', 'text' => 'Bài viết tin tức/blog. Nên khai báo SEO riêng cho bài quan trọng.'],
                    ['label' => 'Pages', 'text' => 'Trang tĩnh như giới thiệu, chính sách, điều khoản.'],
                    ['label' => 'Ảnh đại diện bài viết', 'text' => 'Khuyến nghị 1200x630 để tối ưu chia sẻ mạng xã hội.'],
                ],
            ],
        ];

        $checklist = [
            'Mở trang chủ và kiểm tra logo, slider, menu.',
            'Kiểm tra favicon trên tab trình duyệt.',
            'Mở một trang sản phẩm và một bài viết để test SEO title/description.',
            'Kiểm tra ảnh có bị vỡ, méo, dung lượng quá lớn hoặc tải chậm hay không.',
            'Nếu sửa xong nhưng chưa thấy thay đổi, hãy clear cache ứng dụng và hard refresh trình duyệt.',
        ];
    @endphp

    <style>
        .admin-guide-accordion {
            display: grid;
            gap: 12px;
        }

        .admin-guide-item {
            border: 1px solid rgba(148, 163, 184, 0.35);
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.35);
            overflow: hidden;
        }

        .admin-guide-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            cursor: pointer;
            font-weight: 700;
            list-style: none;
        }

        .admin-guide-summary::-webkit-details-marker {
            display: none;
        }

        .admin-guide-summary::marker {
            content: "";
        }

        .admin-guide-item[open] .admin-guide-summary {
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
            background: rgba(30, 41, 59, 0.45);
        }

        .admin-guide-chevron {
            color: rgb(148 163 184);
            transition: transform 0.2s ease;
            font-size: 12px;
            line-height: 1;
            flex-shrink: 0;
        }

        .admin-guide-item[open] .admin-guide-chevron {
            transform: rotate(180deg);
        }

        .admin-guide-body {
            padding: 14px 16px;
        }

        .admin-guide-body p {
            margin: 0 0 8px;
        }

        .admin-guide-body p:last-child {
            margin-bottom: 0;
        }

        .admin-guide-checklist {
            margin: 0;
            padding-left: 20px;
        }

        .admin-guide-checklist li + li {
            margin-top: 6px;
        }
    </style>

    <div class="space-y-6 text-sm leading-6">
        <x-filament::section heading="Mục tiêu">
            <p>Trang này hướng dẫn nhanh các chức năng trong admin: dùng để làm gì, ảnh hưởng ở đâu trên frontend, giá trị nên nhập và các lưu ý vận hành.</p>
        </x-filament::section>

        <x-filament::section heading="Hướng dẫn theo chức năng">
            <div class="admin-guide-accordion">
                @foreach ($guideSections as $index => $section)
                    <details class="admin-guide-item" @if($index === 0) open @endif>
                        <summary class="admin-guide-summary">
                            <span>{{ $section['title'] }}</span>
                            <span class="admin-guide-chevron" aria-hidden="true">▼</span>
                        </summary>
                        <div class="admin-guide-body">
                            @foreach ($section['items'] as $row)
                                <p><strong>{{ $row['label'] }}:</strong> {{ $row['text'] }}</p>
                            @endforeach
                        </div>
                    </details>
                @endforeach

                <details class="admin-guide-item">
                    <summary class="admin-guide-summary">
                        <span>7) Checklist sau khi lưu</span>
                        <span class="admin-guide-chevron" aria-hidden="true">▼</span>
                    </summary>
                    <div class="admin-guide-body">
                        <ol class="admin-guide-checklist">
                            @foreach ($checklist as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ol>
                    </div>
                </details>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
