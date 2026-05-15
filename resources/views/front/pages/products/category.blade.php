@extends('front.layouts.app')

@section('content')
<section class="products-hero">
    <div class="products-hero-inner container">
        <h1>{{ $category->name }}</h1>
        <p>Danh muc san pham duoc toi uu cho tung nhu cau van hanh va san xuat.</p>
    </div>
</section>

<section class="products-page products-page-full">
    <div class="container" style="padding: 2rem 1.5rem;">
        <div class="section-header"><h2 class="section-title">{{ $category->name }}</h2></div>

        <div class="products-layout">
            @include('front.pages.products._filters')

            <div class="catalog-content">
                <div class="products-toolbar">
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
                            <label for="per-page-category">So san pham/trang</label>
                            <select id="per-page-category" name="per_page" onchange="this.form.submit()">
                                @foreach(($perPageOptions ?? [8, 12, 16, 24]) as $option)
                                    <option value="{{ $option }}" @selected((int) $products->perPage() === (int) $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="toolbar-right">Trang {{ $products->currentPage() }}/{{ $products->lastPage() }}</div>
                </div>

                <div class="product-grid catalog-grid">
                    @foreach($products as $product)
                        <article class="product-card catalog-card clickable-card" data-card-link="{{ route('products.show', $product->slug) }}">
                            <div class="product-img">
                                @if($product->display_image_url)
                                    <img src="{{ $product->display_image_url }}" alt="{{ $product->name }}">
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
                                <div class="product-price">{{ $product->price ?: 'Lien he' }}</div>
                                <div class="product-meta">Ma SP: {{ $product->code ?: ($product->sku ?: 'Dang cap nhat') }}</div>
                                @if($product->short_description)
                                    <p class="product-snippet">{{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 120) }}</p>
                                @endif
                                <div class="product-cat">{{ $product->category?->name }}</div>
                                <div class="product-actions catalog-actions">
                                    <a href="{{ route('contact') }}" class="btn-detail btn-buy">Lien he</a>
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn-detail btn-outline">Xem chi tiet</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{ $products->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
