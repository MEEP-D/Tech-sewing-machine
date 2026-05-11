@php
    $headerCategories = \App\Models\Category::query()
        ->where('type', 'product')
        ->whereNull('parent_id')
        ->withCount('children')
        ->orderBy('sort_order')
        ->take(8)
        ->get();

    $supportPhone = data_get($siteSettings, 'contact_phone', '0903 000 000');
    $supportEmail = data_get($siteSettings, 'contact_email', 'info@techsewing.vn');
    $headerMenus = app(\App\Services\MenuService::class)->tree((array) data_get($siteMenus, 'header', []));
    $logoType = data_get($siteSettings, 'site_logo_type', 'image');
    $logoHeight = (int) data_get($siteSettings, 'site_logo_height', 44);
    $logoWidth = (int) data_get($siteSettings, 'site_logo_width', 180);
    $logoLight = data_get($siteSettings, 'site_logo');
    $logoDark = data_get($siteSettings, 'site_logo_dark');
    $logoMobile = data_get($siteSettings, 'site_logo_mobile');
@endphp

<header class="v-header-wrapper" id="main-header">
    <div class="v-topbar">
        <div class="container v-topbar-inner">
            <span class="v-topbar-meta">{{ data_get($siteSettings, 'site_description', 'Giải pháp máy may công nghiệp') }}</span>
            <div class="v-topbar-links">
                <a href="tel:{{ preg_replace('/\s+/', '', $supportPhone) }}"><i class="fas fa-phone"></i><span>{{ $supportPhone }}</span></a>
                <a href="mailto:{{ $supportEmail }}"><i class="fas fa-envelope"></i><span>{{ $supportEmail }}</span></a>
                <a href="{{ route('contact') }}">Đặt lịch tư vấn</a>
            </div>
        </div>
    </div>

    <div class="v-mainnav" id="main-nav">
        <div class="container v-mainnav-inner">
            <button class="v-mobile-trigger" id="mobile-toggle" type="button" aria-label="Mở menu"><i class="fas fa-bars"></i></button>
            <a href="{{ route('home') }}" class="v-logo" aria-label="TechSewing" style="display:flex;align-items:center;gap:10px;">
                @if($logoType === 'text')
                    <span class="v-logo-text">{{ data_get($siteSettings, 'site_title', 'TechSewing') }}</span>
                @else
                    <template x-if="!darkMode">
                        <img src="{{ asset($logoLight ?: 'assets/frontend/images/logo.png') }}" alt="{{ data_get($siteSettings, 'site_title', 'TechSewing') }}" style="height: {{ $logoHeight }}px; max-width: {{ $logoWidth }}px; width: auto; object-fit: contain;">
                    </template>
                    <template x-if="darkMode">
                        <img src="{{ asset($logoDark ?: $logoLight ?: 'assets/frontend/images/logo.png') }}" alt="{{ data_get($siteSettings, 'site_title', 'TechSewing') }}" style="height: {{ $logoHeight }}px; max-width: {{ $logoWidth }}px; width: auto; object-fit: contain;">
                    </template>
                @endif
            </a>

            <nav class="v-nav" aria-label="Điều hướng chính">
                @forelse($headerMenus as $item)
                    <div class="v-nav-item {{ !empty($item['children']) ? 'has-dropdown' : '' }}">
                        <a href="{{ $item['url'] ?: ($item['route_name'] ? route($item['route_name']) : '#') }}" class="v-nav-link {{ $item['css_class'] ?? '' }}" target="{{ $item['target'] ?? '_self' }}">{{ $item['label'] }}</a>
                        @if(!empty($item['children']))
                            <div class="v-nav-dropdown">
                                <div class="v-nav-dropdown-grid">
                                    @foreach($item['children'] as $child)
                                        <a href="{{ $child['url'] ?: ($child['route_name'] ? route($child['route_name']) : '#') }}" class="v-nav-card {{ $child['css_class'] ?? '' }}" target="{{ $child['target'] ?? '_self' }}">
                                            <span class="v-nav-card-kicker">Menu</span>
                                            <strong>{{ $child['label'] }}</strong>
                                            <span>{{ $child['icon'] ?? 'Khám phá nội dung được quản lý từ admin.' }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="v-nav-item has-dropdown">
                        <a href="{{ route('products.index') }}" class="v-nav-link">Sản phẩm</a>
                        <div class="v-nav-dropdown">
                            <div class="v-nav-dropdown-grid">
                                @foreach($headerCategories as $category)
                                    <a href="{{ route('products.category', data_get($category, 'slug')) }}" class="v-nav-card">
                                        <span class="v-nav-card-kicker">{{ data_get($category, 'children_count') ? 'Nhóm chính' : 'Dòng máy' }}</span>
                                        <strong>{{ data_get($category, 'name', 'Danh mục') }}</strong>
                                        <span>{{ data_get($category, 'description') ?: 'Khám phá danh mục và các mẫu nổi bật.' }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('news.index') }}" class="v-nav-link">Tin tức</a>
                    <a href="{{ route('about') }}" class="v-nav-link">Về chúng tôi</a>
                    <a href="{{ route('contact') }}" class="v-nav-link">Liên hệ</a>
                @endforelse
            </nav>

            <div class="v-nav-actions">
                <form action="{{ route('products.search') }}" method="GET" class="v-search-form"><i class="fas fa-search"></i><input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm máy may, SKU, dòng sản phẩm"></form>
                
                <button type="button" @click="toggleTheme()" class="v-theme-toggle" aria-label="Đổi chủ đề">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>

                <a href="{{ route('contact') }}" class="v-btn v-btn-primary">Nhận báo giá</a>
            </div>
        </div>
    </div>
