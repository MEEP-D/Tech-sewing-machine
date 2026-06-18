<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $faviconPath = $siteSettings['site_favicon'] ?? null;
        $faviconUrl = '/favicon.ico';
        $faviconType = 'image/x-icon';
        $interFontCssUrl = '/fonts/filament/filament/inter/index.css';
        $interVietnameseFontUrl = '/fonts/filament/filament/inter/inter-vietnamese-wght-normal-CE5GGD3W.woff2';
        $modernCssPath = public_path('assets/frontend/css/modern.css');
        $modernCssUrl = '/assets/frontend/css/modern.css?v=' . (is_file($modernCssPath) ? filemtime($modernCssPath) : time());

        if (is_string($faviconPath) && filled($faviconPath)) {
            if (str_starts_with($faviconPath, 'http://') || str_starts_with($faviconPath, 'https://')) {
                $faviconUrl = $faviconPath;
            } elseif (str_starts_with($faviconPath, 'assets/')) {
                $faviconUrl = asset($faviconPath);
            } else {
                $faviconUrl = \Illuminate\Support\Facades\Storage::url($faviconPath);
            }
        }

        $faviconExtension = strtolower((string) pathinfo((string) parse_url($faviconUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
        $faviconType = match ($faviconExtension) {
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            default => 'image/x-icon',
        };
    @endphp
    @include('seo.meta', ['seo' => $seo ?? []])
    <link rel="icon" href="{{ $faviconUrl }}" type="{{ $faviconType }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="{{ $faviconType }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    <link rel="preload" href="{{ $interVietnameseFontUrl }}" as="font" type="font/woff2" crossorigin>
    @stack('preload_assets')
    <link rel="stylesheet" href="{{ $interFontCssUrl }}">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"></noscript>
    <link rel="preload" href="{{ $modernCssUrl }}" as="style">
    <link rel="stylesheet" href="{{ $modernCssUrl }}">
    @stack('page_styles')
</head>
<body>
    @include('front.partials.header')
    <main>@yield('content')</main>
    @include('front.partials.footer')
    @include('front.partials.promo-popup-overlay')
    @include('front.partials.floating-contact')
    @include('front.partials.cookie-consent')
    <script src="/assets/frontend/js/modern.js?v={{ filemtime(public_path('assets/frontend/js/modern.js')) }}" defer></script>
</body>
</html>
