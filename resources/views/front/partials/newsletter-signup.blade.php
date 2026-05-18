@php
    $newsletterImage = $siteContent['newsletter_signup_image'] ?? null;
    if (is_string($newsletterImage) && $newsletterImage !== '' && !str_starts_with($newsletterImage, 'http://') && !str_starts_with($newsletterImage, 'https://')) {
        $newsletterImage = str_starts_with($newsletterImage, 'assets/')
            ? asset($newsletterImage)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($newsletterImage);
    }
@endphp
<section class="newsletter-signup" @if(!empty($newsletterImage)) style="background-image: url('{{ $newsletterImage }}');" @endif>
    <div class="container">
        <div class="newsletter-signup-inner">
            <h2 class="newsletter-signup-title">Dang ky nhan thong tin</h2>
            <p class="newsletter-signup-desc">Dang ky nhan thong tin chuong trinh khuyen mai, dich vu va cap nhat moi nhat tu TechSewing.</p>
            @if(session('newsletter_success'))
                <p class="newsletter-signup-note" style="color:#16a34a;">{{ session('newsletter_success') }}</p>
            @endif
            @if(session('newsletter_error'))
                <p class="newsletter-signup-note" style="color:#dc2626;">{{ session('newsletter_error') }}</p>
            @endif
            @error('email')
                <p class="newsletter-signup-note" style="color:#dc2626;">{{ $message }}</p>
            @enderror
            <form class="newsletter-signup-form" method="POST" action="{{ route('newsletter.subscribe') }}">
                @csrf
                <input
                    type="email"
                    name="email"
                    class="newsletter-signup-input"
                    placeholder="Nhap email cua ban"
                    required
                >
                <button type="submit" class="newsletter-signup-btn">Dang ky</button>
            </form>
            <p class="newsletter-signup-note">Bang cach dang ky, Quy khach xac nhan da doc, hieu va dong y voi chinh sach bao mat thong tin.</p>
        </div>
    </div>
</section>
