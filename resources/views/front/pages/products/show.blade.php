@extends('front.layouts.app')

@section('content')
<section class="special-product-section">
    <div class="container">
        <div class="special-product-container">
            @php
                $media = app(\App\Support\OptimizedMedia::class);
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

                $resolveProductImageUrl = static function (string $path) use ($media): ?string {
                    $optimizedUrl = $media->url($path, ['width' => 1280, 'quality' => 78]);

                    if ($optimizedUrl) {
                        return $optimizedUrl;
                    }

                    return str_starts_with($path, '/')
                        ? asset(ltrim($path, '/'))
                        : \Illuminate\Support\Facades\Storage::disk('public')->url($path);
                };

                $galleryImages = collect();
                $specificationImages = collect();
                $usageGuideAttachmentUrl = $product->usage_guide_attachment_url;
                $usageGuideAttachmentExtension = $product->usage_guide_attachment_extension;
                $usageGuideAttachmentName = $product->usage_guide_attachment_filename ?: 'tai-lieu-huong-dan';
                $usageGuideIsPdf = $usageGuideAttachmentExtension === 'pdf';
                $hasUsageGuide = filled($product->rendered_usage_guide_content)
                    || filled($product->usage_guide_video_id)
                    || filled($usageGuideAttachmentUrl);

                if ($product->display_image_url) {
                    $galleryImages->push($media->url($product->display_image, ['width' => 1280, 'quality' => 78]) ?? $product->display_image_url);
                }

                foreach ((array) ($product->gallery ?? []) as $galleryPath) {
                    if (! is_string($galleryPath) || $galleryPath === '') {
                        continue;
                    }

                    $galleryImages->push($resolveProductImageUrl($galleryPath));
                }

                foreach ((array) ($product->specification_images ?? []) as $specificationImagePath) {
                    if (! is_string($specificationImagePath) || $specificationImagePath === '') {
                        continue;
                    }

                    $specificationImages->push($resolveProductImageUrl($specificationImagePath));
                }

                $galleryImages = $galleryImages->filter()->unique()->values();
                $specificationImages = $specificationImages->filter()->unique()->values();
                $mainImage = $galleryImages->first();
            @endphp

            @push('preload_assets')
                @if($mainImage)
                    <link rel="preload" as="image" href="{{ $mainImage }}" fetchpriority="high">
                @endif
            @endpush

            <div class="special-product-image">
                @if($mainImage)
                    <button type="button" class="product-main-image-btn" id="productMainImageBtn" aria-label="Phóng lớn ảnh sản phẩm">
                        <img id="productMainImage" src="{{ $mainImage }}" alt="{{ $product->name }}" decoding="async" fetchpriority="high">
                    </button>
                @endif

                @if($galleryImages->count() > 1)
                    <div class="product-gallery-thumbs" id="productGalleryThumbs">
                        @foreach($galleryImages as $index => $imageUrl)
                            <button
                                type="button"
                                class="product-thumb-btn {{ $index === 0 ? 'active' : '' }}"
                                data-image="{{ $imageUrl }}"
                                aria-label="Ảnh thứ {{ $index + 1 }}"
                            >
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }} - ảnh {{ $index + 1 }}" loading="lazy" decoding="async">
                            </button>
                        @endforeach
                    </div>
                @endif

                @if($product->video_id)
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/{{ $product->video_id }}" loading="lazy" allowfullscreen title="Video sản phẩm {{ $product->name }}"></iframe>
                    </div>
                @endif
            </div>

            <div class="special-product-content">
                @php
                    $detailBadges = collect([
                        $product->installment_percent
                            ? [
                                'label' => 'Khuyến mãi',
                                'class' => 'is-installment',
                            ]
                            : null,
                        ((int) $product->discount_percent) > 0
                            ? [
                                'label' => 'Giảm giá ' . (int) $product->discount_percent . '%',
                                'class' => 'is-discount',
                            ]
                            : null,
                        $product->availability_badge_label
                            ? [
                                'label' => $product->availability_badge_label,
                                'class' => $product->availability_badge === \App\Models\Product::AVAILABILITY_BADGE_READY
                                    ? 'is-availability-ready'
                                    : 'is-availability-preorder',
                            ]
                            : null,
                    ])->filter()->values();
                @endphp

                <span class="special-tag">Chi tiết sản phẩm</span>
                <h1 class="special-title">{{ $product->name }}</h1>
                <span class="special-code">Mã: {{ $product->code ?: $product->sku }}</span>
                <div class="special-price"><i class="fas fa-tag"></i> Giá: {{ $product->price ?: 'Liên hệ' }}</div>

                @if($detailBadges->isNotEmpty())
                    <div class="product-highlight-badges">
                        @foreach($detailBadges as $badge)
                            <span class="product-highlight-badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        @endforeach
                    </div>
                @endif

                <p class="special-description">{{ $product->short_description }}</p>

                <div class="product-support-block">
                    <p class="product-support-title">{{ $product->support_prompt ?: 'Bạn cần hỗ trợ thông tin gì về sản phẩm này?' }}</p>
                    <div class="product-support-actions">
                        <a href="{{ $product->cta_primary_url ?: route('contact') }}" class="product-support-btn primary">
                            {{ $product->cta_primary_label ?: 'Bạn cần hỗ trợ thông tin gì về sản phẩm này?' }}
                        </a>
                        <a href="{{ $product->cta_secondary_url ?: route('products.index') }}" class="product-support-btn secondary">
                            {{ $product->cta_secondary_label ?: 'Khám phá các mẫu thêu miễn phí tại đây' }}
                        </a>
                    </div>

                    <div class="product-support-sections">
                        <section class="product-support-section">
                            <h3>{{ $product->overview_heading ?: 'Tổng quan về sản phẩm' }}</h3>
                            <div class="page-rich-content product-rich-content">{!! $product->rendered_overview_content ?: ($product->rendered_long_description ?: '<p>Nội dung đang cập nhật.</p>') !!}</div>
                        </section>
                        <section class="product-support-section">
                            <h3>{{ $product->seo_heading ?: 'Tìm hiểu về máy làm seo' }}</h3>
                            <div class="page-rich-content product-rich-content">{!! $product->rendered_seo_content ?: '<p>Nội dung đang cập nhật.</p>' !!}</div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        @php
            $productSpecs = $product->relationLoaded('specs') ? $product->specs : collect();
        @endphp

        <div class="product-detail-tabs">
            <div class="detail-tab-nav" role="tablist" aria-label="Chi tiết sản phẩm">
                <button class="detail-tab-btn active" type="button" id="tab-btn-info" role="tab" aria-selected="true" aria-controls="tab-info" data-target="tab-info">Thông tin sản phẩm</button>
                <button class="detail-tab-btn" type="button" id="tab-btn-specs" role="tab" aria-selected="false" aria-controls="tab-specs" data-target="tab-specs">Thông số kỹ thuật</button>
                <button class="detail-tab-btn" type="button" id="tab-btn-warranty" role="tab" aria-selected="false" aria-controls="tab-warranty" data-target="tab-warranty">Chính sách bảo hành</button>
                <button class="detail-tab-btn" type="button" id="tab-btn-guide" role="tab" aria-selected="false" aria-controls="tab-guide" data-target="tab-guide">Hướng dẫn sử dụng</button>
                <button class="detail-tab-btn" type="button" id="tab-btn-comment" role="tab" aria-selected="false" aria-controls="tab-comment" data-target="tab-comment">Bình luận</button>
            </div>

            <div class="detail-outline-box">
                <strong>Mục lục</strong>
            </div>

            <div class="detail-tab-content active is-entering" id="tab-info" role="tabpanel" aria-labelledby="tab-btn-info">
                <h3>Đặc điểm</h3>
                <div class="page-rich-content product-rich-content">{!! $product->rendered_description ?: '<p>Nội dung đang cập nhật.</p>' !!}</div>
            </div>

            <div class="detail-tab-content" id="tab-specs" role="tabpanel" aria-labelledby="tab-btn-specs">
                @php
                    $hasSpecs = $productSpecs->isNotEmpty() || ! empty($product->specifications);
                @endphp

                @if($productSpecs->isNotEmpty())
                    <div class="special-specs-grid" style="margin-top: 1rem;">
                        @foreach($productSpecs as $spec)
                            <div class="spec-item"><span class="spec-label">{{ $spec->key }}</span><span class="spec-value">{{ $spec->value }}</span></div>
                        @endforeach
                    </div>
                @elseif(! empty($product->specifications))
                    <div class="special-specs-grid" style="margin-top: 1rem;">
                        @foreach((array) $product->specifications as $key => $value)
                            <div class="spec-item"><span class="spec-label">{{ $toText($key, '-') }}</span><span class="spec-value">{{ $toText($value, '-') }}</span></div>
                        @endforeach
                    </div>
                @endif

                @if($specificationImages->isNotEmpty())
                    <div class="product-spec-gallery">
                        @foreach($specificationImages as $index => $imageUrl)
                            <figure class="product-spec-gallery-item">
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }} - thông số kỹ thuật {{ $index + 1 }}" loading="lazy" decoding="async">
                            </figure>
                        @endforeach
                    </div>
                @endif

                @if(! $hasSpecs && $specificationImages->isEmpty())
                    <p>Thông số đang cập nhật.</p>
                @endif
            </div>

            <div class="detail-tab-content" id="tab-warranty" role="tabpanel" aria-labelledby="tab-btn-warranty">
                <p>Tất cả các sản phẩm máy móc của thương hiệu smart sẽ được bảo hành 12 tháng kể từ ngày lắp đặt và không bảo hành với những linh kiện cơ khí dễ mài mòn tự nhiên do sản xuất, thiên tai và tác nhân bên ngoài.</p>
            </div>

            <div class="detail-tab-content" id="tab-guide" role="tabpanel" aria-labelledby="tab-btn-guide">
                <h3>Hướng dẫn sử dụng</h3>

                @if(filled($product->rendered_usage_guide_content))
                    <div class="page-rich-content product-rich-content product-guide-content">{!! $product->rendered_usage_guide_content !!}</div>
                @endif

                @if($product->usage_guide_video_id)
                    <section class="product-guide-media">
                        <h4>Video hướng dẫn</h4>
                        <div class="video-container">
                            <iframe
                                src="https://www.youtube.com/embed/{{ $product->usage_guide_video_id }}"
                                title="Video hướng dẫn sử dụng {{ $product->name }}"
                                loading="lazy"
                                allowfullscreen
                            ></iframe>
                        </div>
                    </section>
                @endif

                @if($usageGuideAttachmentUrl)
                    <section class="product-guide-media">
                        <h4>Tài liệu hướng dẫn</h4>
                        <div class="product-guide-file-card">
                            <div>
                                <strong>{{ strtoupper($usageGuideAttachmentExtension ?: 'FILE') }}</strong>
                                <p>{{ $usageGuideAttachmentName }}</p>
                            </div>
                            <div class="product-guide-file-actions">
                                <a href="{{ $usageGuideAttachmentUrl }}" target="_blank" rel="noopener">Xem file</a>
                                <a href="{{ $usageGuideAttachmentUrl }}" download>Tải xuống</a>
                            </div>
                        </div>

                        @if($usageGuideIsPdf)
                            <iframe
                                class="product-guide-pdf-frame"
                                src="{{ $usageGuideAttachmentUrl }}#view=FitH"
                                title="Tài liệu hướng dẫn {{ $product->name }}"
                                loading="lazy"
                            ></iframe>
                        @else
                            <p class="product-guide-file-note">File Excel/CSV sẽ được mở hoặc tải xuống trên thiết bị của khách.</p>
                        @endif
                    </section>
                @endif

                @unless($hasUsageGuide)
                    <p>Hướng dẫn sử dụng đang được cập nhật.</p>
                @endunless
            </div>

            <div class="detail-tab-content" id="tab-comment" role="tabpanel" aria-labelledby="tab-btn-comment">
                <p>Bình luận đang được cập nhật.</p>
            </div>
        </div>

        @php
            $initialRelatedVisible = 4;
            $hiddenRelatedCount = max($relatedProducts->count() - $initialRelatedVisible, 0);
        @endphp

        @if($relatedProducts->isNotEmpty())
            <section class="related-products-block" id="relatedProductsBlock">
                <div class="section-header related-products-header">
                    <h2 class="section-title">Sản phẩm cùng loại hoặc tương tự</h2>
                </div>

                <div class="related-products-grid" id="relatedProductsGrid">
                    @foreach($relatedProducts as $index => $relatedProduct)
                        <article class="product-card catalog-card related-product-card clickable-card {{ $index >= $initialRelatedVisible ? 'is-hidden' : '' }}" data-card-link="{{ route('products.show', $relatedProduct->slug) }}">
                            <div class="product-img">
                                @if($relatedProduct->display_image_url)
                                    <img src="{{ $media->url($relatedProduct->display_image, ['width' => 640, 'quality' => 76]) ?? $relatedProduct->display_image_url }}" alt="{{ $relatedProduct->name }}" loading="lazy" decoding="async">
                                @endif
                                @if($relatedProduct->installment_percent)
                                    <span class="badge-installment">Khuyến mãi</span>
                                @endif
                                @if(((int) $relatedProduct->discount_percent) > 0)
                                    <span class="badge-discount-ribbon">-{{ (int) $relatedProduct->discount_percent }}%</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a>
                                </h3>
                                <div class="product-price">{{ $relatedProduct->price ?: 'Liên hệ' }}</div>
                                <div class="product-meta">Mã SP: {{ $relatedProduct->code ?: ($relatedProduct->sku ?: 'Đang cập nhật') }}</div>
                                @if($relatedProduct->short_description)
                                    <p class="product-snippet">{{ \Illuminate\Support\Str::limit(strip_tags($relatedProduct->short_description), 120) }}</p>
                                @endif
                                <div class="product-cat">{{ optional($relatedProduct->category)->name }}</div>
                                <div class="product-actions catalog-actions">
                                    <a href="{{ route('contact') }}" class="btn-detail btn-buy">Liên hệ</a>
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn-detail btn-outline">Xem chi tiết</a>
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
                            data-expand-text="Xem thêm {{ $hiddenRelatedCount }} sản phẩm"
                            data-collapse-text="Thu gọn"
                        >
                            Xem thêm {{ $hiddenRelatedCount }} sản phẩm
                        </button>
                    </div>
                @endif
            </section>
        @endif
    </div>
