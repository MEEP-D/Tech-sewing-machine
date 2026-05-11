@props(['section', 'image'])

@php
    $title = $section->title ?? 'Tech Sewing Machine';
    $subtitle = $section->subtitle ?? 'Giải pháp may mặc công nghiệp';
    $content = $section->content ?? 'Chuyên cung cấp máy may công nghiệp và giải pháp vận hành tối ưu cho nhà máy.';
    $btnText = $section->style_config['button_text'] ?? 'Khám phá sản phẩm';
    $btnUrl = $section->style_config['button_url'] ?? route('products.index');
@endphp

<section class="relative min-h-[95vh] flex items-center overflow-hidden bg-zinc-900">
    <!-- Background Image with Parallax-like effect -->
    <div class="absolute inset-0 z-0">
        <img src="{{ $image }}" 
             alt="{{ $title }}" 
             class="w-full h-full object-cover scale-105 opacity-50">
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-20">
        <div class="max-w-3xl space-y-8" 
             x-data="{ show: false }" 
             x-init="setTimeout(() => show = true, 100)">
            
            <div x-show="show" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                {{ $subtitle }}
            </div>

            <h1 x-show="show" 
                x-transition:enter="transition ease-out duration-1000 delay-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="text-5xl md:text-7xl lg:text-8xl font-bold text-white tracking-tighter leading-[0.9]">
                {!! str_replace(' ', '<br class="hidden md:block">', $title) !!}
            </h1>

            <p x-show="show" 
               x-transition:enter="transition ease-out duration-1000 delay-500"
               x-transition:enter-start="opacity-0 -translate-y-4"
               x-transition:enter-end="opacity-100 translate-y-0"
               class="text-lg md:text-xl text-zinc-300 max-w-xl leading-relaxed font-medium">
                {{ $content }}
            </p>

            <div x-show="show" 
                 x-transition:enter="transition ease-out duration-1000 delay-700"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex flex-wrap gap-4 pt-4">
                <a href="{{ $btnUrl }}" class="px-8 py-4 bg-white text-zinc-900 rounded-full font-bold text-lg hover:bg-zinc-200 transition-all hover:scale-105 active:scale-95 shadow-xl shadow-white/10">
                    {{ $btnText }}
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-4 bg-transparent border border-white/30 text-white rounded-full font-bold text-lg hover:bg-white/10 transition-all active:scale-95">
                    Liên hệ tư vấn
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Indicator -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10 animate-bounce text-white/30">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </div>
</section>
