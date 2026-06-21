<div class="products-toolbar">
    <div class="products-toolbar-main">
        <div class="toolbar-left">
            <form method="GET" action="{{ url()->current() }}" class="per-page-form">
                @foreach(request()->except('per_page', 'page') as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $item)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <label for="{{ $perPageId ?? 'per-page-products' }}">Số sản phẩm/trang</label>
                <select id="{{ $perPageId ?? 'per-page-products' }}" name="per_page" onchange="this.form.submit()">
                    @foreach(($perPageOptions ?? [8, 12, 16, 24]) as $option)
                        <option value="{{ $option }}" @selected((int) $products->perPage() === (int) $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="toolbar-right">
            @if($products->total() > 0)
                Hiển thị {{ $products->firstItem() }}-{{ $products->lastItem() }} / {{ $products->total() }} sản phẩm
            @else
                Không có sản phẩm
            @endif
        </div>
    </div>

    @php
        $toolbarCategoryItems = collect($toolbarCategories ?? [])
            ->filter(fn ($item) => is_object($item) && (int) data_get($item, 'product_count', 0) > 0)
            ->values();
        $allProductsCount = (int) ($toolbarTotalCount ?? $products->total());
        $selectedToolbarTag = (string) ($selectedFilters['tag'] ?? '');
        $selectedToolbarItem = $toolbarCategoryItems->first(
            fn ($item) => (string) data_get($item, 'slug', '') === $selectedToolbarTag
        );
        $visibleToolbarItems = $toolbarCategoryItems
            ->reject(fn ($item) => $selectedToolbarItem && data_get($item, 'slug') === data_get($selectedToolbarItem, 'slug'))
            ->take($selectedToolbarItem ? 4 : 5)
            ->values();

        if ($selectedToolbarItem) {
            $visibleToolbarItems = $visibleToolbarItems->push($selectedToolbarItem)->values();
        }

        $visibleToolbarSlugs = $visibleToolbarItems
            ->map(fn ($item) => (string) data_get($item, 'slug', ''))
            ->filter()
            ->values();

        $overflowToolbarItems = $toolbarCategoryItems
            ->reject(fn ($item) => $visibleToolbarSlugs->contains((string) data_get($item, 'slug', '')))
            ->values();
    @endphp
    @if($toolbarCategoryItems->isNotEmpty())
        <div class="products-tag-toolbar">
            <a href="{{ url()->current() }}?{{ http_build_query(request()->except('tag', 'page')) }}" class="product-tag-chip {{ empty($selectedFilters['tag']) ? 'is-active' : '' }}">
                <span class="product-tag-label">Tất cả</span>
                <span class="product-tag-count">{{ $allProductsCount }}</span>
            </a>
            @foreach($visibleToolbarItems as $item)
                @php
                    $slug = data_get($item, 'slug', '');
                    $productCount = (int) data_get($item, 'product_count', 0);
                    $query = array_merge(request()->except('page', 'tag'), ['tag' => $slug]);
                @endphp
                @continue($slug === '' || $productCount <= 0)
                <a href="{{ url()->current() }}?{{ http_build_query($query) }}" class="product-tag-chip {{ ($selectedFilters['tag'] ?? '') === $slug ? 'is-active' : '' }}">
                    <span class="product-tag-label">{{ data_get($item, 'name', $slug) }}</span>
                    <span class="product-tag-count">{{ $productCount }}</span>
                </a>
            @endforeach
            @if($overflowToolbarItems->isNotEmpty())
                <details class="product-tag-more">
                    <summary class="product-tag-chip product-tag-chip-more">
                        <span class="product-tag-label">Thêm</span>
                        <span class="product-tag-count">{{ $overflowToolbarItems->count() }}</span>
                    </summary>
                    <div class="product-tag-more-menu">
                        @foreach($overflowToolbarItems as $item)
                            @php
                                $slug = data_get($item, 'slug', '');
                                $productCount = (int) data_get($item, 'product_count', 0);
                                $query = array_merge(request()->except('page', 'tag'), ['tag' => $slug]);
                            @endphp
                            @continue($slug === '' || $productCount <= 0)
                            <a href="{{ url()->current() }}?{{ http_build_query($query) }}" class="product-tag-chip {{ ($selectedFilters['tag'] ?? '') === $slug ? 'is-active' : '' }}">
                                <span class="product-tag-label">{{ data_get($item, 'name', $slug) }}</span>
                                <span class="product-tag-count">{{ $productCount }}</span>
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif
        </div>
    @endif
</div>
