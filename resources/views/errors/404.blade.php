@extends('front.layouts.app')

@section('content')
    <section style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:4rem 1.5rem;background:var(--color-surface,#fff);text-align:center;">
        <div style="max-width:520px;margin:0 auto;">
            {{-- Số 404 lớn --}}
            <div style="font-size:clamp(6rem,20vw,10rem);font-weight:900;line-height:1;color:var(--color-primary,#2563eb);opacity:.12;user-select:none;letter-spacing:-0.04em;margin-bottom:-1rem;">404</div>

            {{-- Icon --}}
            <div style="margin-bottom:1.5rem;">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:inline-block;">
                    <circle cx="40" cy="40" r="38" stroke="#2563eb" stroke-width="3" fill="#eff6ff"/>
                    <path d="M40 24v20" stroke="#2563eb" stroke-width="4" stroke-linecap="round"/>
                    <circle cx="40" cy="52" r="3" fill="#2563eb"/>
                </svg>
            </div>

            <h1 style="font-size:clamp(1.5rem,4vw,2rem);font-weight:800;color:var(--color-text,#0f172a);margin-bottom:.75rem;">
                Không tìm thấy trang
            </h1>
            <p style="font-size:1rem;color:var(--color-text-2,#475569);line-height:1.7;margin-bottom:2rem;">
                Trang bạn đang tìm kiếm có thể đã bị di chuyển, đổi tên hoặc không còn tồn tại.<br>
                Hãy quay lại trang chủ hoặc khám phá sản phẩm của chúng tôi.
            </p>

            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('home') }}" class="v-btn v-btn-primary">← Về trang chủ</a>
                <a href="{{ route('products.index') }}" class="v-btn v-btn-outline-black">Xem sản phẩm</a>
                <a href="{{ route('contact') }}" class="v-btn v-btn-outline-black">Liên hệ hỗ trợ</a>
            </div>

            {{-- Gợi ý trang nhanh --}}
            <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--color-border,#e2e8f0);">
                <p style="font-size:.82rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--color-text-2,#64748b);margin-bottom:1rem;">Các trang thường dùng</p>
                <div style="display:flex;gap:.5rem;justify-content:center;flex-wrap:wrap;">
                    <a href="{{ route('products.index') }}" style="display:inline-block;padding:.4rem .9rem;border:1px solid var(--color-border,#e2e8f0);border-radius:999px;font-size:.85rem;text-decoration:none;color:var(--color-text,#0f172a);transition:background .15s;">Sản phẩm</a>
                    <a href="{{ route('news.index') }}" style="display:inline-block;padding:.4rem .9rem;border:1px solid var(--color-border,#e2e8f0);border-radius:999px;font-size:.85rem;text-decoration:none;color:var(--color-text,#0f172a);transition:background .15s;">Tin tức</a>
                    <a href="{{ route('contact') }}" style="display:inline-block;padding:.4rem .9rem;border:1px solid var(--color-border,#e2e8f0);border-radius:999px;font-size:.85rem;text-decoration:none;color:var(--color-text,#0f172a);transition:background .15s;">Liên hệ</a>
                </div>
            </div>
        </div>
    </section>
@endsection
