@php
    $promoEnabled = (string) ($siteSettings['promo_popup_enabled'] ?? '0') === '1';
    $promoTitle = trim((string) ($siteSettings['promo_popup_title'] ?? ''));
    $promoDescription = trim((string) ($siteSettings['promo_popup_description'] ?? ''));
    $promoButtonText = trim((string) ($siteSettings['promo_popup_button_text'] ?? ''));
    $promoButtonUrl = trim((string) ($siteSettings['promo_popup_button_url'] ?? ''));
    $promoContactText = trim((string) ($siteSettings['promo_popup_contact_text'] ?? 'Liên hệ ngay'));
    $promoContactUrl = trim((string) ($siteSettings['promo_popup_contact_url'] ?? ''));
    if ($promoContactUrl === '') {
        $hotline = preg_replace('/\D+/', '', (string) ($siteSettings['contact_hotline'] ?? ''));
        $promoContactUrl = $hotline !== '' ? ('tel:' . $hotline) : '/lien-he';
    }
    $promoCountdownEndAt = trim((string) ($siteSettings['promo_popup_countdown_end_at'] ?? ''));
    $promoCountdownNote = trim((string) ($siteSettings['promo_popup_countdown_note'] ?? ''));
    $promoDelaySeconds = max(0, (int) ($siteSettings['promo_popup_delay_seconds'] ?? 2));
    $promoFrequencyHours = max(1, (int) ($siteSettings['promo_popup_frequency_hours'] ?? 24));
    $promoImages = $siteSettings['promo_popup_images'] ?? [];
    if (! is_array($promoImages)) {
        $promoImages = [];
    }

    $promoImage = $siteSettings['promo_popup_image'] ?? null;
    if (is_string($promoImage) && filled($promoImage)) {
        array_unshift($promoImages, $promoImage);
    }

    $promoImageUrls = [];
    foreach ($promoImages as $promoImageItem) {
        if (! is_string($promoImageItem) || ! filled($promoImageItem)) {
            continue;
        }

        if (str_starts_with($promoImageItem, 'http://') || str_starts_with($promoImageItem, 'https://')) {
            $promoImageUrls[] = $promoImageItem;
            continue;
        }

        if (str_starts_with($promoImageItem, 'assets/')) {
            $promoImageUrls[] = asset($promoImageItem);
            continue;
        }

        $promoImageUrls[] = \Illuminate\Support\Facades\Storage::url($promoImageItem);
    }

    $promoImageUrls = array_values(array_unique($promoImageUrls));
    $promoCountdownTs = null;
    if ($promoCountdownEndAt !== '') {
        try {
            $promoCountdownTs = \Illuminate\Support\Carbon::parse($promoCountdownEndAt)->getTimestampMs();
        } catch (\Throwable) {
            $promoCountdownTs = null;
        }
    }
@endphp