</header>

<div class="v-mobile-overlay" id="mobile-overlay"></div>
<aside class="v-mobile-panel" id="mobile-panel" aria-hidden="true">
    <div class="v-mobile-panel-head"><strong>Điều hướng</strong><button class="v-mobile-close" id="mobile-close" type="button" aria-label="Đóng menu"><i class="fas fa-times"></i></button></div>
    <div class="v-mobile-logo" style="padding: 12px 0;">
        @if($logoMobile)
            <img src="{{ asset($logoMobile) }}" alt="{{ data_get($siteSettings, 'site_title', 'TechSewing') }}" style="height: {{ max(28, $logoHeight - 6) }}px; width: auto; object-fit: contain;">
        @elseif($logoType === 'text')
            <strong>{{ data_get($siteSettings, 'site_title', 'TechSewing') }}</strong>
        @else
            <img src="{{ asset($logoLight ?: 'assets/frontend/images/logo.png') }}" alt="{{ data_get($siteSettings, 'site_title', 'TechSewing') }}" style="height: {{ max(28, $logoHeight - 6) }}px; width: auto; object-fit: contain;">
        @endif
    </div>
    <form action="{{ route('products.search') }}" method="GET" class="v-mobile-search"><i class="fas fa-search"></i><input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm sản phẩm"></form>
    <nav class="v-mobile-nav">
        <a href="{{ route('home') }}">Trang chủ</a>
        
        @foreach($headerMenus as $item)
            @if(!empty($item['children']))
                <div x-data="{ open: false }" style="border-bottom: 1px solid rgba(15, 23, 42, 0.06);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <a href="{{ $item['url'] ?: ($item['route_name'] ? route($item['route_name']) : '#') }}" style="border-bottom: none; flex-grow: 1;">{{ $item['label'] }}</a>
                        <button @click="open = !open" style="background: none; border: none; padding: 13px; cursor: pointer; color: var(--color-primary);" aria-label="Mở menu con">
                            <i class="fas fa-chevron-down" :style="open ? 'transform: rotate(180deg); transition: all 0.2s;' : 'transition: all 0.2s;'"></i>
                        </button>
                    </div>
                    <div x-show="open" x-collapse x-cloak>
                        <div style="display: flex; flex-direction: column; padding-bottom: 10px; padding-left: 16px; border-left: 2px solid #e2e8f0; margin-left: 8px; margin-bottom: 8px;">
                            @foreach($item['children'] as $child)
                                <a href="{{ $child['url'] ?: ($child['route_name'] ? route($child['route_name']) : '#') }}" style="border-bottom: none; padding: 8px 0; opacity: 0.85; font-size: 0.95em; font-weight: 400;">{{ $child['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ $item['url'] ?: ($item['route_name'] ? route($item['route_name']) : '#') }}">{{ $item['label'] }}</a>
            @endif
        @endforeach

        @if(empty($headerMenus))
            <div x-data="{ open: false }" style="border-bottom: 1px solid rgba(15, 23, 42, 0.06);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('products.index') }}" style="border-bottom: none; flex-grow: 1;">Sản phẩm</a>
                    <button @click="open = !open" style="background: none; border: none; padding: 13px; cursor: pointer; color: var(--color-primary);" aria-label="Mở danh mục">
                        <i class="fas fa-chevron-down" :style="open ? 'transform: rotate(180deg); transition: all 0.2s;' : 'transition: all 0.2s;'"></i>
                    </button>
                </div>
                <div x-show="open" x-collapse x-cloak>
                    <div style="display: flex; flex-direction: column; padding-bottom: 10px; padding-left: 16px; border-left: 2px solid #e2e8f0; margin-left: 8px; margin-bottom: 8px;">
                        @foreach($headerCategories as $category)
                            <a href="{{ route('products.category', data_get($category, 'slug')) }}" style="border-bottom: none; padding: 8px 0; opacity: 0.85; font-size: 0.95em; font-weight: 400;">{{ data_get($category, 'name', 'Danh mục') }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <a href="{{ route('news.index') }}">Tin tức</a>
            <a href="{{ route('about') }}">Về chúng tôi</a>
            <a href="{{ route('contact') }}">Liên hệ</a>
        @endif
    </nav>
    <div class="v-mobile-support"><a href="tel:{{ preg_replace('/\s+/', '', $supportPhone) }}">{{ $supportPhone }}</a><a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></div>
</aside>
