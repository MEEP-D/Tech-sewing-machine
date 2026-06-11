@extends('front.layouts.app')

@section('content')
@php
    $isBuilderMode = $page->layout_mode === 'builder';
    $contentClass = $isBuilderMode
        ? 'page-rich-content page-mode-builder page-full-width-content'
        : 'page-rich-content page-full-width-content';

    $pageCoverImage = $page->image_url;
    if (! $pageCoverImage) {
        $pageCoverImage = $siteContent['page_news_hero_image'] ?? null;
        if (is_string($pageCoverImage) && $pageCoverImage !== '' && !str_starts_with($pageCoverImage, 'http://') && !str_starts_with($pageCoverImage, 'https://')) {
            $pageCoverImage = str_starts_with($pageCoverImage, 'assets/')
                ? asset($pageCoverImage)
                : \Illuminate\Support\Facades\Storage::disk('public')->url($pageCoverImage);
        }
    }
@endphp
@push('preload_assets')
    @if(!empty($pageCoverImage))
        <link rel="preload" as="image" href="{{ $pageCoverImage }}" fetchpriority="high">
    @endif
@endpush

@if(!empty($pageCoverImage))
<section class="page-hero page-hero-dynamic" style="background-image: linear-gradient(120deg, rgba(15, 23, 42, 0.72), rgba(29, 78, 216, 0.62)), url('{{ $pageCoverImage }}'); background-size: cover; background-position: center;">
    <div class="container">
        <h1>{{ $page->title }}</h1>
    </div>
</section>
@endif

<section class="page-layout-full-width">
    <div class="page-content-shell">
        <div class="section-header"><h1 class="section-title">{{ $page->title }}</h1></div>
        <div class="{{ $contentClass }}">{!! $html !!}</div>
    </div>
</section>

@include('front.partials.newsletter-signup')
@endsection
