@extends('layouts.app')

@section('content')
    @php
        $hero = $sections['hero'] ?? null;
        $strip = $sections['strip'] ?? null;
        $showcase = $sections['product-showcase'] ?? null;
        $value = $sections['value-section'] ?? null;
        $tool = $sections['tool-section'] ?? null;
        $hub = $sections['content-hub'] ?? null;
        $partnerSection = $sections['partners'] ?? null;
        $cta = $sections['cta'] ?? null;
    @endphp

    {{-- Banners Carousel if exists --}}
    @if(($banners ?? collect())->isNotEmpty())
        {{-- I can create a Banner component later --}}
    @endif

    {{-- Hero Section --}}
    @if($hero)
        @include('sections.hero', [
            'section' => $hero, 
            'image' => $homeHeroImage
        ])
    @endif

    {{-- Product Categories Showcase --}}
    @include('sections.categories', ['categories' => $productCategories])

    {{-- Search & Discovery Hub (Promo Banner) --}}
    @include('sections.search-discovery', ['products' => $featuredProducts->concat($latestProducts)])

    {{-- Company Slogan / Vision --}}
    @include('sections.exclusive-product')

    {{-- Product Showcase (Grid) --}}
    @if($showcase)
        @include('sections.featured-products', [
            'products' => $featuredProducts->skip(1),
            'title' => $showcase->title,
            'subtitle' => $showcase->subtitle,
            'content' => $showcase->content
        ])
    @endif

    {{-- Value Proposition --}}
    @if($value)
        <section class="py-24 bg-zinc-50 dark:bg-zinc-950 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                    <div class="relative" x-data="{ revealed: false }" x-intersect="revealed = true">
                        <div x-show="revealed" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 -translate-x-12" x-transition:enter-end="opacity-100 translate-x-0" class="aspect-square rounded-3xl overflow-hidden shadow-2xl">
                            <img src="{{ (str_starts_with($value->image, 'http') ? $value->image : ($value->image ? \Illuminate\Support\Facades\Storage::url($value->image) : asset('assets/frontend/images/placeholder.jpg'))) }}" 
                                 alt="{{ $value->title }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-zinc-900 dark:bg-white rounded-3xl -z-10 hidden lg:block"></div>
                    </div>
                    <div class="space-y-8" x-data="{ revealed: false }" x-intersect="revealed = true">
                        <span x-show="revealed" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400 block">
                            {{ $value->subtitle ?? 'Giá trị cốt lõi' }}
                        </span>
                        <h2 x-show="revealed" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight leading-tight">
                            {{ $value->title }}
                        </h2>
                        <p x-show="revealed" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            {{ $value->content }}
                        </p>
                        
                        <div class="grid grid-cols-1 gap-6">
                            @foreach(['feature_1', 'feature_2', 'feature_3'] as $f)
                                @if(isset($value->style_config[$f.'_title']))
                                    <div x-show="revealed" x-transition:enter="transition ease-out duration-700 delay-{{ 300 + ($loop->index * 100) }}" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex gap-4">
                                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-zinc-900 dark:bg-white flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white dark:text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-zinc-900 dark:text-white font-bold">{{ $value->style_config[$f.'_title'] }}</h4>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-500">{{ $value->style_config[$f.'_desc'] ?? '' }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div x-show="revealed" x-transition:enter="transition ease-out duration-700 delay-600" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="pt-6">
                            <a href="{{ route('about') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-zinc-900 text-white rounded-full font-bold dark:bg-white dark:text-zinc-900 hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-all shadow-xl shadow-zinc-900/10">
                                {{ $value->style_config['button_text'] ?? 'Tìm hiểu thêm' }}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Service Process --}}
    @include('sections.process')

    {{-- Content Hub (Recent News) --}}
    @if($hub)
        <section class="py-24 bg-white dark:bg-zinc-950">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                    <div class="max-w-2xl">
                        <span class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400 mb-4 block">
                            {{ $hub->subtitle ?? 'Cập nhật tri thức' }}
                        </span>
                        <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight">
                            {{ $hub->title ?? 'Tin tức & Công nghệ' }}
                        </h2>
                    </div>
                    <a href="{{ route('news.index') }}" class="group inline-flex items-center gap-2 text-zinc-900 dark:text-white font-bold hover:gap-4 transition-all">
                        Xem tất cả bài viết
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                    @foreach($latestPosts as $post)
                        <x-news.card :post="$post" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Partners --}}
    @if($partnerSection && $partners->isNotEmpty())
        <section class="py-20 bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-xs font-bold uppercase tracking-[0.3em] text-zinc-400 mb-12">Đối tác chiến lược</p>
                <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                    @foreach($partners as $partner)
                        <div class="h-8 md:h-12 w-auto">
                            @if($partner->logo)
                                <img src="{{ (str_starts_with($partner->logo, 'http') ? $partner->logo : \Illuminate\Support\Facades\Storage::url($partner->logo)) }}" 
                                     alt="{{ $partner->name }}"
                                     class="h-full w-auto object-contain">
                            @else
                                <span class="text-xl font-black text-zinc-400 tracking-tighter uppercase">{{ $partner->name }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Trust & Reach --}}
    @include('sections.trust')

    {{-- CTA Parallax Section --}}
    @if($cta)
        <section class="relative py-32 overflow-hidden bg-zinc-900">
            <div class="absolute inset-0">
                <img src="{{ (str_starts_with($cta->image, 'http') ? $cta->image : ($cta->image ? \Illuminate\Support\Facades\Storage::url($cta->image) : asset('assets/frontend/images/placeholder.jpg'))) }}" 
                     alt="{{ $cta->title }}" 
                     class="w-full h-full object-cover opacity-40">
                <div class="absolute inset-0 bg-zinc-950/60"></div>
            </div>
            <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-4xl md:text-6xl font-bold text-white tracking-tight mb-8">
                    {{ $cta->title ?? 'Sẵn sàng nâng tầm công nghệ?' }}
                </h2>
                <p class="text-xl text-zinc-300 mb-12 leading-relaxed">
                    {{ $cta->content ?? 'Liên hệ ngay để nhận được sự tư vấn chuyên nghiệp nhất cho dây chuyền sản xuất của bạn.' }}
                </p>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-white text-zinc-900 rounded-full font-bold text-lg hover:bg-zinc-200 transition-all hover:scale-105 shadow-2xl shadow-white/5">
                    {{ $cta->style_config['button_text'] ?? 'Gửi yêu cầu báo giá' }}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.827-1.233L3 20l1.326-3.945A8.959 8.959 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </a>
            </div>
        </section>
    @endif

@endsection
