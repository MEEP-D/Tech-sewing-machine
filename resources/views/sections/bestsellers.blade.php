@props(['products'])

<section class="py-32 bg-white dark:bg-zinc-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8">
            <div class="max-w-2xl">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400 mb-4 block">Thị trường tin dùng</span>
                <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight">Sản phẩm bán chạy nhất</h2>
                <p class="text-lg text-zinc-600 dark:text-zinc-400 mt-6 font-medium">Những giải pháp được lựa chọn nhiều nhất bởi các xưởng may công nghiệp hàng đầu.</p>
            </div>
            <a href="{{ route('products.index') }}" class="group inline-flex items-center gap-3 text-zinc-900 dark:text-white font-bold">
                Khám phá toàn bộ danh mục
                <div class="w-10 h-10 rounded-full border border-zinc-200 dark:border-zinc-800 flex items-center justify-center group-hover:bg-zinc-900 group-hover:text-white dark:group-hover:bg-white dark:group-hover:text-zinc-900 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($products->take(4) as $product)
                @php
                    $imageUrl = (str_starts_with($product->image, 'http') ? $product->image : ($product->image ? \Illuminate\Support\Facades\Storage::url($product->image) : asset('assets/frontend/images/placeholder.jpg')));
                @endphp
                <div class="flex flex-col sm:flex-row gap-8 p-8 rounded-[2rem] bg-zinc-50 dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 hover:shadow-2xl transition-all group">
                    <div class="w-full sm:w-48 aspect-square rounded-2xl overflow-hidden bg-white dark:bg-zinc-800 flex items-center justify-center p-4">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="flex-grow flex flex-col justify-between py-2">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">{{ $product->brand ?? 'Chính hãng' }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">{{ $product->origin ?? 'Global' }}</span>
                            </div>
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ $product->name }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2 leading-relaxed">
                                {{ $product->short_description ?: 'Sản phẩm đạt tiêu chuẩn chất lượng quốc tế, phù hợp cho nhiều loại vải.' }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between mt-8">
                            <span class="text-xl font-black text-zinc-900 dark:text-white">
                                {{ is_numeric($product->price) ? number_format($product->price) . ' ₫' : ($product->price ?: 'Liên hệ') }}
                            </span>
                            <a href="{{ route('products.show', $product->slug) }}" class="text-sm font-bold text-zinc-900 dark:text-white underline underline-offset-8 decoration-2 decoration-zinc-200 dark:decoration-zinc-800 hover:decoration-zinc-900 dark:hover:decoration-white transition-all">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
