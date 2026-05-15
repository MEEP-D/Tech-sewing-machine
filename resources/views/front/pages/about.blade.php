@extends('front.layouts.app')

@section('content')
<section class="about-hero">
    <div class="container">
        <h1 style="font-size: 3.5rem; font-weight: 700;">{{ $siteContent['about_title'] ?? '' }}</h1>
        <p style="font-size: 1.2rem; opacity: 0.9;">{{ $siteContent['about_subtitle'] ?? '' }}</p>
    </div>
</section>

<main class="container">
    <div class="about-content">
        <div class="about-card">
            <h2 class="about-title">{{ $siteContent['about_company_name'] ?? '' }}</h2>
            <div class="about-text">
                <p>{{ $siteContent['about_intro'] ?? '' }}</p>
            </div>
            <div class="about-slogan">
                {{ $siteContent['about_slogan'] ?? '' }}
            </div>
            <div class="about-text">
                <p>{{ $siteContent['about_body'] ?? '' }}</p>
            </div>
            <div class="about-images">
                <img src="{{ asset('assets/frontend/images/anh7.jpg') }}" alt="Van phong">
                <img src="{{ asset('assets/frontend/images/anh8.jpg') }}" alt="Xuong may">
            </div>
        </div>
    </div>
</main>
@endsection
