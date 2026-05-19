<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $faviconPath = $siteSettings['site_favicon'] ?? null;
        $faviconUrl = asset('favicon.ico');

        if (is_string($faviconPath) && filled($faviconPath)) {
            if (str_starts_with($faviconPath, 'http://') || str_starts_with($faviconPath, 'https://')) {
                $faviconUrl = $faviconPath;
            } elseif (str_starts_with($faviconPath, 'assets/')) {
                $faviconUrl = asset($faviconPath);
            } else {
                $faviconUrl = \Illuminate\Support\Facades\Storage::url($faviconPath);
            }
        }
    @endphp
    @include('seo.meta', ['seo' => $seo ?? []])
    <link rel="icon" href="{{ $faviconUrl }}" type="image/png">
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/normalize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/modern.css') }}?v={{ filemtime(public_path('assets/frontend/css/modern.css')) }}">
    @stack('page_styles')
</head>
<body>
    @include('front.partials.header')
    <main>@yield('content')</main>
    @include('front.partials.footer')
    @include('front.partials.promo-popup-overlay')
    @include('front.partials.floating-contact')
    @include('front.partials.cookie-consent')
    <script src="{{ asset('assets/frontend/js/modern.js') }}?v={{ filemtime(public_path('assets/frontend/js/modern.js')) }}"></script>
</body>
</html>
