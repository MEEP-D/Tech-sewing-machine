@extends('front.layouts.app')

@section('content')
@php
    $postCoverImage = $post->thumbnail_url;
    if (! $postCoverImage) {
        $postCoverImage = $siteContent['page_news_hero_image'] ?? null;
        if (is_string($postCoverImage) && $postCoverImage !== '' && !str_starts_with($postCoverImage, 'http://') && !str_starts_with($postCoverImage, 'https://')) {
            $postCoverImage = str_starts_with($postCoverImage, 'assets/')
                ? asset($postCoverImage)
                : \Illuminate\Support\Facades\Storage::disk('public')->url($postCoverImage);
        }
    }
@endphp
<section class="news-page news-detail-page">
    <div class="container news-layout">
        <article class="news-detail-main">
            <header class="news-detail-header">
                <div class="news-card-meta">
                    <span>{{ $post->category?->name }}</span>
                    <span>{{ optional($post->published_at)->format('d/m/Y') }}</span>
                    <span>{{ number_format((int) $post->view_count) }} lượt xem</span>
                </div>
                <h1>{{ $post->title }}</h1>
                @if($post->excerpt)
                    <p class="news-detail-excerpt">{{ $post->excerpt }}</p>
                @endif
            </header>

            @if($postCoverImage)
                <div class="news-detail-image">
                    <img src="{{ $postCoverImage }}" alt="{{ $post->title }}">
                </div>
            @endif

            <div class="news-detail-content">{!! $post->rendered_content !!}</div>

            @if($relatedPosts->isNotEmpty())
                <section class="news-related">
                    <h3>Bài viết liên quan</h3>
                    <div class="news-related-grid">
                        @foreach($relatedPosts as $related)
                            <article class="news-related-card clickable-card" data-card-link="{{ route('news.show', $related->slug) }}">
                                @if($related->thumbnail_url)
                                    <div class="news-related-image">
                                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}">
                                    </div>
                                @endif
                                <div class="news-related-body">
                                    <h4><a href="{{ route('news.show', $related->slug) }}">{{ $related->title }}</a></h4>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>

        @include('front.pages.news._sidebar')
    </div>
</section>
@include('front.partials.newsletter-signup')
@endsection
