@extends('front.layouts.app')

@section('content')
@php($resolveProductSpecs = function ($product) {
    if (! $product) {
        return collect();
    }

    $specs = collect();

    if ($product->relationLoaded('specs') && $product->specs->isNotEmpty()) {
        $specs = $product->specs->map(fn ($spec) => [
            'key' => trim((string) $spec->key),
            'value' => trim((string) $spec->value),
        ]);
    }

    if ($specs->isEmpty() && is_array($product->specifications) && ! empty($product->specifications)) {
        $specs = collect($product->specifications)->map(function ($value, $key) {
            if (is_array($value)) {
                return [
                    'key' => trim((string) ($value['key'] ?? (is_string($key) ? $key : ''))),
                    'value' => trim((string) ($value['value'] ?? '')),
                ];
            }

            return [
                'key' => is_string($key) ? trim($key) : '',
                'value' => trim((string) $value),
            ];
        });
    }

    return $specs
        ->filter(fn ($spec) => filled($spec['key'] ?? null) || filled($spec['value'] ?? null))
        ->values();
})
@php($resolveAssetUrl = function ($path, $fallback = null) {
    if (! is_string($path) || trim($path) === '') {
        return $fallback;
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    if (str_starts_with($path, 'assets/')) {
        return asset($path);
    }

    return \Illuminate\Support\Facades\Storage::url($path);
})
@php($resolveOptimizedAssetUrl = function ($path, $fallback = null) use ($resolveAssetUrl) {
    if (! is_string($path) || trim($path) === '') {
        return $fallback;
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
    if (! is_string($webpPath)) {
        return $resolveAssetUrl($path, $fallback);
    }

    if (str_starts_with($path, 'assets/')) {
        return is_file(public_path($webpPath)) ? asset($webpPath) : $resolveAssetUrl($path, $fallback);
    }

    return is_file(storage_path('app/public/' . ltrim($webpPath, '/')))
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($webpPath)
        : $resolveAssetUrl($path, $fallback);
})
@php($highlightProduct = $highlightProduct ?? null)
@php($highlightSpecs = $resolveProductSpecs($highlightProduct))
@php($highlightDescription = $highlightProduct ? ($highlightProduct->long_description ?: $highlightProduct->short_description) : '')
@php($renderProductDescription = function ($content) {
    $content = trim((string) $content);

    if ($content === '') {
        return '';
    }

    $content = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $content);

    return $content !== strip_tags($content)
        ? preg_replace('/(?<!>)\R(?!<)/u', '<br>', $content)
        : nl2br(e($content), false);
})
@php($highlightDescriptionHtml = $renderProductDescription($highlightDescription))
@php($homeFaqs = collect($siteContent['home_faqs'] ?? [])->take(6))
@php($serviceImageUrl = $resolveOptimizedAssetUrl($siteContent['home_service_image'] ?? null, asset('assets/frontend/images/anh3.webp')))
@php($bannerProducts = ($bannerSwitcherProducts ?? collect())->values()->map(function ($item) use ($resolveProductSpecs, $resolveOptimizedAssetUrl) {
    $productSpecs = $resolveProductSpecs($item);
    $bannerSpecs = $productSpecs->map(fn ($spec) => [
        'label' => trim((string) ($spec['key'] ?? '')),
        'value' => trim((string) ($spec['value'] ?? '')),
    ])->filter(fn ($spec) => filled($spec['label']) || filled($spec['value']))->values();

    if ($bannerSpecs->isEmpty()) {
        $bannerSpecs = collect([[
            'label' => 'Giá tham khảo',
            'value' => $item->price ?: 'Liên hệ',
        ]]);
    }

    return [
        'name' => $item->name,
        'code' => $item->code ?: $item->sku ?: $item->name,
        'image' => $resolveOptimizedAssetUrl($item->image ?: $item->thumbnail ?: null, $item->display_image_url),
        'link' => route('products.show', $item->slug),
        'specs' => $bannerSpecs,
    ];
}))
@push('preload_assets')
    @if(($sliders ?? collect())->first()?->image_url)
        <link rel="preload" as="image" href="{{ $resolveOptimizedAssetUrl(($sliders ?? collect())->first()->image, ($sliders ?? collect())->first()->image_url) }}" fetchpriority="high">
    @endif
@endpush

<section class="hero">
    <div class="hero-slider">
        @forelse(($sliders ?? collect()) as $index => $slider)
            <div
                class="hero-slide {{ $index === 0 ? 'active' : '' }} {{ $slider->show_overlay ? '' : 'no-overlay' }}"
                data-link="{{ $slider->link }}"
            >
                <img
                    src="{{ $resolveOptimizedAssetUrl($slider->image, $slider->image_url) }}"
                    alt="{{ $slider->title ?? 'Ảnh trình chiếu' }}"
                    width="{{ $slider->width ?: 1920 }}"
                    height="{{ $slider->height ?: 900 }}"
                    @if($index === 0)
                        fetchpriority="high"
                        loading="eager"
                    @else
                        loading="lazy"
                    @endif
                    decoding="async"
                >
            </div>
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
        <button class="hero-arrow prev-hero" id="hero-prev" type="button" aria-label="Slide trước"><i class="fas fa-chevron-left" aria-hidden="true"></i></button>
        <button class="hero-arrow next-hero" id="hero-next" type="button" aria-label="Slide tiếp theo"><i class="fas fa-chevron-right" aria-hidden="true"></i></button>
    @endif
</section>

@if($highlightProduct)
<section class="special-product-section">
    <div class="container">
        <div class="special-product-container">
            <div class="special-product-image">
                @if($highlightProduct->display_image_url)
                    <img src="{{ $resolveOptimizedAssetUrl($highlightProduct->image ?: $highlightProduct->thumbnail ?: null, $highlightProduct->display_image_url) }}" alt="{{ $highlightProduct->name }}" loading="lazy" decoding="async" width="720" height="540">
                @endif
                @if($highlightProduct->video_id)
                    <button class="video-container video-lite" type="button" data-youtube-id="{{ $highlightProduct->video_id }}" aria-label="Phát video {{ $highlightProduct->name }}">
                        <img src="https://i.ytimg.com/vi/{{ $highlightProduct->video_id }}/hqdefault.jpg" alt="" loading="lazy" decoding="async">
                        <span class="video-play" aria-hidden="true"><i class="fas fa-play"></i></span>
                    </button>
                @endif
            </div>
            <div class="special-product-content">
                <span class="special-tag">Sản phẩm đột phá</span>
                <h2 class="special-title">{{ $highlightProduct->name }}</h2>
                <span class="special-code">Mã SP: {{ $highlightProduct->code ?: $highlightProduct->sku }}</span>
                <div class="special-price"><i class="fas fa-tag"></i> Giá: {{ $highlightProduct->price ?: 'Liên hệ' }}</div>
                @if($highlightDescriptionHtml !== '')
                    <div class="page-rich-content product-rich-content special-description">{!! $highlightDescriptionHtml !!}</div>
                @endif
                <div class="special-specs-grid">
                    @foreach($highlightSpecs->take(8) as $spec)
                        <div class="spec-item"><span class="spec-label">{{ $spec['key'] }}</span><span class="spec-value">{{ $spec['value'] }}</span></div>
                    @endforeach
                </div>
                <div class="contact-box">
                    <a class="contact-btn highlight" href="tel:{{ preg_replace('/\D+/', '', $siteContent['home_highlight_contact_primary_phone'] ?? '0902806599') }}">
                        <i class="fas fa-phone"></i>
                        <span>{{ $siteContent['home_highlight_contact_primary_phone'] ?? '0902 806 599' }} ({{ $siteContent['home_highlight_contact_primary_name'] ?? 'Anh Sáng' }})</span>
                    </a>
                    <a class="contact-btn" href="tel:{{ preg_replace('/\D+/', '', $siteContent['home_highlight_contact_secondary_phone'] ?? '0898303287') }}">
                        <i class="fas fa-phone"></i>
                        <span>{{ $siteContent['home_highlight_contact_secondary_phone'] ?? '0898 303 287' }} ({{ $siteContent['home_highlight_contact_secondary_name'] ?? 'Anh Bảo' }})</span>
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
        <div class="banner-watermark" id="banner-watermark">{{ $bannerProducts->first()['code'] }}</div>
        <div class="banner-main">
            <button class="banner-arrow prev" id="banner-prev" type="button" aria-label="Sản phẩm trước"><i class="fas fa-chevron-left" aria-hidden="true"></i></button>
            <div class="banner-image-box">
                <a href="{{ $bannerProducts->first()['link'] }}" id="banner-img-link">
                    <img src="{{ $bannerProducts->first()['image'] ?: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==' }}" id="banner-img" alt="Sản phẩm" loading="lazy" decoding="async" width="640" height="480">
                </a>
            </div>
            <button class="banner-arrow next" id="banner-next" type="button" aria-label="Sản phẩm tiếp theo"><i class="fas fa-chevron-right" aria-hidden="true"></i></button>
        </div>
        <div class="banner-specs-row" id="banner-specs-row">
            @foreach($bannerProducts->first()['specs'] as $spec)
                <div class="banner-spec-item">
                    @if(filled($spec['label']))
                        <span class="label">{{ $spec['label'] }}</span>
                    @endif
                    <span class="value">{{ $spec['value'] ?: '-' }}</span>
                </div>
            @endforeach
        </div>
        <div class="banner-actions">
            <a href="{{ route('contact') }}" class="btn-cta primary">ĐẶT HÀNG NGAY</a>
            <a href="{{ $bannerProducts->first()['link'] }}" id="banner-link" class="btn-cta secondary">XEM CHI TIẾT</a>
        </div>
        <div class="banner-dots">
            @foreach($bannerProducts as $index => $item)
                <button class="nav-dot {{ $index === 0 ? 'active' : '' }}" type="button" data-index="{{ $index }}" aria-label="Xem sản phẩm {{ $index + 1 }}"></button>
            @endforeach
        </div>
    </div>
</div>
<script>window.__homeBannerData = @json($bannerProducts);</script>
@endif

<section id="new-products" class="container" style="padding: 5px 1.5rem;">
    <div class="section-header"><h2 class="section-title">Sản phẩm mới nhất</h2></div>
    <div class="slider-wrapper">
        <button class="slider-btn prev-btn" type="button" data-target="product-grid-new" aria-label="Sản phẩm mới trước"><i class="fas fa-chevron-left" aria-hidden="true"></i></button>
        <div class="product-grid" id="product-grid-new">
            @foreach(($newProducts ?? collect()) as $product)
                <article class="product-card clickable-card" data-card-link="{{ route('products.show', $product->slug) }}">
                    <div class="product-img">
                        @if($product->display_image_url)
                            <img src="{{ $resolveOptimizedAssetUrl($product->image ?: $product->thumbnail ?: null, $product->display_image_url) }}" alt="{{ $product->name }}" loading="lazy" decoding="async" width="420" height="315">
                        @endif
                        <span class="badge-installment">Trả góp {{ max(0, (int) $product->installment_percent) }}%</span>
                        @if(((int) $product->discount_percent) > 0)
                            <span class="badge-discount-ribbon">-{{ (int) $product->discount_percent }}%</span>
                        @endif
                        @if($product->is_new)<span class="badge-new">Mới</span>@endif
                        @if($product->is_hot)<span class="badge-hot">Nổi bật</span>@endif
                        @if($product->is_exclusive)<span class="badge-exclusive">Độc quyền</span>@endif
                    </div>
                    <div class="product-info">
                        <div class="product-cat">{{ $product->category?->name }}</div>
                        <h3 class="product-name"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
                        @php($cardFeatures = collect(preg_split('/\r\n|\r|\n|<br\s*\/?>/i', strip_tags((string) ($product->short_description ?: $product->long_description))))->map(fn($line) => trim($line))->filter()->take(2))
                        @if($cardFeatures->isNotEmpty())
                            <ul class="product-features product-features-lined">
                                @foreach($cardFeatures as $feature)
                                    <li><i class="fas fa-check" aria-hidden="true"></i><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        @endif
                        @php($cardSpecs = $resolveProductSpecs($product)->take(2))
                        @if($cardSpecs->isNotEmpty())
                            <div class="product-specs-mini product-specs-middle">
                                @foreach($cardSpecs as $spec)
                                    <div class="spec">
                                        <i class="fas fa-circle-check" aria-hidden="true"></i>
                                        <span>{{ $spec['key'] }}: {{ $spec['value'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
        <button class="slider-btn next-btn" type="button" data-target="product-grid-new" aria-label="Sản phẩm mới tiếp theo"><i class="fas fa-chevron-right" aria-hidden="true"></i></button>
    </div>
</section>

@foreach(($homeProductRows ?? collect()) as $homeProductRow)
    @php($rowProducts = $homeProductRow->getRelation('products'))
    @php($buttonText = data_get($homeProductRow->style_config, 'button_text') ?: 'Xem thêm')
    @php($buttonUrl = data_get($homeProductRow->style_config, 'button_url') ?: route('products.index'))
    @php($showButton = filter_var(data_get($homeProductRow->style_config, 'show_button', true), FILTER_VALIDATE_BOOLEAN))
    @php($rowHeading = trim((string) ($homeProductRow->title ?: $homeProductRow->content ?: $homeProductRow->subtitle ?: '')))
    @php($usesHighlightClass = str_contains(' ' . trim((string) $homeProductRow->container_class) . ' ', ' home-row-highlight '))
    @php($rowClasses = trim(implode(' ', array_filter(['home-product-row', 'align-equal', 'linehome__item', $homeProductRow->container_class, $homeProductRow->spacing_top, $homeProductRow->spacing_bottom]))))
    @php($rowStyles = trim(implode('; ', array_filter([
        ! $usesHighlightClass && $homeProductRow->bg_color ? 'background-color: ' . $homeProductRow->bg_color : null,
        $homeProductRow->text_color ? 'color: ' . $homeProductRow->text_color : null,
    ]))))
    <section class="{{ $rowClasses }}" @if($rowStyles) style="{{ $rowStyles }}" @endif>
        <div class="container">
            @if($rowHeading !== '')
                <div class="home-product-row-heading linehome__item__header">
                    <span>{{ $rowHeading }}</span>
                </div>
            @endif
            <div class="home-product-row-inner">
                @if($homeProductRow->image_url)
                    <div class="home-product-row-banner col-inner">
                        <img src="{{ $homeProductRow->image_url }}" alt="{{ $homeProductRow->title ?: 'Danh mục sản phẩm' }}" loading="lazy" decoding="async">
                    </div>
                @endif
                <div class="home-product-row-products col-inner">
                    <div class="home-product-row-grid">
                        @foreach($rowProducts as $product)
                            <article class="home-product-row-card product-card clickable-card" data-card-link="{{ route('products.show', $product->slug) }}">
                                <div class="home-product-row-img">
                                    @if($product->display_image_url)
                                        <img src="{{ $resolveOptimizedAssetUrl($product->image ?: $product->thumbnail ?: null, $product->display_image_url) }}" alt="{{ $product->name }}" loading="lazy" decoding="async" width="320" height="240">
                                    @endif
                                    <span class="badge-installment">Trả góp {{ max(0, (int) $product->installment_percent) }}%</span>
                                    @if(((int) $product->discount_percent) > 0)
                                        <span class="badge-discount-ribbon">-{{ (int) $product->discount_percent }}%</span>
                                    @endif
                                </div>
                                <h3 class="home-product-row-name">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <div class="home-product-row-price">
                                    @if($product->formatted_price)
                                        @if(((int) $product->discount_percent) > 0 && $product->formatted_discounted_price && $product->formatted_discounted_price !== $product->formatted_price)
                                            <span class="home-product-row-price-old">{{ $product->formatted_price }}</span>
                                            <span class="home-product-row-price-current">{{ $product->formatted_discounted_price }}</span>
                                        @else
                                            <span class="home-product-row-price-current">{{ $product->formatted_price }}</span>
                                        @endif
                                    @else
                                        <span class="home-product-row-price-current">Liên hệ</span>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                    @if($showButton)
                        <a href="{{ $buttonUrl }}" class="home-product-row-more">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                            <span>{{ $buttonText }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endforeach

<section class="service-banner-section" style="background-image: url('{{ $serviceImageUrl }}');">
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
        </div>
    </div>
</section>

<section class="faq-section">
    <div class="section-header"><h2 class="section-title">Câu hỏi thường gặp</h2></div>
    <div class="container">
        <div class="faq-grid">
            @foreach($homeFaqs as $faq)
                <div class="faq-item"><div class="faq-question">{{ $faq['question'] ?? '' }}<i class="fas fa-chevron-down"></i></div><div class="faq-answer">{!! nl2br(e($faq['answer'] ?? '')) !!}</div></div>
            @endforeach
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
                        @if($partner->url)
                            <a href="{{ $partner->url }}" class="partner-logo" target="_blank" rel="noopener noreferrer">
                                <img src="{{ $resolveOptimizedAssetUrl($partner->logo, $partner->logo_url) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" width="180" height="90">
                            </a>
                        @else
                            <span class="partner-logo" role="img" aria-label="{{ $partner->name }}">
                                <img src="{{ $resolveOptimizedAssetUrl($partner->logo, $partner->logo_url) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" width="180" height="90">
                            </span>
                        @endif
                    @endif
                @endforeach
            </div>
            <div class="marquee-group">
                @foreach(($partners ?? collect()) as $partner)
                    @if($partner->logo_url)
                        @if($partner->url)
                            <a href="{{ $partner->url }}" class="partner-logo" target="_blank" rel="noopener noreferrer">
                                <img src="{{ $resolveOptimizedAssetUrl($partner->logo, $partner->logo_url) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" width="180" height="90">
                            </a>
                        @else
                            <span class="partner-logo" role="img" aria-label="{{ $partner->name }}">
                                <img src="{{ $resolveOptimizedAssetUrl($partner->logo, $partner->logo_url) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" width="180" height="90">
                            </span>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
        <div class="marquee-track scroll-right">
            <div class="marquee-group">
                @foreach(($partners ?? collect()) as $partner)
                    @if($partner->logo_url)
                        @if($partner->url)
                            <a href="{{ $partner->url }}" class="partner-logo" target="_blank" rel="noopener noreferrer">
                                <img src="{{ $resolveOptimizedAssetUrl($partner->logo, $partner->logo_url) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" width="180" height="90">
                            </a>
                        @else
                            <span class="partner-logo" role="img" aria-label="{{ $partner->name }}">
                                <img src="{{ $resolveOptimizedAssetUrl($partner->logo, $partner->logo_url) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" width="180" height="90">
                            </span>
                        @endif
                    @endif
                @endforeach
            </div>
            <div class="marquee-group">
                @foreach(($partners ?? collect()) as $partner)
                    @if($partner->logo_url)
                        @if($partner->url)
                            <a href="{{ $partner->url }}" class="partner-logo" target="_blank" rel="noopener noreferrer">
                                <img src="{{ $resolveOptimizedAssetUrl($partner->logo, $partner->logo_url) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" width="180" height="90">
                            </a>
                        @else
                            <span class="partner-logo" role="img" aria-label="{{ $partner->name }}">
                                <img src="{{ $resolveOptimizedAssetUrl($partner->logo, $partner->logo_url) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" width="180" height="90">
                            </span>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
@include('front.partials.newsletter-signup')
@endsection
