@php
    $media = $media ?? app(\App\Support\OptimizedMedia::class);
    $heroProducts = collect($heroProducts ?? [])->filter()->take(10)->values();
    $productsHeroTitle = $siteContent['page_products_heading'] ?? 'Sản phẩm';
    $productsHeroDescription = $siteContent['page_products_desc'] ?? 'Giải pháp máy công nghiệp, máy lập trình và phụ kiện cho xưởng sản xuất.';
    $productsHeroKicker = $siteContent['page_products_kicker'] ?? '';
    $productsHeroActionUrl = route('products.index') . '#product-catalog';
@endphp

<section class="products-hero page-hero{{ !empty($productsHeroImage) ? ' has-custom-image' : '' }}">
    @if(!empty($productsHeroImage))
        <div class="products-hero-backdrop" aria-hidden="true" style="background-image: url('{{ $productsHeroImage }}');"></div>
    @endif
    <div class="products-hero-surface" aria-hidden="true"></div>

    <div class="products-hero-inner container{{ $heroProducts->isEmpty() ? ' is-copy-only' : '' }}">
        <div class="products-hero-copy">
            @if(!empty($productsHeroKicker))
                <div class="products-hero-kicker">{{ $productsHeroKicker }}</div>
            @endif

            <h1>{{ $productsHeroTitle }}</h1>
            <p>{{ $productsHeroDescription }}</p>

            <a href="{{ $productsHeroActionUrl }}" class="products-hero-cta">
                <i class="fas fa-bag-shopping" aria-hidden="true"></i>
                <span>Xem tất cả sản phẩm</span>
            </a>
        </div>

        @if($heroProducts->isNotEmpty())
            <div class="products-hero-showcase" aria-label="Sản phẩm mới nhất" style="--hero-item-count: {{ $heroProducts->count() }};">
                <div class="products-hero-marquee-row">
                    <div class="products-hero-marquee-track" style="--marquee-items: {{ $heroProducts->count() }};">
                        @foreach([false, true] as $isDuplicateGroup)
                            <div class="products-hero-marquee-group" @if($isDuplicateGroup) aria-hidden="true" @endif>
                                @foreach($heroProducts as $heroProduct)
                                    @php
                                        $heroImage = $media->url($heroProduct->display_image, ['width' => 520, 'quality' => 76]) ?? $heroProduct->display_image_url;
                                        $heroBadge = $heroProduct->is_new ? 'Mới' : ($heroProduct->is_featured ? 'Nổi bật' : 'Sản phẩm');
                                    @endphp
                                    <article class="products-hero-card">
                                        <a href="{{ route('products.show', $heroProduct->slug) }}" class="products-hero-card-link" @if($isDuplicateGroup) tabindex="-1" @endif>
                                            <div class="products-hero-card-media">
                                                <span class="products-hero-card-badge">{{ $heroBadge }}</span>
                                                @if($heroImage)
                                                    <img src="{{ $heroImage }}" alt="{{ $heroProduct->name }}" loading="lazy" decoding="async">
                                                @else
                                                    <div class="products-hero-card-placeholder" aria-hidden="true"></div>
                                                @endif
                                            </div>
                                            <div class="products-hero-card-body">
                                                <div class="products-hero-card-name">{{ \Illuminate\Support\Str::limit($heroProduct->name, 64) }}</div>
                                                <div class="products-hero-card-price">Liên hệ</div>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
