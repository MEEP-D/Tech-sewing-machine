@extends('front.layouts.app')

@section('content')
@php($highlightProduct = $highlightProduct ?? null)
@php($highlightSpecs = ($highlightProduct && $highlightProduct->relationLoaded('specs')) ? $highlightProduct->specs->values() : collect())
@php($homeFaqs = collect($siteContent['home_faqs'] ?? [])->take(6))
@php($bannerProducts = ($exclusiveProducts ?? collect())->values()->map(function($item){
    return [
        'name' => $item->code ?: $item->sku ?: $item->name,
        'image' => $item->display_image_url,
        'link' => route('products.show', $item->slug),
        'specs' => [
            'size' => optional($item->specs->get(0))->value ?? '-',
            'speed' => optional($item->specs->get(1))->value ?? '-',
            'precision' => optional($item->specs->get(2))->value ?? '-',
            'price' => $item->price ?: 'Liên hệ',
        ],
    ];
}))

<section class="hero">
    <div class="hero-slider">
        @forelse(($sliders ?? collect()) as $index => $slider)
            <div
                class="hero-slide {{ $index === 0 ? 'active' : '' }} {{ $slider->show_overlay ? '' : 'no-overlay' }}"
                style="background-image: url('{{ $slider->image_url }}');"
                aria-label="{{ $slider->title ?? 'Slider image' }}"
                data-link="{{ $slider->link }}"
            ></div>
        @empty
            <div class="hero-slide active"></div>
        @endforelse
    </div>
    <div class="container">
        <div class="hero-content">
            @foreach(($sliders ?? collect()) as $index => $slider)
                <div class="hero-slide-content {{ $index === 0 ? 'active' : '' }}">
                    @if($slider->show_title)
                        <h1 class="hero-title">{{ $slider->title }}</h1>
                    @endif
                    @if($slider->show_subtitle)
                        <p class="hero-subtitle">{{ $slider->subtitle }}</p>
                    @endif
                    @if($slider->show_button && $slider->link)
                        <div class="hero-buttons"><a href="{{ $slider->link }}" class="btn btn-primary">Tìm hiểu ngay</a></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <div class="slider-nav">
        @forelse(($sliders ?? collect()) as $index => $slider)
            <div class="dot {{ $index === 0 ? 'active' : '' }}"></div>
        @empty
            <div class="dot active"></div>
        @endforelse
    </div>
    @if(($sliders ?? collect())->count() > 1)
        <button class="hero-arrow prev-hero" id="hero-prev"><i class="fas fa-chevron-left"></i></button>
        <button class="hero-arrow next-hero" id="hero-next"><i class="fas fa-chevron-right"></i></button>
    @endif
</section>

<section class="slogan-section">
    <div class="container">
        <div class="slogan-content">
            <h2 class="slogan-text">{{ $siteContent['home_slogan_title'] ?? 'Giải pháp may công nghiệp chính xác, bền bỉ, tối ưu chi phí' }}</h2>
            <p class="slogan-subtext">{{ $siteContent['home_slogan_subtitle'] ?? 'Đồng hành cùng nhà máy từ tư vấn, lắp đặt đến bảo hành tận nơi.' }}</p>
        </div>
    </div>
</section>

<section class="partners-section">
    <div class="partners-title">{{ $siteContent['home_partners_title'] ?? '' }}</div>
    <div class="marquee-container">
        <div class="marquee-track scroll-left">
            <div class="marquee-group">
                @foreach(($partners ?? collect()) as $partner)
                    @if($partner->logo_url)
                        <a href="{{ $partner->url ?: 'javascript:void(0)' }}" class="partner-logo" @if($partner->url) target="_blank" rel="noopener noreferrer" @endif>
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}">
                        </a>
                    @endif
                @endforeach
            </div>
            <div class="marquee-group">
                @foreach(($partners ?? collect()) as $partner)
                    @if($partner->logo_url)
                        <a href="{{ $partner->url ?: 'javascript:void(0)' }}" class="partner-logo" @if($partner->url) target="_blank" rel="noopener noreferrer" @endif>
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="marquee-track scroll-right">
            <div class="marquee-group">
                @foreach(($partners ?? collect()) as $partner)
                    @if($partner->logo_url)
                        <a href="{{ $partner->url ?: 'javascript:void(0)' }}" class="partner-logo" @if($partner->url) target="_blank" rel="noopener noreferrer" @endif>
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}">
                        </a>
                    @endif
                @endforeach
            </div>
            <div class="marquee-group">
                @foreach(($partners ?? collect()) as $partner)
                    @if($partner->logo_url)
                        <a href="{{ $partner->url ?: 'javascript:void(0)' }}" class="partner-logo" @if($partner->url) target="_blank" rel="noopener noreferrer" @endif>
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>

