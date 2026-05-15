<aside class="catalog-sidebar">
    <h3>Loc san pham</h3>
    <p class="sidebar-intro">Su dung bo loc ben duoi de chon dung may/tinh nang ban mong muon.</p>

    <form method="GET" action="{{ url()->current() }}" class="catalog-filter-form">
        @if(!empty($keyword ?? null))
            <input type="hidden" name="q" value="{{ $keyword }}">
        @endif

        <div class="filter-block">
            <h4>Khoang gia</h4>
            <div class="price-inputs">
                <input type="number" name="min_price" placeholder="Toi thieu" value="{{ $selectedFilters['min_price'] ?? '' }}">
                <input type="number" name="max_price" placeholder="Toi da" value="{{ $selectedFilters['max_price'] ?? '' }}">
            </div>
        </div>

        <div class="filter-block">
            <h4>Loai</h4>
            @foreach(($filterCategories ?? collect()) as $item)
                <label class="filter-check">
                    <input type="checkbox" name="types[]" value="{{ $item->slug }}" @checked(in_array($item->slug, $selectedFilters['types'] ?? [], true))>
                    <span>{{ $item->name }} ({{ (int) ($item->product_count ?? 0) }})</span>
                </label>
            @endforeach
        </div>

        <div class="filter-block">
            <h4>Chuc nang</h4>
            @foreach(($functionTags ?? collect()) as $item)
                <label class="filter-check">
                    <input type="checkbox" name="functions[]" value="{{ $item->slug }}" @checked(in_array($item->slug, $selectedFilters['functions'] ?? [], true))>
                    <span>{{ $item->name }}</span>
                </label>
            @endforeach
        </div>

        <div class="filter-block">
            <h4>Su dung</h4>
            @foreach(($usageTags ?? collect()) as $item)
                <label class="filter-check">
                    <input type="checkbox" name="usages[]" value="{{ $item->slug }}" @checked(in_array($item->slug, $selectedFilters['usages'] ?? [], true))>
                    <span>{{ $item->name }}</span>
                </label>
            @endforeach
        </div>

        <div class="filter-block">
            <h4>Phan loai theo</h4>
            <select name="sort">
                <option value="latest" @selected(($sort ?? 'latest') === 'latest')>Moi nhat</option>
                <option value="price_asc" @selected(($sort ?? '') === 'price_asc')>Gia tang dan</option>
                <option value="price_desc" @selected(($sort ?? '') === 'price_desc')>Gia giam dan</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-detail btn-buy">Ap dung</button>
            <a href="{{ url()->current() }}" class="btn-detail btn-outline">Xoa tat ca bo loc</a>
        </div>
    </form>
</aside>
