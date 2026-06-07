@extends('front.layouts.app')

@section('content')
@php
    $isBuilderMode = $page->layout_mode === 'builder';
    $contentClass = $isBuilderMode
        ? 'page-rich-content page-mode-builder'
        : 'page-rich-content page-rich-content-centered';
@endphp

<section class="container" style="padding: 2rem 1.5rem;">
    <div class="section-header"><h1 class="section-title">{{ $page->title }}</h1></div>
    <div class="{{ $contentClass }}">{!! $html !!}</div>
</section>

@include('front.partials.newsletter-signup')
@endsection
