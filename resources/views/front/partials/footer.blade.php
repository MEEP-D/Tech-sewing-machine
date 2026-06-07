<footer class="main-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>{{ ($siteContent['footer_about_title'] ?? '') ?: 'Về chúng tôi - TechSewing' }}</h4>
                <p style="opacity: 0.8; font-size: 0.95rem;">{{ $siteContent['footer_about_text'] ?? '' }}</p>
            </div>
            <div class="footer-col">
                <h4>Sản phẩm chính</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('products.index') }}">Tất cả sản phẩm</a></li>
                    @foreach(($menuCategories ?? collect())->take(2) as $top)
                        @php($topSlug = data_get($top, 'slug'))
                        @if(filled($topSlug))
                            <li><a href="{{ route('products.category', $topSlug) }}">{{ data_get($top, 'name') }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
                <h4>Trang công khai</h4>
                <ul class="footer-links">
                    @forelse(collect($publicPages ?? []) as $publicPage)
                        <li><a href="{{ route('pages.show', ['slug' => data_get($publicPage, 'slug')]) }}">{{ data_get($publicPage, 'title') }}</a></li>
                    @empty
                        <li><a href="{{ route('contact') }}">Tư vấn lắp đặt</a></li>
                    @endforelse
                </ul>
            </div>
            <div class="footer-col">
                <h4>Liên hệ</h4>
                <ul class="footer-links" style="opacity: 0.9;">
                    <li><i class="fas fa-map-marker-alt"></i> {{ $siteProfile['address'] ?? '' }}</li>
                    <li><i class="fas fa-phone"></i> {{ $siteProfile['hotline'] ?? '' }}</li>
                    <li><i class="fas fa-envelope"></i> {{ $siteProfile['email'] ?? '' }}</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Máy May Thông Minh. Bản quyền đã được bảo hộ.</p>
        </div>
    </div>
</footer>
