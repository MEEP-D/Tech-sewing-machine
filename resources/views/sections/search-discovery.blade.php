@props(['products'])

@php
    $allProducts = $products->isNotEmpty() ? $products : collect();
    $row1 = $allProducts->take(4);
    $row2 = $allProducts->skip(4)->take(4);
    if($row2->isEmpty()) $row2 = $row1;
@endphp

<section class="py-24 bg-white dark:bg-zinc-950 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
        {{-- Promo Banner Container --}}
        <div class="relative bg-zinc-900 rounded-[3rem] p-12 md:p-24 overflow-hidden group">
            <div class="absolute inset-0 opacity-40">
                <img src="{{ asset('assets/frontend/images/anh1.jpg') }}" class="w-full h-full object-cover grayscale group-hover:scale-105 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-950/80 to-transparent"></div>
            </div>
            
            <div class="relative z-10 max-w-2xl">
                <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-widest mb-8 backdrop-blur-md border border-white/10">Ưu đãi độc quyền</span>
                <h2 class="text-4xl md:text-6xl font-bold text-white tracking-tighter mb-8 leading-none">
                    Giải pháp may <br> <span class="text-zinc-400">hiệu suất cao.</span>
                </h2>
                <p class="text-lg text-zinc-400 mb-12 leading-relaxed">
                    Nâng cấp hệ thống sản xuất ngay hôm nay với các dòng máy Brother S-7300A và Juki chính hãng. Hỗ trợ lắp đặt và đào tạo miễn phí.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="px-8 py-4 bg-white text-zinc-900 rounded-2xl font-bold hover:scale-105 transition-all">Liên hệ nhận báo giá</a>
                    <a href="{{ route('products.index') }}" class="px-8 py-4 bg-white/10 text-white rounded-2xl font-bold border border-white/20 backdrop-blur-md hover:bg-white/20 transition-all">Xem bảng giá</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Staggered Product Rows (Small and Sleek) --}}
    @if($allProducts->isNotEmpty())
    <div class="space-y-8 opacity-50 hover:opacity-100 transition-opacity duration-700">
        {{-- Row 1 --}}
        <div class="flex whitespace-nowrap animate-marquee-fast hover:[animation-play-state:paused]">
            @foreach($row1->concat($row1)->concat($row1)->concat($row1) as $p)
                @php
                    $img = $p->image;
                    if ($img && !str_starts_with($img, 'http')) {
                        $img = (str_starts_with($img, 'assets/') ? asset($img) : \Illuminate\Support\Facades\Storage::url($img));
                    }
                    $img = $img ?: asset('assets/frontend/images/placeholder.jpg');
                @endphp
                <div class="inline-block px-3">
                    <div class="flex items-center gap-4 bg-zinc-50 dark:bg-zinc-900 pr-8 rounded-full border border-zinc-100 dark:border-zinc-800">
                        <div class="w-16 h-16 rounded-full overflow-hidden p-2">
                            <img src="{{ $img }}" class="w-full h-full object-contain">
                        </div>
                        <span class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-tight">{{ $p->name }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Row 2 --}}
        <div class="flex whitespace-nowrap animate-marquee-reverse-fast hover:[animation-play-state:paused]">
            @foreach($row2->concat($row2)->concat($row2)->concat($row2) as $p)
                @php
                    $img = $p->image;
                    if ($img && !str_starts_with($img, 'http')) {
                        $img = (str_starts_with($img, 'assets/') ? asset($img) : \Illuminate\Support\Facades\Storage::url($img));
                    }
                    $img = $img ?: asset('assets/frontend/images/placeholder.jpg');
                @endphp
                <div class="inline-block px-3">
                    <div class="flex items-center gap-4 bg-zinc-50 dark:bg-zinc-900 pr-8 rounded-full border border-zinc-100 dark:border-zinc-800">
                        <div class="w-16 h-16 rounded-full overflow-hidden p-2">
                            <img src="{{ $img }}" class="w-full h-full object-contain">
                        </div>
                        <span class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-tight">{{ $p->name }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</section>

<style>
    @keyframes marquee-fast { 0% { transform: translateX(0); } 100% { transform: translateX(-33.33%); } }
    @keyframes marquee-reverse-fast { 0% { transform: translateX(-33.33%); } 100% { transform: translateX(0); } }
    .animate-marquee-fast { animation: marquee-fast 60s linear infinite; }
    .animate-marquee-reverse-fast { animation: marquee-reverse-fast 60s linear infinite; }
</style>
