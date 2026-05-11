@php
    $footerCats = \App\Models\Category::query()
        ->where('type', 'product')
        ->whereNull('parent_id')
        ->orderBy('sort_order')
        ->take(5)
        ->get();

    $footerNewsCats = \App\Models\Category::query()
        ->where('type', 'news')
        ->orderBy('sort_order')
        ->take(4)
        ->get();

    $footerMenus = app(\App\Services\MenuService::class)->tree((array) data_get(\Illuminate\Support\Facades\Cache::get('site_menus_v2', []), 'footer', []));
@endphp

<footer class="v-footer section-shell">
    <div class="container">
        <div class="v-footer-grid">
            <div class="footer-col footer-brand">
                <h4>TechSewing</h4>
                <p>Giải pháp máy may công nghiệp cao cấp, định hướng thương hiệu premium, đảm bảo hiệu suất và tối ưu hóa quy trình sản xuất.</p>
                <div class="v-footer-stats">
                    <div><strong>24/7</strong><span>Hỗ trợ kỹ thuật</span></div>
                    <div><strong>63</strong><span>Tỉnh thành</span></div>
                    <div><strong>100+</strong><span>Mô hình xưởng</span></div>
                </div>
            </div>

            <div class="footer-col">
                <h4>Danh mục sản phẩm</h4>
                <ul class="footer-links">@foreach($footerCats as $cat)<li><a href="{{ route('products.category', $cat->slug) }}">{{ $cat->name }}</a></li>@endforeach</ul>
            </div>

            <div class="footer-col">
                <h4>Tin tức & Sự kiện</h4>
                <ul class="footer-links"><li><a href="{{ route('news.index') }}">Tất cả tin tức</a></li>@foreach($footerNewsCats as $cat)<li><a href="{{ route('news.category', $cat->slug) }}">{{ $cat->name }}</a></li>@endforeach</ul>
            </div>

            <div class="footer-col">
                <h4>Liên kết nhanh</h4>
                <ul class="footer-links">
                    @forelse($footerMenus as $item)
                        <li><a href="{{ $item['url'] ?: ($item['route_name'] ? route($item['route_name']) : '#') }}">{{ $item['label'] }}</a></li>
                    @empty
                        <li><a href="{{ route('about') }}">Về chúng tôi</a></li>
                        <li><a href="{{ route('contact') }}">Gửi yêu cầu báo giá</a></li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'TechSewing') }}. Bảo lưu mọi quyền.</p>
            <div class="footer-bottom-links"><a href="{{ route('products.index') }}">Sản phẩm</a><a href="{{ route('news.index') }}">Tin tức</a><a href="{{ route('contact') }}">Liên hệ</a></div>
        </div>
    </div>
</footer>

<script src="{{ asset('assets/frontend/js/modern.js') }}"></script>
