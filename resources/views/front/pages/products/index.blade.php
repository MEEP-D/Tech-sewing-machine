@extends('front.layouts.app')

@section('content')
@php
    $productsHeroImage = $siteContent['page_products_hero_image'] ?? null;
    if (is_string($productsHeroImage) && $productsHeroImage !== '' && !str_starts_with($productsHeroImage, 'http://') && !str_starts_with($productsHeroImage, 'https://')) {
        $productsHeroImage = str_starts_with($productsHeroImage, 'assets/')
            ? asset($productsHeroImage)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($productsHeroImage);
    }
@endphp
<section class="products-hero page-hero" @if(!empty($productsHeroImage)) style="background-image: linear-gradient(120deg, rgba(15, 23, 42, 0.72), rgba(29, 78, 216, 0.62)), url('{{ $productsHeroImage }}'); background-size: cover; background-position: center;" @endif>
    <div class="products-hero-inner container">
        <h1>{{ $siteContent['page_products_heading'] ?? 'Sản phẩm' }}</h1>
        <p>{{ $siteContent['page_products_desc'] ?? 'Giải pháp máy công nghiệp, máy lập trình và phụ kiện cho xưởng sản xuất.' }}</p>
    </div>
</section>

<section class="products-page products-page-full">
    <div class="container" style="padding: 2rem 1.5rem;">
        <div class="section-header"><h2 class="section-title">Sản phẩm</h2></div>

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
                            <label for="per-page-index">Số sản phẩm/trang</label>
                            <select id="per-page-index" name="per_page" onchange="this.form.submit()">
                                @foreach(($perPageOptions ?? [8, 12, 16, 24]) as $option)
                                    <option value="{{ $option }}" @selected((int) $products->perPage() === (int) $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="toolbar-right">Trang {{ $products->currentPage() }}/{{ $products->lastPage() }}</div>
                </div>
                @php($toolbarTagItems = collect($toolbarTags ?? [])->filter(fn ($tag) => is_object($tag) && isset($tag->slug, $tag->name)))
                @if($toolbarTagItems->isNotEmpty())
                    <div class="products-tag-toolbar">
                        <a href="{{ url()->current() }}?{{ http_build_query(request()->except('tag', 'page')) }}" class="product-tag-chip {{ empty($selectedFilters['tag']) ? 'is-active' : '' }}">Tất cả</a>
                        @foreach($toolbarTagItems as $tag)
                            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['tag' => $tag->slug])) }}" class="product-tag-chip {{ ($selectedFilters['tag'] ?? '') === $tag->slug ? 'is-active' : '' }}">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                @endif

                <div class="product-grid catalog-grid">
                    @foreach($products as $product)
                        @php($cardImage = $product->display_image_url
                            ?: (!empty($product->image) ? asset(ltrim($product->image, '/')) : null)
                            ?: (!empty($product->thumbnail) ? asset(ltrim($product->thumbnail, '/')) : null))
                        <article class="product-card catalog-card clickable-card" data-card-link="{{ route('products.show', $product->slug) }}">
                            <div class="product-img">
                                @if($cardImage)
                                    <img src="{{ $cardImage }}" alt="{{ $product->name }}">
                                @endif
                                <span class="badge-installment">Trả góp {{ max(0, (int) $product->installment_percent) }}%</span>
                                @if(((int) $product->discount_percent) > 0)
                                    <span class="badge-discount-ribbon">-{{ (int) $product->discount_percent }}%</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
                                <div class="product-price">{{ $product->price ?: 'Liên hệ' }}</div>
                                <div class="product-meta">Mã SP: {{ $product->code ?: ($product->sku ?: 'Đang cập nhật') }}</div>
                                @if($product->short_description)
                                    <p class="product-snippet">{{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 120) }}</p>
                                @endif
                                <div class="product-cat">{{ $product->category?->name }}</div>
                                <div class="product-actions catalog-actions">
                                    <a href="{{ route('contact') }}" class="btn-detail btn-buy">Liên hệ</a>
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn-detail btn-outline">Xem chi tiết</a>
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
@include('front.partials.newsletter-signup')
@endsection
