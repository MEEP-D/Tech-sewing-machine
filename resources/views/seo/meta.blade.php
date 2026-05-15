@props(['seo'])

@php
    $title = $seo['meta_title'] ?? ($seo['title'] ?? config('app.name'));
    $description = $seo['meta_description'] ?? ($seo['description'] ?? '');
    $keywords = $seo['keywords'] ?? ($seo['focus_keyword'] ?? '');
    $canonical = $seo['canonical_url'] ?? ($seo['canonical'] ?? url()->current());
    $ogImage = $seo['og_image'] ?? asset('assets/frontend/images/og-image.jpg');
    $ogType = $seo['og_type'] ?? 'website';
    $schemaMarkup = $seo['schema_markup'] ?? [];
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@if($keywords)
    <meta name="keywords" content="{{ $keywords }}">
@endif
<link rel="canonical" href="{{ $canonical }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="{{ $ogImage }}">

<!-- Schema Markup -->
@foreach($schemaMarkup as $schema)
    <script type="application/ld+json">
        {!! json_encode($schema) !!}
    </script>
@endforeach
