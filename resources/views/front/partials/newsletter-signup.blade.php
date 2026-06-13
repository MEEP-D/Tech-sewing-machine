@php
    $media = app(\App\Support\OptimizedMedia::class);
    $newsletterImage = $siteContent['newsletter_signup_image'] ?? null;
    $newsletterImage = $media->url($newsletterImage, ['width' => 1280, 'quality' => 74])
        ?? $media->url('assets/frontend/images/service-machine.png', ['width' => 1280, 'quality' => 74]);
@endphp
<section class="newsletter-signup" @if(!empty($newsletterImage)) data-bg="{{ $newsletterImage }}" @endif>
    <div class="container">
        <div class="newsletter-signup-inner">
            <h2 class="newsletter-signup-title">Đăng ký nhận thông tin</h2>
            <p class="newsletter-signup-desc">Đăng ký nhận thông tin chương trình khuyến mãi, dịch vụ và cập nhật mới nhất từ TechSewing.</p>
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
                <button type="submit" class="newsletter-signup-btn">Đăng ký</button>
            </form>
            <p class="newsletter-signup-note">Bằng cách đăng ký, Quý khách xác nhận đã đọc, hiểu và đồng ý với chính sách bảo mật thông tin.</p>
        </div>
    </div>
</section>
