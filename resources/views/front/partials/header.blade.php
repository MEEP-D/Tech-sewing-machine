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
            $menuService = app(\App\Services\MenuService::class);
            $headerMenuSource = (array) data_get($siteMenus ?? [], 'header', []);

            if (empty($headerMenuSource) && is_array($siteMenus ?? null)) {
                $firstLocation = collect($siteMenus)->first();
                $headerMenuSource = is_array($firstLocation) ? $firstLocation : [];
            }

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
                                <div class="mega-menu">
                                    @if($useCategoryMegaMenu)
                                        @foreach($productMegaCategories as $top)
                                            @php
                                                $topName = $toText(data_get($top, 'name'), 'Danh mục');
                                                $topSlug = $toText(data_get($top, 'slug'), '');
                                                $topChildren = collect(data_get($top, 'children', []));
                                            @endphp
                                            <div class="mega-col">
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
                        <div class="mega-menu">
                            @foreach($productMegaCategories as $top)
                                @php
                                    $topName = $toText(data_get($top, 'name'), 'Danh mục');
                                    $topSlug = $toText(data_get($top, 'slug'), '');
                                    $topChildren = collect(data_get($top, 'children', []));
                                @endphp
                                <div class="mega-col">
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
        </div>

        <button class="desktop-more-toggle" id="desktop-more-toggle" type="button" aria-expanded="false" aria-label="Mở menu">
            <i class="fas fa-bars"></i>
        </button>

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