@if($highlightProduct)
<section class="special-product-section">
    <div class="container">
        <div class="special-product-container">
            <div class="special-product-image">
                @if($highlightProduct->display_image_url)
                    <img src="{{ $highlightProduct->display_image_url }}" alt="{{ $highlightProduct->name }}">
                @endif
                @if($highlightProduct->video_id)
                    <div class="video-container"><iframe src="https://www.youtube.com/embed/{{ $highlightProduct->video_id }}" allowfullscreen></iframe></div>
                @endif
            </div>
            <div class="special-product-content">
                <span class="special-tag">Sản Phẩm Đột Phá</span>
                <h2 class="special-title">{{ $highlightProduct->name }}</h2>
                <span class="special-code">Mã SP: {{ $highlightProduct->code ?: $highlightProduct->sku }}</span>
                <div class="special-price"><i class="fas fa-tag"></i> Giá: {{ $highlightProduct->price ?: 'Liên hệ' }}</div>
                <p class="special-description">{{ $highlightProduct->long_description ?: $highlightProduct->short_description }}</p>
                <div class="special-specs-grid">
                    @foreach($highlightSpecs->take(8) as $spec)
                        <div class="spec-item"><span class="spec-label">{{ $spec->key }}</span><span class="spec-value">{{ $spec->value }}</span></div>
                    @endforeach
                </div>
                <div class="contact-box">
                    <a class="contact-btn highlight" href="tel:{{ preg_replace('/\D+/', '', $siteContent['home_highlight_contact_primary_phone'] ?? '0902806599') }}">
                        <i class="fas fa-phone"></i>
                        <span>{{ $siteContent['home_highlight_contact_primary_phone'] ?? '0902 806 599' }} ({{ $siteContent['home_highlight_contact_primary_name'] ?? 'Mr. Sáng' }})</span>
                    </a>
                    <a class="contact-btn" href="tel:{{ preg_replace('/\D+/', '', $siteContent['home_highlight_contact_secondary_phone'] ?? '0898303287') }}">
                        <i class="fas fa-phone"></i>
                        <span>{{ $siteContent['home_highlight_contact_secondary_phone'] ?? '0898 303 287' }} ({{ $siteContent['home_highlight_contact_secondary_name'] ?? 'Mr. Bảo' }})</span>
                    </a>
                </div>
                <div class="home-benefits">
                    <div class="benefit-item">
                        <i class="fas fa-screwdriver-wrench benefit-icon" aria-hidden="true"></i>
                        <span>Lắp đặt miễn phí</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-truck benefit-icon" aria-hidden="true"></i>
                        <span>Giao hàng tận nơi</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-user-shield benefit-icon" aria-hidden="true"></i>
                        <span>Bảo hành chính hãng</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-gem benefit-icon" aria-hidden="true"></i>
                        <span>Công nghệ độc quyền</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if($bannerProducts->isNotEmpty())
