@extends('front.layouts.app')

@section('content')
@php
    $newsHeroImage = $siteContent['page_news_hero_image'] ?? null;
    if (is_string($newsHeroImage) && $newsHeroImage !== '' && !str_starts_with($newsHeroImage, 'http://') && !str_starts_with($newsHeroImage, 'https://')) {
        $newsHeroImage = str_starts_with($newsHeroImage, 'assets/')
            ? asset($newsHeroImage)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($newsHeroImage);
    }
@endphp
@push('preload_assets')
    @if(!empty($newsHeroImage))
        <link rel="preload" as="image" href="{{ $newsHeroImage }}" fetchpriority="high">
    @endif
@endpush
<section class="news-hero page-hero" @if(!empty($newsHeroImage)) style="background-image: linear-gradient(120deg, rgba(15, 23, 42, 0.72), rgba(29, 78, 216, 0.62)), url('{{ $newsHeroImage }}'); background-size: cover; background-position: center;" @endif>
    <div class="container">
        <h1>{{ $siteContent['page_news_heading'] ?? 'Tin tức' }}</h1>
        <p>{{ $siteContent['page_news_desc'] ?? 'Cập nhật nhanh thị trường, sản phẩm và hướng dẫn vận hành thực tế cho xưởng may.' }}</p>
    </div>
</section>

<section class="news-page">
    <div class="container">
        <form method="GET" action="{{ route('news.category', $category->slug) }}" class="news-search-form news-search-form--wide" id="newsSearchForm">
            @if(!empty($activeTag))
                <input type="hidden" name="tag" value="{{ $activeTag }}">
            @endif
            <input
                type="text"
                name="q"
                id="newsSearchInput"
                value="{{ $keyword ?? '' }}"
                placeholder="Tìm kiếm trong danh mục này..."
                autocomplete="off"
            >
            <button type="submit">Tìm</button>
        </form>
        @if(($newsTags ?? collect())->isNotEmpty())
            <div class="news-tag-toolbar">
                <a href="{{ route('news.category', [$category->slug] + request()->except('tag', 'page')) }}" class="news-tag-chip {{ empty($activeTag) ? 'is-active' : '' }}">Tất cả</a>
                @foreach($newsTags as $tag)
                    <a href="{{ route('news.category', [$category->slug] + array_merge(request()->except('page'), ['tag' => $tag->slug])) }}" class="news-tag-chip {{ ($activeTag ?? '') === $tag->slug ? 'is-active' : '' }}">{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="container news-layout">
        <div class="news-main">

            <div class="news-list">
                @include('front.pages.news._list', ['posts' => $posts])
            </div>

            @if($posts->hasMorePages())
                <div class="news-load-more-wrap">
                    <button
                        type="button"
                        class="news-load-more-btn"
                        id="newsLoadMoreBtn"
                        data-next-page="{{ $posts->nextPageUrl() }}"
                    >
                        Xem thêm
                    </button>
                </div>
            @endif
        </div>

        @include('front.pages.news._sidebar')
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('newsSearchForm');
    const input = document.getElementById('newsSearchInput');
    const loadMoreBtn = document.getElementById('newsLoadMoreBtn');
    const list = document.querySelector('.news-list');
    if (!form || !input) return;

    let timer = null;
    input.addEventListener('input', () => {
        if (timer) clearTimeout(timer);
        timer = setTimeout(() => {
            if (input.value.trim().length === 0 || input.value.trim().length >= 2) {
                form.submit();
            }
        }, 450);
    });

    if (loadMoreBtn && list) {
        loadMoreBtn.addEventListener('click', async () => {
            const nextPageUrl = loadMoreBtn.dataset.nextPage;
            if (!nextPageUrl) return;

            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Đang tải...';

            try {
                const response = await fetch(nextPageUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                if (data?.html) {
                    list.insertAdjacentHTML('beforeend', data.html);
                }

                if (data?.nextPageUrl) {
                    loadMoreBtn.dataset.nextPage = data.nextPageUrl;
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'Xem thêm';
                } else {
                    loadMoreBtn.remove();
                }
            } catch (error) {
                loadMoreBtn.disabled = false;
                loadMoreBtn.textContent = 'Xem thêm';
            }
        });
    }
});
</script>
@include('front.partials.newsletter-signup')
@endsection
