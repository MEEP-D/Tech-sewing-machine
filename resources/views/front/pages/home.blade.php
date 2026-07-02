@extends('front.layouts.app')

@section('content')
@php($media = app(\App\Support\OptimizedMedia::class))
@php($normalizeSpecKey = fn ($label) => \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii(trim((string) $label))))
@php($resolveJsonSpecs = function ($product) {
    if (! $product) {
        return collect();
    }

    if (! is_array($product->specifications) || empty($product->specifications)) {
        return collect();
    }

    return collect($product->specifications)
        ->map(function ($value, $key) {
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
        })
        ->filter(fn ($spec) => filled($spec['key'] ?? null) && filled($spec['value'] ?? null))
        ->values();
})
@php($resolveBannerSpecs = function ($product) use ($resolveJsonSpecs) {
    if (! $product) {
        return collect();
    }

    $bannerSpecs = collect();

    if ($product->relationLoaded('specs') && $product->specs->isNotEmpty()) {
        $bannerSpecs = $product->specs
            ->map(fn ($spec) => [
                'key' => trim((string) $spec->key),
                'value' => trim((string) $spec->value),
            ])
            ->filter(fn ($spec) => filled($spec['key'] ?? null) && filled($spec['value'] ?? null))
            ->values();
    }

    return $bannerSpecs->isNotEmpty() ? $bannerSpecs : $resolveJsonSpecs($product);
})
@php($resolveProductSpecs = function ($product) use ($normalizeSpecKey, $resolveJsonSpecs) {
    if (! $product) {
        return collect();
    }

    $tableSpecs = collect();

    if ($product->relationLoaded('specs') && $product->specs->isNotEmpty()) {
        $tableSpecs = $product->specs
            ->map(fn ($spec) => [
                'key' => trim((string) $spec->key),
                'value' => trim((string) $spec->value),
            ])
            ->filter(fn ($spec) => filled($spec['key'] ?? null) || filled($spec['value'] ?? null))
            ->values();
    }

    $jsonSpecs = $resolveJsonSpecs($product);

    if ($tableSpecs->isEmpty()) {
        return $jsonSpecs;
    }

    if ($jsonSpecs->isEmpty()) {
        return $tableSpecs;
    }

    $remainingJsonSpecs = $jsonSpecs->values();

    $mergedSpecs = $tableSpecs->map(function ($spec, $index) use (&$remainingJsonSpecs, $normalizeSpecKey) {
        $resolved = [
            'key' => trim((string) ($spec['key'] ?? '')),
            'value' => trim((string) ($spec['value'] ?? '')),
        ];

        $matchedIndex = $remainingJsonSpecs->search(function ($jsonSpec) use ($resolved, $normalizeSpecKey) {
            $tableKey = $normalizeSpecKey($resolved['key'] ?? '');
            $jsonKey = $normalizeSpecKey(data_get($jsonSpec, 'key', ''));

            return $tableKey !== '' && $jsonKey !== '' && $tableKey === $jsonKey;
        });

        if ($matchedIndex === false) {
            $matchedIndex = $index < $remainingJsonSpecs->count() ? $index : false;
        }

        $fallback = $matchedIndex !== false ? $remainingJsonSpecs->get($matchedIndex) : null;

        if ($matchedIndex !== false) {
            $remainingJsonSpecs->forget($matchedIndex);
            $remainingJsonSpecs = $remainingJsonSpecs->values();
        }

        return [
            'key' => $resolved['key'] !== '' ? $resolved['key'] : trim((string) data_get($fallback, 'key', '')),
            'value' => $resolved['value'] !== '' ? $resolved['value'] : trim((string) data_get($fallback, 'value', '')),
        ];
    });

    return $mergedSpecs
        ->concat($remainingJsonSpecs)
        ->filter(fn ($spec) => filled($spec['key'] ?? null) || filled($spec['value'] ?? null))
        ->values();
})
@php($resolveAssetUrl = fn ($path, $fallback = null) => $media->url($path) ?? $fallback)
@php($highlightProduct = $highlightProduct ?? null)
@php($highlightSpecs = $resolveProductSpecs($highlightProduct))
@php($isPriceSpec = function ($label) {
    $normalized = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii(trim((string) $label)));

    if ($normalized === '') {
        return false;
    }

    foreach (['gia', 'gia ban', 'gia niem yet', 'gia tham khao'] as $keyword) {
        if ($normalized === $keyword || str_contains($normalized, $keyword)) {
            return true;
        }
    }

    return false;
})
@php($highlightDescription = $highlightProduct ? ($highlightProduct->long_description ?: $highlightProduct->short_description) : '')
@php($highlightDescriptionLines = collect(preg_split('/\r\n|\r|\n|<br\s*\/?>/i', strip_tags((string) $highlightDescription)))->map(fn($line) => trim($line))->filter())
@php($homeFaqs = collect($siteContent['home_faqs'] ?? [])->take(6))
@php($serviceImageUrl = $media->url($siteContent['home_service_image'] ?? null, ['width' => 1600, 'quality' => 74]) ?? $resolveAssetUrl($siteContent['home_service_image'] ?? null, '/assets/frontend/images/anh3.jpg'))
@php($heroSlides = ($sliders ?? collect())->values()->map(function ($slider) use ($media) {
    return (object) [
        'title' => $slider->title,
        'subtitle' => $slider->subtitle,
        'link' => $slider->link,
        'show_overlay' => $slider->show_overlay,
        'show_title' => $slider->show_title,
        'show_subtitle' => $slider->show_subtitle,
        'show_button' => $slider->show_button,
        'image_url' => $media->url($slider->image, ['width' => 1600, 'quality' => 74]) ?? $slider->image_url,
    ];
}))
@php($bannerProducts = ($bannerSwitcherProducts ?? collect())->values()->map(function ($item) use ($resolveBannerSpecs, $media, $isPriceSpec) {
    $productSpecs = $resolveBannerSpecs($item);

    return [
        'name' => $item->name,
        'code' => $item->code ?: $item->sku ?: $item->name,
        'image' => $media->url($item->display_image, ['width' => 860, 'quality' => 76]) ?? $item->display_image_url,
        'link' => route('products.show', $item->slug),
        'specs' => $productSpecs
            ->map(fn ($spec) => [
                'label' => trim((string) data_get($spec, 'key', '')),
                'value' => trim((string) data_get($spec, 'value', '-')),
            ])
            ->reject(fn ($spec) => $isPriceSpec($spec['label'] ?? ''))
            ->values()
            ->all(),
        'price' => $item->formatted_discounted_price ?: $item->formatted_price ?: $item->price ?: 'Liên hệ',
    ];
}))
@push('preload_assets')
    @if(optional($heroSlides->first())->image_url)
        <link rel="preload" as="image" href="{{ $heroSlides->first()->image_url }}" fetchpriority="high">
    @endif
