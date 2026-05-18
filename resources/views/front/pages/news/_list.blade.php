@forelse($posts as $post)
    @php
        $ribbons = [];

        if ((bool) $post->is_featured) {
            $ribbons[] = ['label' => 'HOT', 'class' => 'is-hot'];
        }

        $isLatest = optional($post->published_at)->greaterThanOrEqualTo(now()->subDays(3))
            || optional($post->updated_at)->greaterThanOrEqualTo(now()->subDays(3));
        if ($isLatest) {
            $ribbons[] = ['label' => 'Mới nhất', 'class' => 'is-latest'];
        }
    @endphp

    <article class="news-card clickable-card" data-card-link="{{ route('news.show', $post->slug) }}">
        <div class="news-card-image">
            @if(!empty($ribbons))
                <div class="news-card-ribbons">
                    @foreach($ribbons as $ribbon)
                        <span class="news-card-ribbon {{ $ribbon['class'] }}">{{ $ribbon['label'] }}</span>
                    @endforeach
                </div>
            @endif
            @if($post->thumbnail_url)
                <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
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
