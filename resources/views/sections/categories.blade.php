@props(['categories'])

<section class="py-24 bg-zinc-50 dark:bg-zinc-900 border-y border-zinc-200 dark:border-zinc-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between mb-16 gap-8">
            <div class="text-center md:text-left">
                <span class="text-xs font-bold uppercase tracking-[0.3em] text-zinc-400 mb-4 block">Hệ sinh thái thiết bị</span>
                <h2 class="text-3xl md:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight mb-4">Danh mục giải pháp</h2>
                <p class="text-zinc-500 dark:text-zinc-400 font-medium italic">
                    {{ \App\Models\Setting::getValue('site_slogan', 'Dẫn đầu công nghệ - Kiến tạo năng suất cho mọi xưởng may.') }}
                </p>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden lg:flex -space-x-3">
                    @for($i=0; $i<5; $i++)
                        <div class="w-10 h-10 rounded-full border-2 border-white dark:border-zinc-900 bg-zinc-200 dark:bg-zinc-800"></div>
                    @endfor
                </div>
                <span class="text-sm font-bold text-zinc-500 uppercase tracking-widest">5000+ Thiết bị đã bàn giao</span>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $cat)
                @php
                    $catImg = $cat->image ? (str_starts_with($cat->image, 'http') ? $cat->image : \Illuminate\Support\Facades\Storage::url($cat->image)) : asset('assets/frontend/images/anh1.jpg');
                @endphp
                <a href="{{ route('products.category', $cat->slug) }}" class="group bg-white dark:bg-zinc-950 p-8 rounded-3xl border border-zinc-100 dark:border-zinc-800 text-center hover:bg-zinc-900 dark:hover:bg-white transition-all duration-500 shadow-sm hover:shadow-2xl hover:-translate-y-2">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-2xl overflow-hidden bg-zinc-50 dark:bg-zinc-900 p-2 group-hover:scale-110 transition-transform">
                        <img src="{{ $catImg }}" alt="{{ $cat->name }}" class="w-full h-full object-contain">
                    </div>
                    <h4 class="text-sm font-bold text-zinc-900 dark:text-white group-hover:text-white dark:group-hover:text-zinc-900 transition-colors uppercase tracking-tight">{{ $cat->name }}</h4>
                </a>
            @endforeach
        </div>
    </div>
</section>
