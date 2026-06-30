@php
    $menuService = app(\App\Services\MenuService::class);
    $footerMenuSource = (array) data_get($siteMenus ?? [], 'footer', []);
    $footerMenus = $menuService->tree($footerMenuSource);
    $publicPagesCollection = collect($publicPages ?? []);

    $resolveFooterMenuUrl = static function (array $item): string {
        $url = data_get($item, 'url');
        if (is_string($url) && trim($url) !== '') {
            return $url;
        }

        $routeName = data_get($item, 'route_name');
        if (is_string($routeName) && trim($routeName) !== '' && \Illuminate\Support\Facades\Route::has($routeName)) {
            return route($routeName);
        }

        return 'javascript:void(0)';
    };

    $footerLinkHeading = 'Trang công khai';
    $footerLinkItems = [];

    if (! empty($footerMenus)) {
        $footerLinkHeading = 'Menu chân trang';

        foreach ($footerMenus as $footerMenu) {
            $footerLinkItems[] = [
                'label' => data_get($footerMenu, 'label'),
                'url' => $resolveFooterMenuUrl($footerMenu),
            ];

            foreach (collect(data_get($footerMenu, 'children', [])) as $footerMenuChild) {
                $footerLinkItems[] = [
                    'label' => data_get($footerMenuChild, 'label'),
                    'url' => $resolveFooterMenuUrl($footerMenuChild),
                ];
            }
        }
    } elseif ($publicPagesCollection->isNotEmpty()) {
        foreach ($publicPagesCollection as $publicPage) {
            $footerLinkItems[] = [
                'label' => data_get($publicPage, 'title'),
                'url' => route('pages.show', ['slug' => data_get($publicPage, 'slug')]),
            ];
        }
    } else {
        $footerLinkItems[] = [
            'label' => 'Tư vấn lắp đặt',
            'url' => route('contact'),
        ];
    }
@endphp

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
                <h4>{{ $footerLinkHeading }}</h4>
                <ul class="footer-links">
                    @foreach($footerLinkItems as $footerLinkItem)
                        <li><a href="{{ data_get($footerLinkItem, 'url') }}">{{ data_get($footerLinkItem, 'label') }}</a></li>
                    @endforeach
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
