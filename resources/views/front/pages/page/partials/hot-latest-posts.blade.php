@if(($hotLatestPosts ?? collect())->isNotEmpty())
    <section class="page-hot-news-section">
        <div class="container">
            <div class="page-hot-news-head">
                <span>Tin đáng chú ý</span>
                <h2>Bài viết hot & mới nhất</h2>
            </div>

            <div class="page-hot-news-row">
                @foreach($hotLatestPosts as $post)
                    <article class="page-hot-news-card clickable-card" data-card-link="{{ route('news.show', $post->slug) }}">
                        <a class="page-hot-news-image" href="{{ route('news.show', $post->slug) }}" aria-label="{{ $post->title }}">
                            @if($post->thumbnail_url)
                                <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async">
                            @else
                                <span>{{ mb_substr($post->title, 0, 1) }}</span>
                            @endif
                        </a>
                        <div class="page-hot-news-body">
                            <div class="page-hot-news-meta">
                                @if($post->is_featured)
                                    <span class="is-hot">HOT</span>
                                @endif
                                <time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->format('d/m/Y') }}</time>
                            </div>
                            <h3><a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a></h3>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
