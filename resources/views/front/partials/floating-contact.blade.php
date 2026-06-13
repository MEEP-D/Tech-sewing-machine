@php
    $fabFacebookUrl = trim((string) ($siteSettings['header_facebook_url'] ?? ''));
    $fabZaloUrl = trim((string) ($siteSettings['header_zalo_url'] ?? ''));
    $fabZaloIconUrl = 'https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg';
    $fabHotline = trim((string) ($siteProfile['hotline'] ?? ($siteSettings['contact_hotline'] ?? '')));
    $fabHotlineHref = $fabHotline !== '' ? 'tel:' . preg_replace('/\D+/', '', $fabHotline) : route('contact');
@endphp

<div class="fab_contact_wrap" aria-label="Liên hệ nhanh">
    <a class="fab_btn is-float is-visible fab-btn-zalo" href="{{ $fabZaloUrl !== '' ? $fabZaloUrl : route('contact') }}" @if($fabZaloUrl !== '') target="_blank" rel="noopener noreferrer" @endif aria-label="Zalo">
        <img class="fab-zalo-img" src="{{ $fabZaloIconUrl }}" alt="Zalo" loading="lazy" decoding="async">
    </a>

    <a class="fab_btn is-float is-visible fab-btn-facebook" href="{{ $fabFacebookUrl !== '' ? $fabFacebookUrl : route('contact') }}" @if($fabFacebookUrl !== '') target="_blank" rel="noopener noreferrer" @endif aria-label="Facebook">
        <i class="fab fa-facebook-f" aria-hidden="true"></i>
    </a>

    <a class="fab_btn is-float is-visible fab-btn-phone" href="{{ $fabHotlineHref }}" aria-label="Gọi điện">
        <i class="fas fa-phone-alt" aria-hidden="true"></i>
    </a>
</div>
