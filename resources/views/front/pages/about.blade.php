@extends('front.layouts.app')

@section('content')
@php
    $aboutHeroImage = $siteContent['page_about_hero_image'] ?? null;
    if (is_string($aboutHeroImage) && $aboutHeroImage !== '' && !str_starts_with($aboutHeroImage, 'http://') && !str_starts_with($aboutHeroImage, 'https://')) {
        $aboutHeroImage = str_starts_with($aboutHeroImage, 'assets/')
            ? asset($aboutHeroImage)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($aboutHeroImage);
    }

    $partners = \App\Models\Partner::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->limit(18)
        ->get();

    $aboutGallery = [
        asset('assets/frontend/images/anh1.jpg'),
        asset('assets/frontend/images/anh2.jpg'),
        asset('assets/frontend/images/anh3.jpg'),
        asset('assets/frontend/images/anh4.jpg'),
        asset('assets/frontend/images/anh5.jpg'),
        asset('assets/frontend/images/anh6.jpg'),
        asset('assets/frontend/images/anh7.jpg'),
        asset('assets/frontend/images/anh8.jpg'),
    ];
@endphp

<section class="about-pro-hero page-hero page-hero--about" @if(!empty($aboutHeroImage)) style="background-image: linear-gradient(120deg, rgba(7, 15, 32, 0.78), rgba(14, 58, 130, 0.72)), url('{{ $aboutHeroImage }}'); background-size: cover; background-position: center;" @endif>
    <div class="container">
        <p class="about-pro-kicker">Tech Sewing Machine</p>
        <h1>{{ $siteContent['page_about_heading'] ?? ($siteContent['about_title'] ?? 'Gioi thieu doanh nghiep') }}</h1>
        <p>{{ $siteContent['page_about_desc'] ?? ($siteContent['about_subtitle'] ?? 'Nang luc may moc, doi ngu ky thuat va dich vu sau ban hang duoc trien khai dong bo cho nha may may mac.') }}</p>
        <div class="about-pro-stats">
            <div><strong>15+</strong><span>Nam kinh nghiem</span></div>
            <div><strong>300+</strong><span>Du an trien khai</span></div>
            <div><strong>24/7</strong><span>Ho tro ky thuat</span></div>
            <div><strong>63</strong><span>Tinh thanh phuc vu</span></div>
        </div>
    </div>
</section>

<main class="container about-pro-main">
    <section class="about-pro-section about-pro-intro">
        <div class="about-pro-intro-grid">
            <article class="about-pro-card">
                <h2>{{ $siteContent['about_company_name'] ?? 'Tech Sewing Machine' }}</h2>
                <p>{{ $siteContent['about_intro'] ?? 'Chung toi cung cap giai phap may cong nghiep toan dien, tu tu van cau hinh den lap dat va dao tao van hanh.' }}</p>
                <p>{{ $siteContent['about_body'] ?? 'Doi ngu tap trung vao tinh on dinh cua day chuyen, nang suat va chi phi van hanh toi uu cho doanh nghiep may mac.' }}</p>
                @if(!empty($siteContent['about_slogan']))
                    <blockquote>{{ $siteContent['about_slogan'] }}</blockquote>
                @endif
            </article>
            <div class="about-pro-highlight">
                <h3>Nang luc trien khai</h3>
                <ul>
                    <li>Tu van setup day chuyen theo tung quy mo nha xuong.</li>
                    <li>Lap dat, can chinh va nghiem thu tai xuong.</li>
                    <li>Bao tri dinh ky, thay the linh kien nhanh.</li>
                    <li>Dao tao ky thuat cho to truong va cong nhan van hanh.</li>
                    <li>Dong hanh toi uu nang suat sau dau tu.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="about-pro-section">
        <div class="about-pro-headline">
            <h2>Hinh anh hoat dong</h2>
            <p>Goc van phong, xuong ky thuat, lap dat tai cong trinh va cac buoi huan luyen doi tac.</p>
        </div>
        <div class="about-pro-gallery">
            @foreach($aboutGallery as $image)
                <figure>
                    <img src="{{ $image }}" alt="Tech Sewing machine image {{ $loop->iteration }}">
                </figure>
            @endforeach
        </div>
    </section>

    <section class="about-pro-section">
        <div class="about-pro-headline">
            <h2>Quy trinh lam viec</h2>
        </div>
        <div class="about-pro-timeline">
            <article><span>01</span><h3>Khao sat nhu cau</h3><p>Danh gia san pham, luu luong va muc tieu nang suat.</p></article>
            <article><span>02</span><h3>De xuat giai phap</h3><p>Len cau hinh may, chi phi dau tu va ke hoach trien khai.</p></article>
            <article><span>03</span><h3>Lap dat va dao tao</h3><p>Ban giao van hanh voi quy trinh kiem tra chat luong ro rang.</p></article>
            <article><span>04</span><h3>Dong hanh lau dai</h3><p>Ho tro bao tri va toi uu day chuyen theo tung giai doan.</p></article>
        </div>
    </section>

    <section class="about-pro-section">
        <div class="about-pro-headline">
            <h2>Thuong hieu dong hanh</h2>
            <p>Danh sach logo doi tac va khach hang tieu bieu.</p>
        </div>
        <div class="about-pro-logos">
            @forelse($partners as $partner)
                <a href="{{ $partner->url ?: 'javascript:void(0)' }}" @if($partner->url) target="_blank" rel="noopener noreferrer" @endif>
                    @if($partner->logo_url)
                        <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}">
                    @else
                        <span>{{ $partner->name }}</span>
                    @endif
                </a>
            @empty
                <div><span>Garment One</span></div>
                <div><span>Factory Plus</span></div>
                <div><span>SeamPro</span></div>
                <div><span>NeedleTech</span></div>
                <div><span>Viet Stitch</span></div>
                <div><span>Industrial Wear</span></div>
            @endforelse
        </div>
    </section>

    <section class="about-pro-section about-pro-cta">
        <div>
            <h2>San sang nang cap day chuyen?</h2>
            <p>Ket noi de nhan de xuat cau hinh may phu hop quy mo va ngan sach cua doanh nghiep.</p>
        </div>
        <div class="about-pro-cta-actions">
            <a href="{{ route('contact') }}" class="btn-service">Nhan tu van</a>
            <a href="{{ route('products.index') }}" class="btn-service secondary">Xem danh muc may</a>
        </div>
    </section>
