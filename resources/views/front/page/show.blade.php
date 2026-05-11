@extends('front.layouts.app')

@section('content')
    <article class="v-content-wrap section-shell-sm">
        <div class="container" style="max-width: 900px;">
            <div class="v-breadcrumbs v-reveal">
                <a href="{{ route('home') }}">Trang chủ</a>
                <span>/</span>
                <strong>{{ $page->title }}</strong>
            </div>

            <div class="v-article-hero v-reveal">
                <h1>{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p>{{ $page->excerpt }}</p>
                @endif
            </div>

            @if($page->image)
                <div class="v-article-cover v-reveal stagger-1">
                    <img src="{{ asset($page->image) }}" alt="{{ $page->title }}" loading="lazy">
                </div>
            @endif

            <div class="v-article-body v-reveal">
                @if($page->layout_mode === 'builder')
                    {!! $html !!}
                @else
                    <div class="prose" style="max-width: 100%">{!! $page->content !!}</div>
                @endif
            </div>
        </div>
    </article>
@endsection
