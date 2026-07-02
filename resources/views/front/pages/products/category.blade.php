@extends('front.layouts.app')

@section('content')
@php
    $media = app(\App\Support\OptimizedMedia::class);
    $productsHeroImage = $siteContent['page_products_hero_image'] ?? null;
    $productsHeroImage = $media->url($productsHeroImage, ['width' => 1600, 'quality' => 74]);
@endphp
@push('preload_assets')
    @if(!empty($productsHeroImage))
        <link rel="preload" as="image" href="{{ $productsHeroImage }}" fetchpriority="high">
    @endif
@endpush
<section class="products-hero page-hero" @if(!empty($productsHeroImage)) style="background-image: linear-gradient(120deg, rgba(15, 23, 42, 0.72), rgba(29, 78, 216, 0.62)), url('{{ $productsHeroImage }}'); background-size: cover; background-position: center;" @endif>
    <div class="products-hero-inner container">
        <h1>{{ $siteContent['page_products_heading'] ?? 'Sản phẩm' }}</h1>
        <p>{{ $siteContent['page_products_desc'] ?? 'Giải pháp máy công nghiệp, máy lập trình và phụ kiện cho xưởng sản xuất.' }}</p>
    </div>
</section>

<section class="products-page products-page-full">
    <div class="container">
        <div class="section-header"><h2 class="section-title">{{ $category->name }}</h2></div>

        <div class="products-layout">
            @include('front.pages.products._filters')

            <div class="catalog-content">
                @include('front.pages.products._toolbar', ['perPageId' => 'per-page-category'])

                <div class="product-grid catalog-grid">
                    @foreach($products as $product)
                        @php($cardImage = $product->display_image_url
                            ?: (!empty($product->image) ? asset(ltrim($product->image, '/')) : null)
                            ?: (!empty($product->thumbnail) ? asset(ltrim($product->thumbnail, '/')) : null))
                        <article class="product-card catalog-card clickable-card" data-card-link="{{ route('products.show', $product->slug) }}">
                            <div class="product-img">
                                @if($cardImage)
                                    <img src="{{ $media->url($product->display_image, ['width' => 640, 'quality' => 76]) ?? $cardImage }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                                @endif
                                @if($product->installment_percent)
                                    <span class="badge-installment">Khuyến mãi</span>
                                @endif
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
