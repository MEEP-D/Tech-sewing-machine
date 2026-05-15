@extends('front.layouts.app')

@section('content')
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

            @if($post->thumbnail)
                <div class="news-detail-image">
                    <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}">
                </div>
            @endif

            <div class="news-detail-content">{!! $post->content !!}</div>

            @if($relatedPosts->isNotEmpty())
                <section class="news-related">
                    <h3>Bài viết liên quan</h3>
                    <div class="news-related-grid">
                        @foreach($relatedPosts as $related)
                            <article class="news-related-card clickable-card" data-card-link="{{ route('news.show', $related->slug) }}">
                                @if($related->thumbnail)
                                    <div class="news-related-image">
                                        <img src="{{ asset($related->thumbnail) }}" alt="{{ $related->title }}">
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
@endsection