@endpush

<section class="hero">
    <div class="hero-slider">
        @forelse($heroSlides as $index => $slider)
            <div
                class="hero-slide {{ $index === 0 ? 'active' : '' }} {{ $slider->show_overlay ? '' : 'no-overlay' }}"
                @if($slider->image_url)
                    style="background-image: url('{{ $slider->image_url }}');"
                @endif
                aria-label="{{ $slider->title ?? 'Ảnh trình chiếu' }}"
                data-link="{{ $slider->link }}"
            ></div>
        @empty
            <div class="hero-slide active"></div>
        @endforelse
    </div>
    <div class="container">
        <div class="hero-content">
            @foreach($heroSlides as $index => $slider)
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
        @forelse($heroSlides as $index => $slider)
            <div class="dot {{ $index === 0 ? 'active' : '' }}"></div>
        @empty
            <div class="dot active"></div>
        @endforelse
    </div>
    @if($heroSlides->count() > 1)
        <button class="hero-arrow prev-hero" id="hero-prev"><i class="fas fa-chevron-left"></i></button>
        <button class="hero-arrow next-hero" id="hero-next"><i class="fas fa-chevron-right"></i></button>
    @endif
</section>

@if($highlightProduct)
<section class="special-product-section">
    <div class="container">
        <div class="special-product-container">
            <div class="special-product-image">
                @if($highlightProduct->display_image_url)
                    <img src="{{ $media->url($highlightProduct->display_image, ['width' => 960, 'quality' => 78]) ?? $highlightProduct->display_image_url }}" alt="{{ $highlightProduct->name }}" loading="lazy" decoding="async">
                @endif
                @if($highlightProduct->video_id)
                    <div class="video-container"><iframe src="https://www.youtube.com/embed/{{ $highlightProduct->video_id }}" loading="lazy" allowfullscreen></iframe></div>
                @endif
            </div>
            <div class="special-product-content">
                <span class="special-tag">Sản phẩm đột phá</span>
                <h2 class="special-title">{{ $highlightProduct->name }}</h2>
                <span class="special-code">Mã SP: {{ $highlightProduct->code ?: $highlightProduct->sku }}</span>
                <div class="special-price"><i class="fas fa-tag"></i> Giá: {{ $highlightProduct->price ?: 'Liên hệ' }}</div>
                @if($highlightDescriptionLines->isNotEmpty())
                    <ul class="description-list">
                        @foreach($highlightDescriptionLines as $line)
                            <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{{ $line }}</span></li>
                        @endforeach
                    </ul>
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
@php($initialBannerProduct = $bannerProducts->first())
<div class="banner-switcher-section" id="banner-switcher-root" data-link="{{ $bannerProducts->first()['link'] }}">
    <div class="banner-switcher-wrapper">
        <div class="banner-watermark" id="banner-watermark">{{ $bannerProducts->first()['code'] }}</div>
        <div class="banner-main">
            <button class="banner-arrow prev" id="banner-prev"><i class="fas fa-chevron-left"></i></button>
            <div class="banner-image-box">
                <a href="{{ $bannerProducts->first()['link'] }}" id="banner-img-link">
                    <img src="{{ $bannerProducts->first()['image'] ?: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==' }}" id="banner-img" alt="Sản phẩm" loading="lazy" decoding="async">
                </a>
                <div class="banner-product-code" id="banner-product-code">Mã SP: {{ $bannerProducts->first()['code'] }}</div>
            </div>
            <button class="banner-arrow next" id="banner-next"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="banner-specs-row" id="banner-specs-row" style="--banner-spec-columns: {{ max(count($initialBannerProduct['specs']) + 1, 1) }};">
            @foreach($initialBannerProduct['specs'] as $spec)
                <div class="banner-spec-item">
                    <span class="label">{{ $spec['label'] }}</span>
                    <span class="value">{{ $spec['value'] }}</span>
                </div>
            @endforeach
            <div class="banner-spec-item banner-spec-item-price">
                <span class="label">Giá tham khảo</span>
                <span class="value">{{ $initialBannerProduct['price'] }}</span>
            </div>
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
    <div class="section-header"><h2 class="section-title">Sản phẩm mới nhất</h2></div>
    <div class="slider-wrapper">
        <button class="slider-btn prev-btn" data-target="product-grid-new"><i class="fas fa-chevron-left"></i></button>
        <div class="product-grid" id="product-grid-new">
            @foreach(($newProducts ?? collect()) as $product)
                <article class="product-card clickable-card" data-card-link="{{ route('products.show', $product->slug) }}">
                    <div class="product-img">
                        @if($product->display_image_url)
                            <img src="{{ $media->url($product->display_image, ['width' => 640, 'quality' => 76]) ?? $product->display_image_url }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                        @endif
                        @if($product->installment_percent)
                            <span class="badge-installment">Khuyến mãi</span>
                        @endif
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
                        @php($cardFeatures = collect(preg_split('/\r\n|\r|\n|<br\s*\/?>/i', strip_tags((string) ($product->short_description ?: $product->long_description))))->map(fn($line) => trim($line))->filter())
                        @if($cardFeatures->isNotEmpty())
                            <ul class="product-features product-features-lined">
                                @foreach($cardFeatures as $feature)
                                    <li><i class="fas fa-check" aria-hidden="true"></i><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        @endif
                        @php($cardSpecs = $resolveProductSpecs($product))
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
        <button class="slider-btn next-btn" data-target="product-grid-new"><i class="fas fa-chevron-right"></i></button>
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
                                        <img src="{{ $media->url($product->display_image, ['width' => 560, 'quality' => 76]) ?? $product->display_image_url }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                                    @endif
                                    @if($product->installment_percent)
                                        <span class="badge-installment">Khuyến mãi</span>
                                    @endif
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