</section>

<div class="product-lightbox" id="productLightbox" aria-hidden="true">
    <button type="button" class="product-lightbox-close" id="productLightboxClose" aria-label="Đóng">&times;</button>
    <img id="productLightboxImage" src="" alt="Ảnh sản phẩm phóng lớn">
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.product-detail-tabs');
    if (container) {
        const buttons = container.querySelectorAll('.detail-tab-btn');
        const tabs = container.querySelectorAll('.detail-tab-content');
        const activateTab = (button) => {
            const target = button.getAttribute('data-target');
            const activeTab = container.querySelector('#' + target);
            if (!activeTab) return;

            buttons.forEach((btn) => {
                const isActive = btn === button;
                btn.classList.toggle('active', isActive);
                btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            tabs.forEach((tab) => {
                tab.classList.remove('active', 'is-entering');
            });

            activeTab.classList.add('active');
            requestAnimationFrame(() => {
                activeTab.classList.add('is-entering');
            });
        };

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                activateTab(button);
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
                relatedToggle.textContent = relatedToggle.getAttribute('data-expand-text') || 'Xem thêm';
            } else {
                hiddenCards.forEach((card) => card.classList.add('show'));
                relatedToggle.classList.add('expanded');
                relatedToggle.textContent = relatedToggle.getAttribute('data-collapse-text') || 'Thu gọn';
            }
        });
    }
});
</script>

@include('front.partials.newsletter-signup')
@endsection
