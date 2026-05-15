<aside class="news-sidebar">
    <div class="news-sidebar-card compact">
        <h3>Danh mục tin tức</h3>
        <ul class="news-sidebar-list compact">
            @if($marketCategory)
                <li><a href="{{ route('news.category', $marketCategory->slug) }}">Về thị trường</a></li>
            @endif
            @if($productCategory)
                <li><a href="{{ route('news.category', $productCategory->slug) }}">Về sản phẩm</a></li>
            @endif
            @if($guideCategory)
                <li><a href="{{ route('news.category', $guideCategory->slug) }}">Hướng dẫn sử dụng sản phẩm</a></li>
            @endif
        </ul>
    </div>

    <div class="news-sidebar-card compact">
        <h3>Tin nổi bật</h3>
        <ul class="news-sidebar-list compact">
            @forelse($featuredPosts as $featured)
                <li><a href="{{ route('news.show', $featured->slug) }}">{{ $featured->title }}</a></li>
            @empty
                <li><span class="news-sidebar-empty">Đang cập nhật</span></li>
            @endforelse
        </ul>
    </div>
</aside>
