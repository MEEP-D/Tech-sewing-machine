<x-filament-widgets::widget>
    @once
        <style>
            .admin-action-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.75rem;
            }

            .admin-action-card,
            .admin-content-group,
            .admin-lead-row {
                border: 1px solid #d9e2ef;
                background: #ffffff;
            }

            .admin-action-card {
                display: flex;
                min-height: 4.75rem;
                align-items: center;
                gap: 0.75rem;
                border-left: 4px solid var(--card-accent);
                border-radius: 0.75rem;
                padding: 0.8rem;
                text-decoration: none;
                transition: transform 160ms ease, border-color 160ms ease, box-shadow 160ms ease, background-color 160ms ease;
            }

            .admin-action-card:hover,
            .admin-lead-row:hover {
                transform: translateY(-1px);
                border-color: color-mix(in srgb, var(--card-accent, #2563eb) 45%, #d9e2ef);
                box-shadow: 0 14px 28px -22px color-mix(in srgb, var(--card-accent, #2563eb) 45%, #000);
            }

            .admin-action-mark {
                display: inline-flex;
                width: 2.25rem;
                height: 2.25rem;
                flex: 0 0 auto;
                align-items: center;
                justify-content: center;
                border-radius: 0.625rem;
                background: color-mix(in srgb, var(--card-accent) 14%, transparent);
                color: var(--card-accent);
                font-size: 0.72rem;
                font-weight: 800;
            }

            .admin-action-copy {
                min-width: 0;
            }

            .admin-action-copy strong,
            .admin-action-copy em {
                display: block;
            }

            .admin-action-copy strong,
            .admin-content-title,
            .admin-lead-name,
            .admin-lead-summary strong {
                color: #0f172a;
            }

            .admin-action-copy strong {
                font-size: 0.9rem;
                font-weight: 700;
            }

            .admin-action-copy em,
            .admin-content-group-head,
            .admin-content-time,
            .admin-lead-time,
            .admin-lead-summary p,
            .admin-lead-phone,
            .admin-empty-state {
                color: #475569;
            }

            .admin-action-copy em {
                margin-top: 0.15rem;
                font-size: 0.76rem;
                font-style: normal;
                line-height: 1.35;
            }

            .admin-action-card-blue,
            .admin-content-kicker-blue {
                --card-accent: #2563eb;
            }

            .admin-action-card-green,
            .admin-content-kicker-green {
                --card-accent: #059669;
            }

            .admin-action-card-amber,
            .admin-content-kicker-amber {
                --card-accent: #d97706;
            }

            .admin-action-card-slate {
                --card-accent: #64748b;
            }

            .admin-action-card-violet {
                --card-accent: #7c3aed;
            }

            .admin-action-card-cyan {
                --card-accent: #0891b2;
            }

            .admin-content-stack,
            .admin-lead-list {
                display: grid;
                gap: 0.9rem;
            }

            .admin-content-group {
                border-radius: 0.75rem;
                padding: 0.75rem;
            }

            .admin-content-group-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                margin-bottom: 0.55rem;
                font-size: 0.72rem;
                font-weight: 700;
            }

            .admin-content-kicker {
                display: inline-flex;
                align-items: center;
                border-radius: 999px;
                background: color-mix(in srgb, var(--card-accent) 12%, transparent);
                color: var(--card-accent);
                padding: 0.25rem 0.55rem;
                text-transform: uppercase;
            }

            .admin-content-list {
                display: grid;
                gap: 0.45rem;
            }

            .admin-content-row {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                align-items: center;
                gap: 0.8rem;
                border-radius: 0.55rem;
                padding: 0.55rem 0.65rem;
                color: inherit;
                text-decoration: none;
                transition: background-color 160ms ease, transform 160ms ease;
            }

            .admin-content-row:hover {
                background: #eef3f9;
                transform: translateX(1px);
            }

            .admin-content-title {
                min-width: 0;
                overflow: hidden;
                font-size: 0.86rem;
                font-weight: 650;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .admin-content-time,
            .admin-lead-time {
                border-radius: 999px;
                background: #eef3f9;
                font-size: 0.72rem;
                font-weight: 650;
                padding: 0.22rem 0.5rem;
                white-space: nowrap;
            }

            .admin-alert-pill {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                border-radius: 999px;
                background: #dc2626;
                color: #ffffff;
                font-size: 0.74rem;
                font-weight: 800;
                padding: 0.28rem 0.6rem;
            }

            .admin-alert-pill span {
                width: 0.4rem;
                height: 0.4rem;
                border-radius: 999px;
                background: #ffffff;
                animation: admin-pulse 1.15s ease-in-out infinite;
            }

            .admin-lead-summary {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                margin-bottom: 0.9rem;
                border: 1px solid #d9e2ef;
                border-left: 4px solid #2563eb;
                border-radius: 0.75rem;
                background: #eff6ff;
                padding: 0.8rem;
            }

            .admin-lead-summary-alert {
                border-left-color: #dc2626;
                background: #fff1f2;
            }

            .admin-lead-summary strong,
            .admin-lead-summary p {
                display: block;
            }

            .admin-lead-summary strong {
                font-size: 0.92rem;
                font-weight: 800;
            }

            .admin-lead-summary p {
                margin-top: 0.18rem;
                font-size: 0.78rem;
            }

            .admin-lead-summary a {
                flex: 0 0 auto;
                border-radius: 999px;
                background: #ffffff;
                color: #2563eb;
                font-size: 0.74rem;
                font-weight: 800;
                padding: 0.38rem 0.65rem;
                text-decoration: none;
            }

            .admin-lead-row {
                display: grid;
                grid-template-columns: auto minmax(0, 1fr) auto;
                align-items: center;
                gap: 0.75rem;
                border-radius: 0.75rem;
                padding: 0.7rem;
                color: inherit;
                text-decoration: none;
                transition: transform 160ms ease, border-color 160ms ease, background-color 160ms ease;
            }

            .admin-lead-avatar {
                display: inline-flex;
                width: 2rem;
                height: 2rem;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                background: #dbeafe;
                color: #2563eb;
                font-size: 0.82rem;
                font-weight: 800;
                text-transform: uppercase;
            }

            .admin-lead-main,
            .admin-lead-name,
            .admin-lead-phone {
                display: block;
                min-width: 0;
            }

            .admin-lead-name {
                overflow: hidden;
                font-size: 0.88rem;
                font-weight: 750;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .admin-lead-name em {
                margin-left: 0.35rem;
                border-radius: 999px;
                background: #fee2e2;
                color: #b91c1c;
                font-size: 0.62rem;
                font-style: normal;
                font-weight: 850;
                padding: 0.15rem 0.4rem;
                vertical-align: middle;
            }

            .admin-lead-phone {
                margin-top: 0.15rem;
                font-size: 0.76rem;
            }

            .admin-empty-state {
                border: 1px dashed #d9e2ef;
                border-radius: 0.75rem;
                font-size: 0.82rem;
                padding: 0.8rem;
            }

            .dark .admin-action-card,
            .dark .admin-content-group,
            .dark .admin-lead-row {
                border-color: #2b3447;
                background: #111827;
            }

            .dark .admin-action-card:hover,
            .dark .admin-lead-row:hover {
                border-color: color-mix(in srgb, var(--card-accent, #60a5fa) 58%, #2b3447);
                background: #172033;
                box-shadow: 0 14px 28px -22px color-mix(in srgb, var(--card-accent, #60a5fa) 60%, #000);
            }

            .dark .admin-action-copy strong,
            .dark .admin-content-title,
            .dark .admin-lead-name,
            .dark .admin-lead-summary strong {
                color: #e5ecf7;
            }

            .dark .admin-action-copy em,
            .dark .admin-content-group-head,
            .dark .admin-content-time,
            .dark .admin-lead-time,
            .dark .admin-lead-summary p,
            .dark .admin-lead-phone,
            .dark .admin-empty-state {
                color: #a9b5cb;
            }

            .dark .admin-action-mark,
            .dark .admin-content-kicker,
            .dark .admin-lead-avatar {
                background: color-mix(in srgb, var(--card-accent, #60a5fa) 18%, #111827);
            }

            .dark .admin-content-row:hover,
            .dark .admin-content-time,
            .dark .admin-lead-time {
                background: #1b2639;
            }

            .dark .admin-lead-summary {
                border-color: #2b3447;
                border-left-color: #60a5fa;
                background: #13223a;
            }

            .dark .admin-lead-summary-alert {
                border-left-color: #f87171;
                background: #2a1620;
            }

            .dark .admin-lead-summary a {
                background: #1b2639;
                color: #93c5fd;
            }

            .dark .admin-lead-name em {
                background: #431923;
                color: #fecaca;
            }

            .dark .admin-empty-state {
                border-color: #2b3447;
            }

            @keyframes admin-pulse {
                0%, 100% {
                    opacity: 1;
                    transform: scale(1);
                }

                50% {
                    opacity: 0.55;
                    transform: scale(0.72);
                }
            }

            @media (max-width: 640px) {
                .admin-action-grid {
                    grid-template-columns: 1fr;
                }

                .admin-lead-summary {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .admin-lead-row {
                    grid-template-columns: auto minmax(0, 1fr);
                }

                .admin-lead-time {
                    grid-column: 2;
                    justify-self: start;
                }
            }
        </style>
    @endonce

    <x-filament::section heading="Thao tác nhanh" icon="heroicon-o-bolt">
        <div class="admin-action-grid">
            <a href="{{ url('/admin/products/create') }}" class="admin-action-card admin-action-card-blue">
                <span class="admin-action-mark">SP</span>
                <span class="admin-action-copy">
                    <strong>Thêm sản phẩm</strong>
                    <em>Tạo hồ sơ máy, giá, ảnh và thông số.</em>
                </span>
            </a>

            <a href="{{ url('/admin/posts/create') }}" class="admin-action-card admin-action-card-green">
                <span class="admin-action-mark">BV</span>
                <span class="admin-action-copy">
                    <strong>Thêm bài viết</strong>
                    <em>Đăng tin tức, sự kiện hoặc hội thảo.</em>
                </span>
            </a>

            <a href="{{ url('/admin/pages/create') }}" class="admin-action-card admin-action-card-amber">
                <span class="admin-action-mark">TR</span>
                <span class="admin-action-copy">
                    <strong>Thêm trang</strong>
                    <em>Tạo trang nội dung tĩnh cho website.</em>
                </span>
            </a>

            <a href="{{ url('/admin/site-settings') }}" class="admin-action-card admin-action-card-slate">
                <span class="admin-action-mark">WS</span>
                <span class="admin-action-copy">
                    <strong>Cài đặt website</strong>
                    <em>Logo, hotline, email và thông tin hệ thống.</em>
                </span>
            </a>

            <a href="{{ url('/admin/seo-settings') }}" class="admin-action-card admin-action-card-violet">
                <span class="admin-action-mark">SEO</span>
                <span class="admin-action-copy">
                    <strong>Cài đặt SEO</strong>
                    <em>Title, canonical, OG và mặc định tìm kiếm.</em>
                </span>
            </a>

            <a href="{{ url('/admin/categories') }}" class="admin-action-card admin-action-card-cyan">
                <span class="admin-action-mark">DM</span>
                <span class="admin-action-copy">
                    <strong>Quản lý danh mục</strong>
                    <em>Phân loại sản phẩm, bài viết và trang.</em>
                </span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