<section class="service-banner-section" @if($serviceImageUrl) data-bg="{{ $serviceImageUrl }}" style="background-image: url('{{ $serviceImageUrl }}');" @endif>
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
                        <a href="{{ $partner->url ?: 'javascript:void(0)' }}" class="partner-logo" @if($partner->url) target="_blank" rel="noopener noreferrer" @endif>
                            <img src="{{ $media->url($partner->logo, ['width' => 320, 'quality' => 80]) ?? $partner->logo_url }}" alt="{{ $partner->name }}" loading="lazy" decoding="async">
                        </a>
                    @endif
                @endforeach
            </div>
            <div class="marquee-group">
                @foreach(($partners ?? collect()) as $partner)
                    @if($partner->logo_url)
                        <a href="{{ $partner->url ?: 'javascript:void(0)' }}" class="partner-logo" @if($partner->url) target="_blank" rel="noopener noreferrer" @endif>
                            <img src="{{ $media->url($partner->logo, ['width' => 320, 'quality' => 80]) ?? $partner->logo_url }}" alt="{{ $partner->name }}" loading="lazy" decoding="async">
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
                            <img src="{{ $media->url($partner->logo, ['width' => 320, 'quality' => 80]) ?? $partner->logo_url }}" alt="{{ $partner->name }}" loading="lazy" decoding="async">
                        </a>
                    @endif
                @endforeach
            </div>
            <div class="marquee-group">
                @foreach(($partners ?? collect()) as $partner)
                    @if($partner->logo_url)
                        <a href="{{ $partner->url ?: 'javascript:void(0)' }}" class="partner-logo" @if($partner->url) target="_blank" rel="noopener noreferrer" @endif>
                            <img src="{{ $media->url($partner->logo, ['width' => 320, 'quality' => 80]) ?? $partner->logo_url }}" alt="{{ $partner->name }}" loading="lazy" decoding="async">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
@include('front.partials.newsletter-signup')
@endsection
