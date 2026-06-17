@php
    $media = app(\App\Support\OptimizedMedia::class);
    $newsletterImage = $siteContent['newsletter_signup_image'] ?? null;
    $newsletterImage = $media->url($newsletterImage, ['width' => 1280, 'quality' => 74])
        ?? $media->url('assets/frontend/images/service-machine.png', ['width' => 1280, 'quality' => 74]);
    $newsletterTitle = trim((string) ($siteContent['newsletter_signup_title'] ?? '')) ?: 'Đăng ký nhận thông tin';
    $newsletterDescription = trim((string) ($siteContent['newsletter_signup_description'] ?? '')) ?: 'Đăng ký nhận thông tin chương trình khuyến mãi, dịch vụ và cập nhật mới nhất từ TechSewing.';
    $newsletterButtonText = trim((string) ($siteContent['newsletter_signup_button_text'] ?? '')) ?: 'Đăng ký';
    $newsletterNote = trim((string) ($siteContent['newsletter_signup_note'] ?? '')) ?: 'Bằng cách đăng ký, Quý khách xác nhận đã đọc, hiểu và đồng ý với chính sách bảo mật thông tin.';
@endphp

<section class="newsletter-signup" @if(!empty($newsletterImage)) data-bg="{{ $newsletterImage }}" @endif>
    <div class="container">
        <div class="newsletter-signup-inner">
            <h2 class="newsletter-signup-title">{{ $newsletterTitle }}</h2>
            <p class="newsletter-signup-desc">{{ $newsletterDescription }}</p>
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
                    placeholder="Nhập email của bạn"
                    required
                >
                <button type="submit" class="newsletter-signup-btn">{{ $newsletterButtonText }}</button>
            </form>
            @if($newsletterNote !== '')
                <p class="newsletter-signup-note">{{ $newsletterNote }}</p>
            @endif
        </div>
    </div>
</section>
