@extends('front.layouts.app')

@push('page_styles')
<style>
    .contact-page {
        --contact-blue: #0f4c81;
        --contact-blue-soft: #1d70b8;
        --contact-bg: #f4f8fc;
        --contact-border: #d8e3ef;
        --contact-ink: #0f172a;
        --contact-muted: #5b6b7f;
        padding: 1.5rem 1.5rem 4rem;
    }

    .contact-hero {
        background: linear-gradient(135deg, var(--contact-blue) 0%, var(--contact-blue-soft) 100%);
        border-radius: 0;
        padding: clamp(3rem, 8vw, 5.5rem) 1.5rem;
        color: #fff;
        margin-bottom: 0;
    }

    .contact-hero-inner {
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
    }

    .contact-hero h1 {
        font-size: clamp(1.8rem, 4vw, 3rem);
        line-height: 1.1;
        margin: 0;
    }

    .contact-hero p {
        margin: 0.85rem 0 0;
        font-size: 1.05rem;
        max-width: 800px;
        opacity: 0.95;
    }

    .contact-layout {
        display: grid;
        grid-template-columns: minmax(280px, 1fr) minmax(320px, 1.15fr);
        gap: 1rem;
    }

    .contact-card {
        background: #fff;
        border: 1px solid var(--contact-border);
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 10px 28px rgba(15, 76, 129, 0.08);
    }

    .contact-card h2 {
        font-size: 1.2rem;
        margin: 0 0 0.9rem;
        color: var(--contact-ink);
    }

    .contact-list {
        display: grid;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .contact-item {
        display: flex;
        gap: 0.7rem;
        align-items: flex-start;
        color: var(--contact-muted);
        line-height: 1.45;
    }

    .contact-item i {
        color: var(--contact-blue);
        margin-top: 0.2rem;
        min-width: 16px;
    }

    .contact-item strong,
    .contact-item a {
        color: var(--contact-ink);
    }

    .contact-item a:hover {
        color: var(--contact-blue-soft);
    }

    .contact-benefits {
        display: grid;
        gap: 0.55rem;
        margin-top: 0.8rem;
    }

    .contact-benefits span {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--contact-muted);
    }

    .contact-benefits i {
        color: #0ea5a3;
    }

    .contact-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.8rem;
    }

    .contact-field {
        display: grid;
        gap: 0.4rem;
    }

    .contact-field.full {
        grid-column: 1 / -1;
    }

    .contact-label {
        font-size: 0.84rem;
        color: var(--contact-muted);
        font-weight: 700;
        letter-spacing: 0.01em;
        text-transform: uppercase;
    }

    .contact-input {
        border: 1px solid var(--contact-border);
        border-radius: 10px;
        padding: 0.72rem 0.8rem;
        color: var(--contact-ink);
        font-size: 0.95rem;
        width: 100%;
        background: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .contact-input:focus {
        border-color: var(--contact-blue-soft);
        box-shadow: 0 0 0 3px rgba(29, 112, 184, 0.17);
        outline: none;
    }

    .contact-input.is-error {
        border-color: #dc2626;
    }

    .contact-error {
        margin: 0;
        color: #b91c1c;
        font-size: 0.82rem;
    }

    .contact-alert {
        border-radius: 10px;
        padding: 0.8rem 0.9rem;
        margin-bottom: 0.9rem;
        font-size: 0.9rem;
    }

    .contact-alert.success {
        background: #ecfdf5;
        border: 1px solid #86efac;
        color: #166534;
    }

    .contact-alert.error {
        background: #fff1f2;
        border: 1px solid #fda4af;
        color: #9f1239;
    }

    .contact-actions {
        margin-top: 0.95rem;
        display: flex;
        gap: 0.6rem;
        align-items: center;
    }

    .contact-actions .btn {
        padding-inline: 1.6rem;
    }

    .contact-note {
        color: var(--contact-muted);
        font-size: 0.82rem;
    }

    .contact-map-section {
        width: 100%;
        background: #f4f8fc;
        padding: 0;
    }

    .contact-map-inner {
        width: 100%;
        height: clamp(384px, calc(42vw + 4rem), 584px);
        overflow: hidden;
        border-top: 1px solid var(--contact-border);
        border-bottom: 1px solid var(--contact-border);
        background: #dbeafe;
    }

    .contact-map-inner iframe {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
    }

    @media (max-width: 992px) {
        .contact-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .contact-page {
            padding-inline: 1rem;
            padding-bottom: 3rem;
        }

        .contact-hero {
            padding-inline: 1rem;
        }

        .contact-form-grid {
            grid-template-columns: 1fr;
        }

        .contact-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .contact-map-section {
            padding-bottom: 0;
        }

        .contact-map-inner {
            height: 384px;
        }
    }
</style>
@endpush

@section('content')
@php
    $contactHeroImage = $siteContent['page_contact_hero_image'] ?? null;
    if (is_string($contactHeroImage) && $contactHeroImage !== '' && !str_starts_with($contactHeroImage, 'http://') && !str_starts_with($contactHeroImage, 'https://')) {
        $contactHeroImage = str_starts_with($contactHeroImage, 'assets/')
            ? asset($contactHeroImage)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($contactHeroImage);
    }
    $contactMapAddress = trim((string) ($siteProfile['address'] ?? ''));
    $contactMapQuery = $contactMapAddress !== '' ? $contactMapAddress : 'TechSewing';
@endphp
@push('preload_assets')
    @if(!empty($contactHeroImage))
        <link rel="preload" as="image" href="{{ $contactHeroImage }}" fetchpriority="high">
    @endif
@endpush
<section class="contact-hero page-hero page-hero--contact" @if(!empty($contactHeroImage)) style="background-image: linear-gradient(120deg, rgba(15, 23, 42, 0.72), rgba(29, 78, 216, 0.62)), url('{{ $contactHeroImage }}'); background-size: cover; background-position: center;" @endif>
    <div class="contact-hero-inner">
        <h1>{{ $siteContent['page_contact_heading'] ?? ($siteContent['contact_page_title'] ?? 'Liên hệ TechSewing') }}</h1>
        <p>{{ $siteContent['page_contact_desc'] ?? ($siteContent['contact_page_subtitle'] ?? 'Nhận tư vấn giải pháp máy công nghiệp, báo giá nhanh và hỗ trợ kỹ thuật theo nhu cầu nhà máy của bạn.') }}</p>
    </div>
</section>

<section class="container contact-page">
    <div class="contact-layout">
        <aside class="contact-card">
            <h2>Thông tin liên hệ</h2>

            <div class="contact-list">
                <div class="contact-item">
                    <i class="fas fa-phone-volume" aria-hidden="true"></i>
                    <div>
                        <small>Hotline</small><br>
                        <a href="tel:{{ preg_replace('/\D+/', '', $siteProfile['hotline'] ?? '') }}">
                            <strong>{{ $siteProfile['hotline'] ?? '-' }}</strong>
                        </a>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope-open-text" aria-hidden="true"></i>
                    <div>
                        <small>Email</small><br>
                        <a href="mailto:{{ $siteProfile['email'] ?? '' }}">
                            <strong>{{ $siteProfile['email'] ?? '-' }}</strong>
                        </a>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-location-dot" aria-hidden="true"></i>
                    <div>
                        <small>Địa chỉ</small><br>
                        <strong>{{ $siteProfile['address'] ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            <div class="contact-benefits">
                <span><i class="fas fa-circle-check" aria-hidden="true"></i> Tư vấn phương án theo quy mô nhà máy</span>
                <span><i class="fas fa-circle-check" aria-hidden="true"></i> Demo vận hành và hướng dẫn sử dụng</span>
                <span><i class="fas fa-circle-check" aria-hidden="true"></i> Hỗ trợ giao, lắp đặt và bảo hành tận nơi</span>
            </div>
        </aside>

        <div class="contact-card">
            <h2>Gửi yêu cầu tư vấn</h2>

            @if (session('success'))
                <div class="contact-alert success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="contact-alert error">Vui lòng kiểm tra lại thông tin trong form.</div>
            @endif

            <form method="post" action="{{ route('contact.store') }}" novalidate>
                @csrf
                <div class="contact-form-grid">
                    <div class="contact-field">
                        <label for="contact_name" class="contact-label">Ho va ten *</label>
                        <input id="contact_name" type="text" name="name" class="contact-input @error('name') is-error @enderror" value="{{ old('name') }}" required>
                        @error('name')<p class="contact-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="contact-field">
                        <label for="contact_phone" class="contact-label">So dien thoai *</label>
                        <input id="contact_phone" type="text" name="phone" class="contact-input @error('phone') is-error @enderror" value="{{ old('phone') }}" required>
                        @error('phone')<p class="contact-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="contact-field">
                        <label for="contact_email" class="contact-label">Email</label>
                        <input id="contact_email" type="email" name="email" class="contact-input @error('email') is-error @enderror" value="{{ old('email') }}">
                        @error('email')<p class="contact-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="contact-field">
                        <label for="contact_company" class="contact-label">Cong ty</label>
                        <input id="contact_company" type="text" name="company" class="contact-input @error('company') is-error @enderror" value="{{ old('company') }}">
                        @error('company')<p class="contact-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="contact-field full">
                        <label for="contact_interest" class="contact-label">Nhu cau quan tam</label>
                        <input id="contact_interest" type="text" name="interest" class="contact-input @error('interest') is-error @enderror" value="{{ old('interest') }}" placeholder="Ví dụ: Máy lập đầu tự động, máy in nhãn vải...">
                        @error('interest')<p class="contact-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="contact-field full">
                        <label for="contact_message" class="contact-label">Nội dung *</label>
                        <textarea id="contact_message" name="message" rows="6" class="contact-input @error('message') is-error @enderror" required>{{ old('message') }}</textarea>
                        @error('message')<p class="contact-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="contact-actions">
                    <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                    <span class="contact-note">TechSewing sẽ liên hệ với bạn trong thời gian sớm nhất.</span>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="contact-map-section" aria-label="Bản đồ">
    <div class="contact-map-inner">
        <iframe
            src="https://www.google.com/maps?q={{ urlencode($contactMapQuery) }}&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
            title="Bản đồ {{ $contactMapQuery }}"
        ></iframe>
    </div>
</section>
@endsection
