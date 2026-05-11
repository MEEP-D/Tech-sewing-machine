# Admin Validation Spec

## Scope
This spec defines validation rules for all important admin inputs, with focus on Filament resources and settings pages.

## Global rules
- Required fields must be declared with `required()` and validated at save time.
- String fields must have sensible `maxLength()` limits.
- Numeric fields must use `numeric()` and have bounds if business-critical.
- URL fields must use `url()`.
- Upload fields must accept only image/file types allowed by the business.
- Slug fields must be unique and auto-generated where appropriate.
- Relationships must be searchable/preloaded where needed.
- No deprecated Filament namespace usage.
- All uploads must persist a string path in DB, never arrays.

## Resource rules

### Banner
- `key` required, unique identifier for the banner slot
- `title`, `subtitle` optional text
- `image` accepts image upload or path
- `link` must be URL if present
- `button_text` max 50
- `size_label`, `recommended_size` max 100
- `sort_order` numeric, default 0
- `is_active` boolean, default true

### Site Settings
- `site_title` required
- `site_description` max 255
- `site_logo_type` must be `image` or `text`
- `site_logo_height` / `site_logo_width` numeric positive values
- upload fields must be image-only
- SEO fields must have bounds:
  - title <= 70
  - description <= 160
  - OG title <= 95
  - OG description <= 200

### Menu
- `location` required, limited to header/footer
- `label` required
- `url` optional but must be URL when filled
- `route_name` optional and must correspond to a real route when used
- `target` limited to `_self` / `_blank`
- `sort_order` numeric
- `is_active` boolean

### Page
- `title` required
- `slug` required and unique
- `excerpt` max 500
- `image` valid image path/upload
- `layout_mode` limited to `content` / `builder`
- `cache_ttl` numeric and positive

### Post
- `title` required, max 255
- `slug` unique, max 255
- `type` limited to allowed types
- `status` limited to allowed statuses
- `author_id` required
- `excerpt` max 500
- `content` required when publishing
- `thumbnail` image upload, 16:9 recommended
- SEO fields validated by length and URL

### Product
- `name` required, max 255
- `slug` unique
- `sku` unique when present
- `price` numeric when present
- `thumbnail` image upload
- `gallery` image array only
- `specifications` array only

### Category
- `name` required
- `slug` unique
- `type` limited to product/news
- `parent_id` optional and must point to valid category

### Tag
- `name` required
- `slug` unique
- `type` limited to product/news

## Acceptance criteria
- Invalid inputs are rejected before save.
- Valid inputs persist successfully.
- Upload fields save path strings and reopen correctly.
- SEO fields never exceed recommended lengths.
- All resources use the current Filament API without namespace conflicts.