</main>
@endsection

@push('page_styles')
<style>
    .about-pro-hero { padding: 7rem 0 5.5rem; color: #fff; position: relative; overflow: hidden; }
    .about-pro-kicker { letter-spacing: .16em; text-transform: uppercase; font-size: .78rem; font-weight: 800; margin-bottom: 1rem; opacity: .85; }
    .about-pro-hero h1 { font-size: clamp(2rem, 4vw, 3.4rem); margin-bottom: 1rem; }
    .about-pro-hero p { max-width: 860px; }
    .about-pro-stats { margin-top: 2rem; display: grid; gap: .9rem; grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .about-pro-stats div { background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.2); border-radius: 12px; padding: .9rem 1rem; backdrop-filter: blur(2px); }
    .about-pro-stats strong { display: block; font-size: 1.5rem; margin-bottom: .2rem; }
    .about-pro-stats span { font-size: .92rem; opacity: .92; }
    .about-pro-main { padding: 3.2rem 0 4.2rem; }
    .about-pro-section { margin-bottom: 3rem; }
    .about-pro-intro-grid { display: grid; grid-template-columns: 1.25fr .95fr; gap: 1.3rem; align-items: stretch; }
    .about-pro-card, .about-pro-highlight { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 12px 35px rgba(15, 23, 42, .08); padding: 1.5rem; }
    .about-pro-card h2 { margin-bottom: .8rem; }
    .about-pro-card p { margin-bottom: .8rem; line-height: 1.75; }
    .about-pro-card blockquote { margin: .9rem 0 0; border-left: 4px solid #0ea5e9; padding-left: .85rem; font-weight: 700; color: #0f172a; }
    .about-pro-highlight h3 { margin-bottom: .8rem; }
    .about-pro-highlight ul { margin: 0; padding-left: 1rem; display: grid; gap: .55rem; line-height: 1.6; }
    .about-pro-headline { margin-bottom: 1rem; }
    .about-pro-headline p { color: #475569; }
    .about-pro-gallery { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: .8rem; }
    .about-pro-gallery figure { margin: 0; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 26px rgba(15, 23, 42, .1); }
    .about-pro-gallery img { width: 100%; height: 190px; object-fit: cover; display: block; transition: transform .35s ease; }
    .about-pro-gallery img:hover { transform: scale(1.05); }
    .about-pro-timeline { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: .9rem; }
    .about-pro-timeline article { background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #dbeafe; border-radius: 14px; padding: 1rem; }
    .about-pro-timeline span { display: inline-flex; width: 32px; height: 32px; border-radius: 999px; align-items: center; justify-content: center; background: #0ea5e9; color: #fff; font-weight: 800; margin-bottom: .55rem; }
    .about-pro-timeline h3 { margin-bottom: .4rem; font-size: 1.02rem; }
    .about-pro-timeline p { color: #475569; line-height: 1.6; }
    .about-pro-logos { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: .75rem; }
    .about-pro-logos a, .about-pro-logos > div { min-height: 80px; border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; padding: .7rem; box-shadow: 0 8px 22px rgba(15, 23, 42, .06); }
    .about-pro-logos img { max-height: 46px; width: auto; max-width: 100%; object-fit: contain; filter: grayscale(100%); opacity: .92; transition: all .2s ease; }
    .about-pro-logos a:hover img { filter: grayscale(0); opacity: 1; transform: scale(1.04); }
    .about-pro-logos span { font-weight: 700; color: #334155; font-size: .95rem; text-align: center; }
    .about-pro-cta { background: linear-gradient(110deg, #0f172a 0%, #0b3a78 52%, #0ea5e9 100%); color: #fff; border-radius: 20px; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    .about-pro-cta h2 { margin-bottom: .35rem; }
    .about-pro-cta-actions { display: flex; gap: .6rem; flex-wrap: wrap; }
    @media (max-width: 1100px) {
        .about-pro-gallery { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .about-pro-logos { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .about-pro-timeline { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 900px) {
        .about-pro-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .about-pro-intro-grid { grid-template-columns: 1fr; }
        .about-pro-gallery { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .about-pro-cta { flex-direction: column; align-items: flex-start; }
    }
    @media (max-width: 640px) {
        .about-pro-hero { padding: 5.4rem 0 4.3rem; }
        .about-pro-gallery { grid-template-columns: 1fr; }
        .about-pro-logos { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .about-pro-main { padding-top: 2.2rem; }
    }
</style>
@endpush
