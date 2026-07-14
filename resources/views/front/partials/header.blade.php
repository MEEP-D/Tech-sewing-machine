<header class="header-main">
    <div class="container header-container">
        @php
            $media = app(\App\Support\OptimizedMedia::class);
            $toText = static function (mixed $value, string $default = ''): string {
                if (is_string($value) || is_numeric($value)) {
                    $text = trim((string) $value);
                    return $text !== '' ? $text : $default;
                }

                if (is_array($value)) {
                    $flat = collect($value)
                        ->flatten()
                        ->filter(fn ($item) => is_string($item) || is_numeric($item))
                        ->map(fn ($item) => trim((string) $item))
                        ->filter()
                        ->implode(' ');

                    return $flat !== '' ? $flat : $default;
                }

                return $default;
            };

            $logoType = strtolower((string) ($siteSettings['site_logo_type'] ?? 'image'));
            $siteLogo = $siteSettings['site_logo'] ?? null;
            $siteLogoUrl = asset(is_file(public_path('assets/frontend/images/anh1.webp')) ? 'assets/frontend/images/anh1.webp' : 'assets/frontend/images/anh1.jpg');
            $siteTitle = trim((string) ($siteSettings['site_title'] ?? config('app.name')));
            $siteTitle = $siteTitle !== '' ? $siteTitle : config('app.name');
            $facebookUrl = trim((string) ($siteSettings['header_facebook_url'] ?? ''));
            $youtubeUrl = trim((string) ($siteSettings['header_youtube_url'] ?? ''));
            $headerQuoteLabel = $toText($siteContent['header_quote_label'] ?? '', '');
            $headerHotline = trim((string) ($siteProfile['hotline'] ?? ''));
            $headerButtonText = $headerQuoteLabel !== '' ? $headerQuoteLabel : $headerHotline;
            $headerHomeLink = $toText($siteContent['header_home_link'] ?? null, 'Trang chủ');
            $headerProductsLink = $toText($siteContent['header_products_link'] ?? null, 'Sản phẩm');
            $headerNewsLink = $toText($siteContent['header_news_link'] ?? null, 'Tin tức');
            $headerContactLink = $toText($siteContent['header_contact_link'] ?? null, 'Liên hệ');
            $onlineVisitors = (int) data_get($siteVisitorStats ?? [], 'online_count', 0);
            $totalVisits = (int) data_get($siteVisitorStats ?? [], 'total_visits', 0);
            $menuService = app(\App\Services\MenuService::class);
            $headerMenuSource = (array) data_get($siteMenus ?? [], 'header', []);

            $headerMenus = $menuService->tree($headerMenuSource);
            $productMegaCategories = collect($menuCategories ?? collect())->take(5);

            $resolveMenuUrl = static function (array $item): string {
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

            if (is_string($siteLogo) && filled($siteLogo)) {
                $siteLogoUrl = $media->url($siteLogo, ['width' => 360, 'quality' => 82]) ?? $siteLogoUrl;
            }
        @endphp
        <a href="{{ route('home') }}" class="logo">
            @if($logoType === 'text')
                <span class="logo-text">{{ $siteTitle }}</span>
            @else
                <img src="{{ $siteLogoUrl }}" alt="{{ $siteTitle }}" decoding="async" fetchpriority="high">
            @endif
        </a>

        <nav class="main-nav-combined">
            <ul class="nav-links">
                <li class="mobile-traffic-entry" aria-label="Thống kê truy cập website">
                    <div class="header-traffic-panel header-traffic-panel-mobile">
                        <div class="header-traffic-row header-traffic-row-online" aria-label="Số người online: {{ number_format($onlineVisitors) }}">
                            <span class="header-online-dot" aria-hidden="true"></span>
                            <span class="header-traffic-label">Số người online</span>
                            <strong class="header-traffic-value">{{ number_format($onlineVisitors) }}</strong>
                        </div>
                        <div class="header-traffic-row" aria-label="Số lượt truy cập: {{ number_format($totalVisits) }}">
                            <i class="fas fa-user header-traffic-icon" aria-hidden="true"></i>
                            <span class="header-traffic-label">Số lượt truy cập</span>
                            <strong class="header-traffic-value">{{ number_format($totalVisits) }}</strong>
                        </div>
                    </div>
                </li>
                @if(!empty($headerMenus))
                    @foreach($headerMenus as $menu)
                        @php
                            $menuLabel = $toText(data_get($menu, 'label'), 'Menu');
                            $menuUrl = $resolveMenuUrl($menu);
                            $menuRouteName = $toText(data_get($menu, 'route_name'), '');
                            $menuTarget = $toText(data_get($menu, 'target'), '_self');
                            $menuChildren = collect(data_get($menu, 'children', []));
                            $useCategoryMegaMenu = $menuRouteName === 'products.index' && $productMegaCategories->isNotEmpty();
                            $hasDropdown = $useCategoryMegaMenu || $menuChildren->isNotEmpty();
                        @endphp
                        <li class="nav-item {{ $hasDropdown ? 'has-children' : '' }}">
                            <a href="{{ $menuUrl }}" class="nav-link" target="{{ $menuTarget }}">{{ $menuLabel }}@if($hasDropdown) <i class="fas fa-chevron-down"></i>@endif</a>
                            @if($hasDropdown)
                                <div class="mega-menu{{ $useCategoryMegaMenu ? ' mega-menu-five-cols' : '' }}">
                                    @if($useCategoryMegaMenu)
                                        @foreach($productMegaCategories as $top)
                                            @php
                                                $topName = $toText(data_get($top, 'name'), 'Danh mục');
                                                $topSlug = $toText(data_get($top, 'slug'), '');
                                                $topChildren = collect(data_get($top, 'children', []));
                                                $topHighlight = (bool) data_get($top, 'highlight_mega_label', false);
                                                $topBlink = (bool) data_get($top, 'highlight_mega_blink', false);
                                                $topClasses = trim(implode(' ', array_filter([
                                                    'mega-col',
                                                    ($topHighlight || $topBlink) ? 'mega-col-highlight' : null,
                                                    $topBlink ? 'mega-col-highlight-blink' : null,
                                                ])));
                                            @endphp
                                            <div class="{{ $topClasses }}">
                                                <h5><a href="{{ route('products.category', $topSlug) }}">{{ $topName }}</a></h5>
                                                <ul class="mega-links">
                                                    @include('front.partials.category-tree', ['categories' => $topChildren, 'level' => 1])
                                                </ul>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach($menuChildren as $child)
                                            @php
                                                $childLabel = $toText(data_get($child, 'label'), 'Danh mục');
                                                $childUrl = $resolveMenuUrl($child);
                                                $grandChildren = collect(data_get($child, 'children', []));
                                            @endphp
                                            <div class="mega-col">
                                                <h5><a href="{{ $childUrl }}">{{ $childLabel }}</a></h5>
                                                @if($grandChildren->isNotEmpty())
                                                    <ul class="mega-links">
                                                        @foreach($grandChildren as $grandChild)
                                                            @php
                                                                $grandLabel = $toText(data_get($grandChild, 'label'), 'Chi tiết');
                                                                $grandUrl = $resolveMenuUrl($grandChild);
                                                                $greatGrandChildren = collect(data_get($grandChild, 'children', []));
                                                            @endphp
                                                            <li class="{{ $greatGrandChildren->isNotEmpty() ? 'has-children' : '' }}">
                                                                <a href="{{ $grandUrl }}">{{ $grandLabel }}</a>
                                                                @if($greatGrandChildren->isNotEmpty())
                                                                    <ul class="sub-links">
                                                                        @foreach($greatGrandChildren as $greatGrandChild)
                                                                            @php
                                                                                $greatGrandLabel = $toText(data_get($greatGrandChild, 'label'), 'Chi tiết');
                                                                                $greatGrandUrl = $resolveMenuUrl($greatGrandChild);
                                                                            @endphp
                                                                            <li><a href="{{ $greatGrandUrl }}">{{ $greatGrandLabel }}</a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">{{ $headerHomeLink }}</a></li>
                    @foreach(collect($publicPages ?? [])->take(4) as $publicPage)
                        @php
                            $pageTitle = $toText(data_get($publicPage, 'title'), 'Trang');
                            $pageSlug = ltrim($toText(data_get($publicPage, 'slug'), ''), '/');
                        @endphp
                        @if($pageSlug !== '')
                            <li class="nav-item"><a href="{{ route('pages.show', ['slug' => $pageSlug]) }}" class="nav-link">{{ $pageTitle }}</a></li>
                        @endif
                    @endforeach
                    <li class="nav-item has-children">
                        <a href="{{ route('products.index') }}" class="nav-link">{{ $headerProductsLink }} <i class="fas fa-chevron-down"></i></a>
                        <div class="mega-menu mega-menu-five-cols">
                            @foreach($productMegaCategories as $top)
                                @php
                                    $topName = $toText(data_get($top, 'name'), 'Danh mục');
                                    $topSlug = $toText(data_get($top, 'slug'), '');
                                    $topChildren = collect(data_get($top, 'children', []));
                                    $topHighlight = (bool) data_get($top, 'highlight_mega_label', false);
                                    $topBlink = (bool) data_get($top, 'highlight_mega_blink', false);
                                    $topClasses = trim(implode(' ', array_filter([
                                        'mega-col',
                                        ($topHighlight || $topBlink) ? 'mega-col-highlight' : null,
                                        $topBlink ? 'mega-col-highlight-blink' : null,
                                    ])));
                                @endphp
                                <div class="{{ $topClasses }}">
                                    <h5><a href="{{ route('products.category', $topSlug) }}">{{ $topName }}</a></h5>
                                    <ul class="mega-links">
                                        @include('front.partials.category-tree', ['categories' => $topChildren, 'level' => 1])
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </li>
                    <li class="nav-item"><a href="{{ route('news.index') }}" class="nav-link">{{ $headerNewsLink }}</a></li>
                    <li class="nav-item"><a href="{{ route('contact') }}" class="nav-link">{{ $headerContactLink }}</a></li>
                @endif
            </ul>
        </nav>

        <div class="header-actions">
            <div class="contact-info">
            <a class="contact-item" href="{{ $facebookUrl !== '' ? $facebookUrl : 'javascript:void(0)' }}" @if($facebookUrl !== '') target="_blank" rel="noopener noreferrer" @endif aria-label="Facebook">
                <i class="fab fa-tiktok" style="color: #000000; font-size: 1.2rem;"></i>
            </a>
            <a class="contact-item" href="{{ $youtubeUrl !== '' ? $youtubeUrl : 'javascript:void(0)' }}" @if($youtubeUrl !== '') target="_blank" rel="noopener noreferrer" @endif aria-label="YouTube">
                <i class="fab fa-youtube" style="color: #ff0000; font-size: 1.2rem;"></i>
            </a>
            <a class="btn btn-primary header-hotline-btn" href="{{ !empty($siteProfile['hotline']) ? 'tel:' . preg_replace('/\D+/', '', $siteProfile['hotline']) : route('contact') }}">
                {{ $headerButtonText }}
            </a>
            <div class="header-traffic-panel" aria-label="Thống kê truy cập website">
                <div class="header-traffic-row header-traffic-row-online" aria-label="Số người online: {{ number_format($onlineVisitors) }}">
                    <span class="header-online-dot" aria-hidden="true"></span>
                    <span class="header-traffic-label">Số người online</span>
                    <strong class="header-traffic-value">{{ number_format($onlineVisitors) }}</strong>
                </div>
                <div class="header-traffic-row" aria-label="Số lượt truy cập: {{ number_format($totalVisits) }}">
                    <i class="fas fa-user header-traffic-icon" aria-hidden="true"></i>
                    <span class="header-traffic-label">Số lượt truy cập</span>
                    <strong class="header-traffic-value">{{ number_format($totalVisits) }}</strong>
                </div>
            </div>
        </div>

        <button class="desktop-more-toggle" id="desktop-more-toggle" type="button" aria-expanded="false" aria-label="Mở menu">
            <i class="fas fa-bars"></i>
        </button>
        </div>

        <div class="mobile-toggle" id="mobile-toggle">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>
<div class="menu-overlay" id="menu-overlay"></div>
<aside class="desktop-more-drawer" id="desktop-more-drawer" aria-hidden="true">
    <div class="desktop-more-head">
        <span>Menu</span>
        <button type="button" id="desktop-more-close" aria-label="Đóng menu">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <ul class="desktop-more-list" id="desktop-more-list"></ul>
</aside>
