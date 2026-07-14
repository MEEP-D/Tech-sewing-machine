@php
    $media = $media ?? app(\App\Support\OptimizedMedia::class);
    $heroPosts = collect($heroPosts ?? [])->filter()->take(5)->values();
    $newsHeroTitle = $siteContent['page_news_heading'] ?? 'Tin tức';
    $newsHeroDescription = $siteContent['page_news_desc'] ?? 'Cập nhật nhanh thị trường, sản phẩm và hướng dẫn vận hành thực tế cho xưởng may.';
    $newsHeroKicker = $siteContent['page_news_kicker'] ?? 'Tin tức & cập nhật';
    $newsHeroActionUrl = $newsHeroActionUrl ?? route('news.index') . '#news-list';
@endphp

<section class="news-featured-hero page-hero{{ !empty($newsHeroImage) ? ' has-custom-image' : '' }}">
    @if(!empty($newsHeroImage))
        <div class="news-featured-hero-backdrop" aria-hidden="true" style="background-image: url('{{ $newsHeroImage }}');"></div>
    @endif
    <div class="news-featured-hero-pattern" aria-hidden="true"></div>

    <div class="news-featured-hero-inner container{{ $heroPosts->isEmpty() ? ' is-copy-only' : '' }}">
        <div class="news-featured-hero-copy">
            <div class="news-featured-hero-kicker">
                <i class="fas fa-newspaper" aria-hidden="true"></i>
                <span>{{ $newsHeroKicker }}</span>
            </div>

            <h1>{{ $newsHeroTitle }}</h1>
            <p>{{ $newsHeroDescription }}</p>

            <a href="{{ $newsHeroActionUrl }}" class="news-featured-hero-cta">
                <i class="fas fa-book-open" aria-hidden="true"></i>
                <span>Xem tất cả bài viết</span>
            </a>
        </div>

        @if($heroPosts->isNotEmpty())
            <div class="news-featured-hero-grid" aria-label="Bài viết nổi bật">
                @foreach($heroPosts as $index => $heroPost)
                    @php
                        $isLarge = $index < 2;
                        $heroImage = $media->url($heroPost->thumbnail, ['width' => $isLarge ? 760 : 520, 'quality' => 76]) ?? $heroPost->thumbnail_url;
                        $heroBadge = $heroPost->category?->name ?: ($heroPost->is_featured ? 'Nổi bật' : 'Tin mới');
                    @endphp
                    <article class="news-featured-card{{ $isLarge ? ' is-large' : '' }}">
                        <a href="{{ route('news.show', $heroPost->slug) }}" class="news-featured-card-link">
                            <div class="news-featured-card-media">
                                <span class="news-featured-card-badge">{{ $heroBadge }}</span>
                                @if($heroImage)
                                    <img src="{{ $heroImage }}" alt="{{ $heroPost->title }}" loading="lazy" decoding="async">
                                @else
                                    <div class="news-featured-card-placeholder" aria-hidden="true"></div>
                                @endif
                            </div>

                            <div class="news-featured-card-body">
                                <h2>{{ \Illuminate\Support\Str::limit($heroPost->title, $isLarge ? 72 : 56) }}</h2>
                                <div class="news-featured-card-meta">
                                    <span>
                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                        {{ optional($heroPost->published_at)->format('d/m/Y') ?: 'Đang cập nhật' }}
                                    </span>
                                    <span>{{ $heroPost->reading_time }} phút đọc</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
