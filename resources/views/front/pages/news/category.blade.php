@extends('front.layouts.app')

@section('content')
<section class="news-hero">
    <div class="container">
        <h1>{{ $category->name }}</h1>
        <p>Danh mục tin tức chuyên sâu, bám sát vận hành sản xuất thực tế.</p>
    </div>
</section>

<section class="news-page">
    <div class="container news-layout">
        <div class="news-main">
            <form method="GET" action="{{ route('news.category', $category->slug) }}" class="news-search-form" id="newsSearchForm">
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

            <div class="news-list">
                @forelse($posts as $post)
                    <article class="news-card clickable-card" data-card-link="{{ route('news.show', $post->slug) }}">
                        <div class="news-card-image">
                            @if($post->thumbnail)
                                <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}">
                            @endif
                        </div>
                        <div class="news-card-body">
                            <div class="news-card-meta">
                                <span>{{ $post->category?->name }}</span>
                                <span>Cập nhật: {{ optional($post->updated_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            <h2><a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a></h2>
                            <p>{{ $post->excerpt }}</p>
                        </div>
                    </article>
                @empty
                    <div class="news-empty-state">Không tìm thấy tin tức phù hợp.</div>
                @endforelse
            </div>

            <div class="news-pagination">{{ $posts->onEachSide(1)->links() }}</div>
        </div>

        @include('front.pages.news._sidebar')
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('newsSearchForm');
    const input = document.getElementById('newsSearchInput');
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
});
</script>
@endsection