<div class="banner-switcher-section" id="banner-switcher-root" data-link="{{ $bannerProducts->first()['link'] }}">
    <div class="banner-switcher-wrapper">
        <div class="banner-watermark" id="banner-watermark">{{ $bannerProducts->first()['name'] }}</div>
        <div class="banner-main">
            <button class="banner-arrow prev" id="banner-prev"><i class="fas fa-chevron-left"></i></button>
            <div class="banner-image-box">
                <a href="{{ $bannerProducts->first()['link'] }}" id="banner-img-link">
                    <img src="{{ $bannerProducts->first()['image'] ?: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==' }}" id="banner-img" alt="Product">
                </a>
            </div>
            <button class="banner-arrow next" id="banner-next"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="banner-specs-row">
            <div class="banner-spec-item"><span class="label">Khổ làm việc</span><span class="value" data-spec="size">{{ $bannerProducts->first()['specs']['size'] }}</span></div>
            <div class="banner-spec-item"><span class="label">Tốc độ in</span><span class="value" data-spec="speed">{{ $bannerProducts->first()['specs']['speed'] }}</span></div>
            <div class="banner-spec-item"><span class="label">Độ chính xác</span><span class="value" data-spec="precision">{{ $bannerProducts->first()['specs']['precision'] }}</span></div>
            <div class="banner-spec-item"><span class="label">Giá tham khảo</span><span class="value" data-spec="price">{{ $bannerProducts->first()['specs']['price'] }}</span></div>
        </div>
        <div class="banner-actions">
            <a href="{{ route('contact') }}" class="btn-cta primary">ĐẶT HÀNG NGAY</a>
            <a href="{{ $bannerProducts->first()['link'] }}" id="banner-link" class="btn-cta secondary">XEM CHI TIẾT</a>
        </div>
        <div class="banner-dots">
            @foreach($bannerProducts as $index => $item)
                <button class="nav-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></button>
            @endforeach
        </div>
    </div>
</div>
<script>window.__homeBannerData = @json($bannerProducts);</script>
@endif

<section id="new-products" class="container" style="padding: 5px 1.5rem;">
    <div class="section-header"><h2 class="section-title">Sản Phẩm Mới Nhất</h2></div>
    <div class="slider-wrapper">
        <button class="slider-btn prev-btn" data-target="product-grid-new"><i class="fas fa-chevron-left"></i></button>
        <div class="product-grid" id="product-grid-new">
            @foreach(($newProducts ?? collect()) as $product)
                <article class="product-card clickable-card" data-card-link="{{ route('products.show', $product->slug) }}">
                    <div class="product-img">
                        @if($product->display_image_url)
                            <img src="{{ $product->display_image_url }}" alt="{{ $product->name }}">
                        @endif
                        @if($product->is_new)<span class="badge-new">Mới</span>@endif
                        @if($product->is_hot)<span class="badge-hot">Hot</span>@endif
                        @if($product->is_exclusive)<span class="badge-exclusive">Exclusive</span>@endif
                    </div>
                    <div class="product-info">
                        <div class="product-cat">{{ $product->category?->name }}</div>
                        <h3 class="product-name"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
                        <div class="product-footer">
                            <div class="price-box">
                                <span class="product-price">{{ $product->price ?: 'Liên hệ' }}</span>
                                <span class="product-code">Mã: {{ $product->code ?: $product->sku }}</span>
                            </div>
                            <div class="product-actions"><a href="{{ route('products.show', $product->slug) }}" class="btn-detail">Chi tiết</a></div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <button class="slider-btn next-btn" data-target="product-grid-new"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>

<section class="service-banner-section">
    <div class="container">
        <div class="service-banner-content">
            <div class="service-text-side">
                <h2 class="service-title">{{ $siteContent['home_service_title'] ?? '' }}</h2>
                <p class="service-description">{{ $siteContent['home_service_description'] ?? '' }}</p>
                <div class="service-actions">
                    <a href="{{ route('contact') }}" class="btn-service primary">{{ $siteContent['home_service_primary_cta'] ?? '' }}</a>
                    <a href="{{ route('about') }}" class="btn-service secondary">{{ $siteContent['home_service_secondary_cta'] ?? '' }}</a>
                </div>
            </div>
            <div class="service-image-side">
                <img src="{{ asset('assets/frontend/images/service-machine.png') }}" alt="Service & Warranty">
            </div>
        </div>
    </div>
</section>

<section class="faq-section">
    <div class="section-header"><h2 class="section-title">Câu Hỏi Thường Gặp</h2></div>
    <div class="container">
        <div class="faq-grid">
            @foreach($homeFaqs as $faq)
                <div class="faq-item"><div class="faq-question">{{ $faq['question'] ?? '' }}<i class="fas fa-chevron-down"></i></div><div class="faq-answer">{{ $faq['answer'] ?? '' }}</div></div>
            @endforeach
        </div>
    </div>
</section>
@endsection
