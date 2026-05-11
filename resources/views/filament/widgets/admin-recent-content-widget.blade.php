<x-filament-widgets::widget>
    <x-filament::section heading="Nội dung mới nhất" icon="heroicon-o-clock">
        <div class="admin-content-stack">
            <div class="admin-content-group">
                <div class="admin-content-group-head">
                    <span class="admin-content-kicker admin-content-kicker-blue">Sản phẩm</span>
                    <span>{{ $latestProducts->count() }} mục</span>
                </div>

                <div class="admin-content-list">
                    @forelse ($latestProducts as $item)
                        <a href="{{ url('/admin/products/' . $item->id . '/edit') }}" class="admin-content-row">
                            <span class="admin-content-title">{{ $item->name }}</span>
                            <span class="admin-content-time">{{ $item->created_at?->format('d/m H:i') }}</span>
                        </a>
                    @empty
                        <p class="admin-empty-state">Chưa có sản phẩm mới.</p>
                    @endforelse
                </div>
            </div>

            <div class="admin-content-group">
                <div class="admin-content-group-head">
                    <span class="admin-content-kicker admin-content-kicker-green">Bài viết</span>
                    <span>{{ $latestPosts->count() }} mục</span>
                </div>

                <div class="admin-content-list">
                    @forelse ($latestPosts as $item)
                        <a href="{{ url('/admin/posts/' . $item->id . '/edit') }}" class="admin-content-row">
                            <span class="admin-content-title">{{ $item->title }}</span>
                            <span class="admin-content-time">{{ $item->created_at?->format('d/m H:i') }}</span>
                        </a>
                    @empty
                        <p class="admin-empty-state">Chưa có bài viết mới.</p>
                    @endforelse
                </div>
            </div>

            <div class="admin-content-group">
                <div class="admin-content-group-head">
                    <span class="admin-content-kicker admin-content-kicker-amber">Trang</span>
                    <span>{{ $latestPages->count() }} mục</span>
                </div>

                <div class="admin-content-list">
                    @forelse ($latestPages as $item)
                        <a href="{{ url('/admin/pages/' . $item->id . '/edit') }}" class="admin-content-row">
                            <span class="admin-content-title">{{ $item->title }}</span>
                            <span class="admin-content-time">{{ $item->created_at?->format('d/m H:i') }}</span>
                        </a>
                    @empty
                        <p class="admin-empty-state">Chưa có trang mới.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
