# SEO Admin Spec

## Mục tiêu
Quản trị viên có thể cấu hình SEO của website ngay trong Filament, không cần sửa code.

## Phạm vi
- Cấu hình SEO mặc định toàn site
- Cấu hình branding phục vụ SEO
- Cấu hình Open Graph
- Cấu hình JSON-LD schema
- Cấu hình robots/canonical
- Áp dụng mặc định cho page, post, product, category

## Chức năng

### 1. Global SEO settings
- SEO title mặc định
- SEO description mặc định
- Canonical mặc định
- OG image mặc định
- Robots mặc định
- Bật/tắt schema JSON-LD
- Bật/tắt Open Graph

### 2. Branding settings
- Site title
- Site description
- Logo sáng/tối
- Favicon
- Hero image mặc định

### 3. Per-model SEO
Áp dụng cho:
- Page
- Post
- Product
- Category

Các trường:
- meta_title
- meta_description
- og_title
- og_description
- og_image
- canonical_url
- focus_keyword
- schema_markup
- no_index
- no_follow

### 4. Rendering logic
- Nếu model có SEO riêng thì dùng SEO riêng
- Nếu không có thì fallback về global SEO
- Canonical luôn chuẩn hóa qua `url()` hoặc URL được nhập
- Schema JSON-LD xuất trong Blade, không render client-side

### 5. Validation rules
- Title tối đa 70 ký tự
- Description tối đa 160 ký tự
- OG title tối đa 95 ký tự
- OG image là ảnh hợp lệ hoặc URL hợp lệ
- Canonical phải là URL hợp lệ
- Robots chỉ cho phép index/follow hoặc noindex/nofollow

## Performance
- Cache SEO payload theo page/model
- Cache schema markup nếu không đổi
- Clear cache khi admin cập nhật SEO

## Acceptance criteria
- Admin sửa SEO không cần code
- Frontend sinh meta tag đúng
- Page render HTML server-side
- Không phá layout hoặc SEO hiện tại
