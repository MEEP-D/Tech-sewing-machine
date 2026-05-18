@extends('front.layouts.app')

@section('content')
<section class="special-product-section">
    <div class="container">
        <div class="special-product-container">
            @php
                $toText = static function (mixed $value, string $default = ''): string {
                    if (is_string($value) || is_numeric($value)) {
                        $text = trim((string) $value);
                        return $text !== '' ? $text : $default;
                    }

                    if (is_array($value)) {
                        $flat = collect($value)
                            ->flatten()
                            ->filter(fn ($item) => is_string($item) || is_numeric($item))
                            ->map(fn ($item) => trim((string) $item))
                            ->filter()
                            ->implode(', ');

                        return $flat !== '' ? $flat : $default;
                    }

                    return $default;
                };

                $galleryImages = collect();

                if ($product->display_image_url) {
                    $galleryImages->push($product->display_image_url);
                }

                foreach ((array) ($product->gallery ?? []) as $galleryPath) {
                    if (!is_string($galleryPath) || $galleryPath === '') {
                        continue;
                    }

                    if (str_starts_with($galleryPath, 'http://') || str_starts_with($galleryPath, 'https://')) {
                        $galleryImages->push($galleryPath);
                    } elseif (str_starts_with($galleryPath, '/')) {
                        $galleryImages->push(asset(ltrim($galleryPath, '/')));
                    } else {
                        $galleryImages->push(\Illuminate\Support\Facades\Storage::disk('public')->url($galleryPath));
                    }
                }

                $galleryImages = $galleryImages->filter()->unique()->values();
                $mainImage = $galleryImages->first();
            @endphp

            <div class="special-product-image">
                @if($mainImage)
                    <button type="button" class="product-main-image-btn" id="productMainImageBtn" aria-label="Phong lon anh san pham">
                        <img id="productMainImage" src="{{ $mainImage }}" alt="{{ $product->name }}">
                    </button>
                @endif

                @if($galleryImages->count() > 1)
                    <div class="product-gallery-thumbs" id="productGalleryThumbs">
                        @foreach($galleryImages as $index => $imageUrl)
                            <button
                                type="button"
                                class="product-thumb-btn {{ $index === 0 ? 'active' : '' }}"
                                data-image="{{ $imageUrl }}"
                                aria-label="Anh thu {{ $index + 1 }}"
                            >
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }} - anh {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                @endif

                @if($product->video_id)
                    <div class="video-container"><iframe src="https://www.youtube.com/embed/{{ $product->video_id }}" allowfullscreen></iframe></div>
                @endif
            </div>
            <div class="special-product-content">
                <span class="special-tag">Chi tiet san pham</span>
                <h1 class="special-title">{{ $product->name }}</h1>
                <span class="special-code">Ma: {{ $product->code ?: $product->sku }}</span>
                <div class="special-price"><i class="fas fa-tag"></i> Gia: {{ $product->price ?: 'Lien he' }}</div>
                <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin:.7rem 0 0;">
                    <span style="display:inline-flex;align-items:center;background:#d92d20;color:#fff;font-weight:700;border-radius:999px;padding:.32rem .72rem;font-size:.82rem;">Tra gop {{ max(0, (int) $product->installment_percent) }}%</span>
                    @if(((int) $product->discount_percent) > 0)
                        <span style="display:inline-flex;align-items:center;background:#0f172a;color:#fff;font-weight:700;border-radius:999px;padding:.32rem .72rem;font-size:.82rem;">Giam gia {{ (int) $product->discount_percent }}%</span>
                    @endif
                </div>
                <p class="special-description">{{ $product->short_description }}</p>

                <div class="product-support-block">
                    <p class="product-support-title">{{ $product->support_prompt ?: 'Ban can ho tro thong tin gi ve san pham nay?' }}</p>
                    <div class="product-support-actions">
                        <a href="{{ $product->cta_primary_url ?: route('contact') }}" class="product-support-btn primary">
                            {{ $product->cta_primary_label ?: 'Ban can ho tro thong tin gi ve san pham nay?' }}
                        </a>
                        <a href="{{ $product->cta_secondary_url ?: route('products.index') }}" class="product-support-btn secondary">
                            {{ $product->cta_secondary_label ?: 'Kham pha cac mau theu mien phi tai day' }}
                        </a>
                    </div>

                    <div class="product-support-sections">
                        <section class="product-support-section">
                            <h3>{{ $product->overview_heading ?: 'Tong quan ve san pham' }}</h3>
                            <div>{!! $product->overview_content ?: ($product->long_description ?: '<p>Noi dung dang cap nhat.</p>') !!}</div>
                        </section>
                        <section class="product-support-section">
                            <h3>{{ $product->seo_heading ?: 'Tim hieu ve may lam seo' }}</h3>
                            <div>{!! $product->seo_content ?: '<p>Noi dung dang cap nhat.</p>' !!}</div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        @php
            $productSpecs = $product->relationLoaded('specs') ? $product->specs : collect();
        @endphp
        <div class="product-detail-tabs">
            <div class="detail-tab-nav">
                <button class="detail-tab-btn active" type="button" data-target="tab-info">Thong tin san pham</button>
                <button class="detail-tab-btn" type="button" data-target="tab-specs">Thong so ky thuat</button>
                <button class="detail-tab-btn" type="button" data-target="tab-warranty">Chinh sach bao hanh</button>
                <button class="detail-tab-btn" type="button" data-target="tab-review">Danh gia</button>
                <button class="detail-tab-btn" type="button" data-target="tab-comment">Binh luan</button>
            </div>

            <div class="detail-outline-box">
                <strong>Muc luc</strong>
            </div>

            <div class="detail-tab-content active" id="tab-info">
                <h3>Dac diem</h3>
                <div>{!! $product->long_description ?: $product->description !!}</div>
            </div>

            <div class="detail-tab-content" id="tab-specs">
                @if($productSpecs->isNotEmpty())
                    <div class="special-specs-grid" style="margin-top: 1rem;">
                        @foreach($productSpecs as $spec)
                            <div class="spec-item"><span class="spec-label">{{ $spec->key }}</span><span class="spec-value">{{ $spec->value }}</span></div>
                        @endforeach
                    </div>
                @elseif(!empty($product->specifications))
                    <div class="special-specs-grid" style="margin-top: 1rem;">
                        @foreach((array) $product->specifications as $key => $value)
                            <div class="spec-item"><span class="spec-label">{{ $toText($key, '-') }}</span><span class="spec-value">{{ $toText($value, '-') }}</span></div>
                        @endforeach
                    </div>
                @else
                    <p>Thong so dang cap nhat.</p>
                @endif
            </div>

            <div class="detail-tab-content" id="tab-warranty">
                <p>San pham duoc bao hanh theo chinh sach cua nha cung cap. Vui long lien he de nhan thong tin bao hanh chi tiet va huong dan ky thuat.</p>
            </div>

            <div class="detail-tab-content" id="tab-review">
                <p>Danh gia dang duoc cap nhat.</p>
            </div>

            <div class="detail-tab-content" id="tab-comment">
                <p>Binh luan dang duoc cap nhat.</p>
            </div>
        </div>

        @php
            $initialRelatedVisible = 4;
            $hiddenRelatedCount = max($relatedProducts->count() - $initialRelatedVisible, 0);
        @endphp
        @if($relatedProducts->isNotEmpty())
            <section class="related-products-block" id="relatedProductsBlock">
                <div class="section-header related-products-header">
                    <h2 class="section-title">San pham cung loai hoac tuong tu</h2>
                </div>

                <div class="related-products-grid" id="relatedProductsGrid">
                    @foreach($relatedProducts as $index => $relatedProduct)
                    <article class="product-card catalog-card related-product-card clickable-card {{ $index >= $initialRelatedVisible ? 'is-hidden' : '' }}" data-card-link="{{ route('products.show', $relatedProduct->slug) }}">
                <div class="product-img">
                    @if($relatedProduct->display_image_url)
                        <img src="{{ $relatedProduct->display_image_url }}" alt="{{ $relatedProduct->name }}">
                    @endif
                    <span class="badge-installment">Tra gop {{ max(0, (int) $relatedProduct->installment_percent) }}%</span>
                    @if(((int) $relatedProduct->discount_percent) > 0)
                        <span class="badge-discount-ribbon">-{{ (int) $relatedProduct->discount_percent }}%</span>
                    @endif
                </div>
                <div class="product-info">
                    <h3 class="product-name">
                        <a href="{{ route('products.show', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a>
                    </h3>
                    <div class="product-price">{{ $relatedProduct->price ?: 'Lien he' }}</div>
                    <div class="product-meta">Ma SP: {{ $relatedProduct->code ?: ($relatedProduct->sku ?: 'Dang cap nhat') }}</div>
                    @if($relatedProduct->short_description)
                        <p class="product-snippet">{{ \Illuminate\Support\Str::limit(strip_tags($relatedProduct->short_description), 120) }}</p>
                    @endif
                    <div class="product-cat">{{ optional($relatedProduct->category)->name }}</div>
                    <div class="product-actions catalog-actions">
                        <a href="{{ route('contact') }}" class="btn-detail btn-buy">Lien he</a>
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn-detail btn-outline">Xem chi tiet</a>
                    </div>
                </div>
            </article>
                    @endforeach
                </div>

                @if($hiddenRelatedCount > 0)
                    <div class="related-products-actions">
                        <button
                            type="button"
                            class="btn-detail btn-outline related-products-toggle"
                            id="relatedProductsToggle"
                            data-expand-text="Xem them {{ $hiddenRelatedCount }} san pham"
                            data-collapse-text="Thu gon"
                        >
                            Xem them {{ $hiddenRelatedCount }} san pham
                        </button>
                    </div>
                @endif
            </section>
        @endif
    </div>
</section>

<div class="product-lightbox" id="productLightbox" aria-hidden="true">
    <button type="button" class="product-lightbox-close" id="productLightboxClose" aria-label="Dong">&times;</button>
    <img id="productLightboxImage" src="" alt="Anh san pham phong lon">
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.product-detail-tabs');
    if (container) {
        const buttons = container.querySelectorAll('.detail-tab-btn');
        const tabs = container.querySelectorAll('.detail-tab-content');

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-target');

                buttons.forEach((btn) => btn.classList.remove('active'));
                tabs.forEach((tab) => tab.classList.remove('active'));

                button.classList.add('active');
                const activeTab = container.querySelector('#' + target);
                if (activeTab) activeTab.classList.add('active');
            });
        });
    }

    const mainImage = document.getElementById('productMainImage');
    const thumbWrap = document.getElementById('productGalleryThumbs');
    const lightbox = document.getElementById('productLightbox');
    const lightboxImage = document.getElementById('productLightboxImage');
    const lightboxClose = document.getElementById('productLightboxClose');
    const mainImageBtn = document.getElementById('productMainImageBtn');

    if (mainImage && thumbWrap) {
        thumbWrap.querySelectorAll('.product-thumb-btn').forEach((thumbBtn) => {
            thumbBtn.addEventListener('click', () => {
                const nextImage = thumbBtn.getAttribute('data-image');
                if (!nextImage) return;

                mainImage.src = nextImage;
                if (lightboxImage) {
                    lightboxImage.src = nextImage;
                }

                thumbWrap.querySelectorAll('.product-thumb-btn').forEach((btn) => btn.classList.remove('active'));
                thumbBtn.classList.add('active');
            });
        });
    }

    const openLightbox = () => {
        if (!mainImage || !lightbox || !lightboxImage) return;
        lightboxImage.src = mainImage.src;
        lightbox.classList.add('open');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.classList.add('no-scroll');
    };

    const closeLightbox = () => {
        if (!lightbox) return;
        lightbox.classList.remove('open');
        lightbox.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('no-scroll');
    };

    if (mainImageBtn) {
        mainImageBtn.addEventListener('click', openLightbox);
    }

    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }

    if (lightbox) {
        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeLightbox();
        }
    });

    const relatedToggle = document.getElementById('relatedProductsToggle');
    if (relatedToggle) {
        relatedToggle.addEventListener('click', () => {
            const hiddenCards = document.querySelectorAll('.related-product-card.is-hidden');
            const isExpanded = relatedToggle.classList.contains('expanded');

            if (isExpanded) {
                hiddenCards.forEach((card) => card.classList.remove('show'));
                relatedToggle.classList.remove('expanded');
                relatedToggle.textContent = relatedToggle.getAttribute('data-expand-text') || 'Xem them';
            } else {
                hiddenCards.forEach((card) => card.classList.add('show'));
                relatedToggle.classList.add('expanded');
                relatedToggle.textContent = relatedToggle.getAttribute('data-collapse-text') || 'Thu gon';
            }
        });
    }
});
</script>
@include('front.partials.newsletter-signup')
@endsection
