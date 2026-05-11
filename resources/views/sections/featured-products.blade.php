@props(['products', 'title', 'subtitle', 'content'])

<section class="py-24 bg-white dark:bg-zinc-950 overflow-hidden" id="products">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="mb-16 max-w-2xl" x-data="{ revealed: false }" x-intersect="revealed = true">
            <span x-show="revealed" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400 mb-4 block">
                {{ $subtitle ?? 'Danh mục sản phẩm' }}
            </span>
            <h2 x-show="revealed" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight mb-6">
                {{ $title ?? 'Sản phẩm tiêu biểu' }}
            </h2>
            <p x-show="revealed" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed font-medium">
                {{ $content ?? 'Khám phá những dòng máy may công nghiệp thế hệ mới, tích hợp công nghệ tự động hóa tối ưu.' }}
            </p>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12">
            @forelse($products as $product)
                <div x-data="{ revealed: false }" x-intersect="revealed = true" x-show="revealed" x-transition:enter="transition ease-out duration-700 delay-{{ $loop->index * 100 }}" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <x-product.card :product="$product" />
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-zinc-50 dark:bg-zinc-900 rounded-3xl border-2 border-dashed border-zinc-200 dark:border-zinc-800">
                    <p class="text-zinc-500 dark:text-zinc-400 font-medium">Chưa có sản phẩm nào được hiển thị.</p>
                </div>
            @endforelse
        </div>

        <!-- Bottom Action -->
        <div class="mt-16 text-center">
            <a href="{{ route('products.index') }}" class="group inline-flex items-center gap-2 text-zinc-900 dark:text-white font-bold hover:gap-4 transition-all">
                Xem tất cả sản phẩm
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</section>