@if($promoEnabled)
    <div id="promo-popup-overlay" class="promo-popup-overlay" aria-hidden="true">
        <div class="promo-popup-card" role="dialog" aria-modal="true" aria-label="Khuyến mãi">
            <button type="button" class="promo-popup-close" data-promo-close aria-label="Đóng">
                <i class="fas fa-times"></i>
            </button>
            @if(count($promoImageUrls) > 0)
                <div class="promo-popup-media">
                    <div class="promo-popup-slides" data-promo-slides>
                        @foreach($promoImageUrls as $index => $promoImageUrl)
                            <img
                                src="{{ $promoImageUrl }}"
                                class="promo-popup-slide{{ $index === 0 ? ' is-active' : '' }}"
                                loading="lazy"
                                decoding="async"
                                alt="{{ $promoTitle !== '' ? $promoTitle : 'Khuyến mãi' }}"
                                data-promo-slide
                            >
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="promo-popup-content">
                @if($promoTitle !== '')
                    <h3>{{ $promoTitle }}</h3>
                @endif
                @if($promoDescription !== '')
                    <p>{{ $promoDescription }}</p>
                @endif
                <div class="promo-popup-countdown" data-countdown-wrap @if($promoCountdownTs === null) style="display:none" @endif>
                    <div class="promo-popup-count-item">
                        <strong data-countdown-days>00</strong>
                        <span>ngay</span>
                    </div>
                    <div class="promo-popup-count-item">
                        <strong data-countdown-hours>00</strong>
                        <span>gio</span>
                    </div>
                </div>
                @if($promoButtonText !== '' && $promoButtonUrl !== '')
                    <a href="{{ $promoButtonUrl }}" class="promo-popup-btn" target="_blank" rel="noopener noreferrer">{{ $promoButtonText }}</a>
                @endif
                @if($promoContactText !== '' && $promoContactUrl !== '')
                    <a href="{{ $promoContactUrl }}" class="promo-popup-btn promo-popup-btn-secondary" target="_blank" rel="noopener noreferrer">{{ $promoContactText }}</a>
                @endif
                @if($promoCountdownNote !== '')
                    <p class="promo-popup-note">{{ $promoCountdownNote }}</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        (function () {
            var overlay = document.getElementById('promo-popup-overlay');
            if (!overlay) return;

            var delayMs = {{ $promoDelaySeconds }} * 1000;
            var frequencyMs = {{ $promoFrequencyHours }} * 60 * 60 * 1000;
            var storageKey = 'promo_popup_next_show_at';
            var slideIntervalMs = 3000;
            var now = Date.now();
            var nextShowAt = Number(localStorage.getItem(storageKey) || '0');
            var slideTimer = null;
            var forcePreview = window.location.search.indexOf('promo_preview=1') !== -1;
            var countdownEndAt = {{ $promoCountdownTs !== null ? (string) $promoCountdownTs : 'null' }};
            var countdownTimer = null;

            if (!forcePreview && now < nextShowAt) {
                return;
            }

            var slides = Array.prototype.slice.call(overlay.querySelectorAll('[data-promo-slide]'));
            var slideIndex = 0;

            function showSlide(index) {
                if (!slides.length) return;

                var total = slides.length;
                slideIndex = ((index % total) + total) % total;

                slides.forEach(function (slide, idx) {
                    if (idx === slideIndex) {
                        slide.classList.add('is-active');
                    } else {
                        slide.classList.remove('is-active');
                    }
                });
            }

            function startSlides() {
                if (slides.length <= 1) return;
                if (slideTimer) return;

                slideTimer = setInterval(function () {
                    showSlide(slideIndex + 1);
                }, slideIntervalMs);
            }

            function stopSlides() {
                if (!slideTimer) return;
                clearInterval(slideTimer);
                slideTimer = null;
            }

            function closePopup() {
                overlay.classList.remove('is-open');
                overlay.setAttribute('aria-hidden', 'true');
                stopSlides();
                if (countdownTimer) {
                    clearInterval(countdownTimer);
                    countdownTimer = null;
                }
                if (!forcePreview) {
                    localStorage.setItem(storageKey, String(Date.now() + frequencyMs));
                }
            }

            function startCountdown() {
                var wrap = overlay.querySelector('[data-countdown-wrap]');
                var daysEl = overlay.querySelector('[data-countdown-days]');
                var hoursEl = overlay.querySelector('[data-countdown-hours]');
                if (!wrap || !daysEl || !hoursEl || !countdownEndAt) return;

                function renderCountdown() {
                    var remainMs = countdownEndAt - Date.now();
                    if (remainMs < 0) remainMs = 0;
                    var totalHours = Math.floor(remainMs / (1000 * 60 * 60));
                    var days = Math.floor(totalHours / 24);
                    var hours = totalHours % 24;
                    daysEl.textContent = String(days).padStart(2, '0');
                    hoursEl.textContent = String(hours).padStart(2, '0');
                }

                renderCountdown();
                countdownTimer = setInterval(renderCountdown, 1000);
            }

            setTimeout(function () {
                showSlide(0);
                overlay.classList.add('is-open');
                overlay.setAttribute('aria-hidden', 'false');
                startSlides();
                startCountdown();
            }, delayMs);

            overlay.addEventListener('click', function (event) {
                if (event.target === overlay || event.target.closest('[data-promo-close]')) {
                    closePopup();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && overlay.classList.contains('is-open')) {
                    closePopup();
                }
            });
        })();
    </script>
@endif
