<x-filament-panels::page.simple class="admin-login-page">
    <section class="admin-login-shell" aria-label="Đăng nhập quản trị">
        <aside class="admin-login-hero" aria-label="Linh vật chào quản trị viên">
            <div class="admin-login-hero-glow admin-login-hero-glow-one"></div>
            <div class="admin-login-hero-glow admin-login-hero-glow-two"></div>

            <div class="admin-login-brand">
                <span class="admin-login-brand-mark">TS</span>
                <span>
                    <strong>Tech Sewing Machine</strong>
                    <small>Bảng điều khiển thông minh</small>
                </span>
            </div>

            <div class="admin-ai-card">
                <div class="admin-ai-orbit admin-ai-orbit-one"></div>
                <div class="admin-ai-orbit admin-ai-orbit-two"></div>

                <svg class="admin-ai-mascot" viewBox="0 0 360 320" role="img" aria-labelledby="admin-ai-title">
                    <title id="admin-ai-title">Linh vật chào admin</title>
                    <defs>
                        <linearGradient id="admin-ai-body" x1="58" y1="42" x2="286" y2="285" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#ffffff" />
                            <stop offset="0.5" stop-color="#dbeafe" />
                            <stop offset="1" stop-color="#93c5fd" />
                        </linearGradient>
                        <linearGradient id="admin-ai-screen" x1="109" y1="80" x2="248" y2="207" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#0f172a" />
                            <stop offset="1" stop-color="#1e3a8a" />
                        </linearGradient>
                    </defs>
                    <path d="M74 238c-18-20-24-55-8-79 15-24 42-27 57-48 19-25 10-55 36-72 24-16 62-8 80 14 18 23 11 51 26 73 15 21 43 24 52 50 10 29-10 65-37 79-26 14-52 3-82 8-38 7-85 19-124-25Z" fill="#bfdbfe" opacity=".55" />
                    <rect x="78" y="72" width="204" height="174" rx="44" fill="url(#admin-ai-body)" />
                    <rect x="105" y="93" width="150" height="102" rx="32" fill="url(#admin-ai-screen)" />
                    <circle cx="150" cy="141" r="10" fill="#38bdf8" />
                    <circle cx="210" cy="141" r="10" fill="#38bdf8" />
                    <path d="M151 165c18 15 40 15 58 0" stroke="#93c5fd" stroke-width="9" stroke-linecap="round" fill="none" />
                    <path d="M179 72V42" stroke="#2563eb" stroke-width="11" stroke-linecap="round" />
                    <circle cx="179" cy="32" r="16" fill="#2563eb" />
                    <path d="M78 143H48c-12 0-22 10-22 22v12c0 12 10 22 22 22h30" stroke="#60a5fa" stroke-width="14" stroke-linecap="round" />
                    <path d="M282 143h30c12 0 22 10 22 22v12c0 12-10 22-22 22h-30" stroke="#60a5fa" stroke-width="14" stroke-linecap="round" />
                    <path d="M126 246l-18 38" stroke="#2563eb" stroke-width="13" stroke-linecap="round" />
                    <path d="M234 246l18 38" stroke="#2563eb" stroke-width="13" stroke-linecap="round" />
                    <circle cx="108" cy="265" r="15" fill="#1d4ed8" />
                    <circle cx="252" cy="265" r="15" fill="#1d4ed8" />
                    <path d="M118 218h124" stroke="#ffffff" stroke-width="10" stroke-linecap="round" opacity=".9" />
                    <path d="M138 238h84" stroke="#bfdbfe" stroke-width="8" stroke-linecap="round" />
                </svg>
            </div>

            <div class="admin-login-message">
                <span>Xin chào Admin</span>
                <h2>Trợ lý quản trị đã sẵn sàng đồng hành cùng bạn.</h2>
                <p>Quản lý nội dung, sản phẩm, khách hàng và SEO trong một không gian gọn gàng, bảo mật và dễ thao tác.</p>
            </div>

            <div class="admin-login-stats">
                <span><strong>24/7</strong> Theo dõi hệ thống</span>
                <span><strong>SEO</strong> Tối ưu nội dung</span>
                <span><strong>CRM</strong> Chăm sóc khách hàng</span>
            </div>
        </aside>

        <div class="admin-login-form-panel">
            <div class="admin-login-form-head">
                <span class="admin-login-eyebrow">Khu vực bảo mật</span>
                <h1>{{ $this->getHeading() }}</h1>
                @if (filled($subheading = $this->getSubheading()))
                    <p>{{ $subheading }}</p>
                @endif
            </div>

            {{ $this->content }}

            <div class="admin-login-help">
                <span>Cần hỗ trợ?</span>
                <strong>Liên hệ quản trị hệ thống để cấp lại quyền truy cập.</strong>
            </div>
        </div>
    </section>
</x-filament-panels::page.simple>
