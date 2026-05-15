<footer class="main-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>{{ ($siteContent['footer_about_title'] ?? '') ?: 'Ve Chung Toi - TechSewing' }}</h4>
                <p style="opacity: 0.8; font-size: 0.95rem;">{{ $siteContent['footer_about_text'] ?? '' }}</p>
            </div>
            <div class="footer-col">
                <h4>San Pham Chinh</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('products.index') }}">Tat ca san pham</a></li>
                    @foreach(($menuCategories ?? collect())->take(2) as $top)
                        <li><a href="{{ route('products.category', $top->slug) }}">{{ $top->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
                <h4>Trang Cong Khai</h4>
                <ul class="footer-links">
                    @forelse(collect($publicPages ?? []) as $publicPage)
                        <li><a href="{{ route('pages.show', ['slug' => data_get($publicPage, 'slug')]) }}">{{ data_get($publicPage, 'title') }}</a></li>
                    @empty
                        <li><a href="{{ route('contact') }}">Tu van lap dat</a></li>
                    @endforelse
                </ul>
            </div>
            <div class="footer-col">
                <h4>Lien He</h4>
                <ul class="footer-links" style="opacity: 0.9;">
                    <li><i class="fas fa-map-marker-alt"></i> {{ $siteProfile['address'] ?? '' }}</li>
                    <li><i class="fas fa-phone"></i> {{ $siteProfile['hotline'] ?? '' }}</li>
                    <li><i class="fas fa-envelope"></i> {{ $siteProfile['email'] ?? '' }}</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} May May Thong Minh. All rights reserved.</p>
        </div>
    </div>
</footer>
