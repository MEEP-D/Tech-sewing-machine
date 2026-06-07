<aside class="catalog-sidebar">
    <h3>Lọc sản phẩm</h3>
    <p class="sidebar-intro">Sử dụng bộ lọc bên dưới để chọn đúng máy/tính năng bạn mong muốn.</p>

    <form method="GET" action="{{ url()->current() }}" class="catalog-filter-form">
        @if(!empty($keyword ?? null))
            <input type="hidden" name="q" value="{{ $keyword }}">
        @endif

        <div class="filter-block">
            <h4>Khoảng giá</h4>
            <div class="price-inputs">
                <input type="number" name="min_price" placeholder="Tối thiểu" value="{{ $selectedFilters['min_price'] ?? '' }}">
                <input type="number" name="max_price" placeholder="Tối đa" value="{{ $selectedFilters['max_price'] ?? '' }}">
            </div>
        </div>

        <div class="filter-block">
            <h4>Loại</h4>
            @foreach(($filterCategories ?? collect()) as $item)
                @php
                    $slug = data_get($item, 'slug', is_string($item) ? $item : '');
                    $name = data_get($item, 'name', $slug);
                    $productCount = (int) data_get($item, 'product_count', 0);
                @endphp
                @continue($slug === '')
                <label class="filter-check">
                    <input type="checkbox" name="types[]" value="{{ $slug }}" @checked(in_array($slug, $selectedFilters['types'] ?? [], true))>
                    <span>{{ $name }} ({{ $productCount }})</span>
                </label>
            @endforeach
        </div>

        <div class="filter-block">
            <h4>Chức năng</h4>
            @foreach(($functionTags ?? collect()) as $item)
                @php
                    $slug = data_get($item, 'slug', is_string($item) ? $item : '');
                    $name = data_get($item, 'name', $slug);
                @endphp
                @continue($slug === '')
                <label class="filter-check">
                    <input type="checkbox" name="functions[]" value="{{ $slug }}" @checked(in_array($slug, $selectedFilters['functions'] ?? [], true))>
                    <span>{{ $name }}</span>
                </label>
            @endforeach
        </div>

        <div class="filter-block">
            <h4>Sử dụng</h4>
            @foreach(($usageTags ?? collect()) as $item)
                @php
                    $slug = data_get($item, 'slug', is_string($item) ? $item : '');
                    $name = data_get($item, 'name', $slug);
                @endphp
                @continue($slug === '')
                <label class="filter-check">
                    <input type="checkbox" name="usages[]" value="{{ $slug }}" @checked(in_array($slug, $selectedFilters['usages'] ?? [], true))>
                    <span>{{ $name }}</span>
                </label>
            @endforeach
        </div>

        <div class="filter-block">
            <h4>Phân loại theo</h4>
            <select name="sort">
                <option value="latest" @selected(($sort ?? 'latest') === 'latest')>Mới nhất</option>
                <option value="price_asc" @selected(($sort ?? '') === 'price_asc')>Giá tăng dần</option>
                <option value="price_desc" @selected(($sort ?? '') === 'price_desc')>Giá giảm dần</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-detail btn-buy">Áp dụng</button>
            <a href="{{ url()->current() }}" class="btn-detail btn-outline">Xóa tất cả bộ lọc</a>
        </div>
    </form>
</aside>
