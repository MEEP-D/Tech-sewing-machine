@props(['product'])

@php
    $imageUrl = $product->image_url; // Assuming an accessor exists, or handle manually
    if (!$imageUrl) {
        $imageUrl = (str_starts_with($product->image, 'http') ? $product->image : ($product->image ? \Illuminate\Support\Facades\Storage::url($product->image) : asset('assets/frontend/images/placeholder.jpg')));
    }
@endphp

<div class="group relative bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300 hover:shadow-2xl hover:shadow-zinc-900/5">
    <!-- Image -->
    <a href="{{ route('products.show', $product->slug) }}" class="block aspect-[4/5] overflow-hidden bg-zinc-100 dark:bg-zinc-800">
        <img src="{{ $imageUrl }}" 
             alt="{{ $product->name }}" 
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
             loading="lazy">
    </a>

    <!-- Content -->
    <div class="p-6">
        <div class="flex justify-between items-start mb-2">
            @if($product->category)
                <a href="{{ route('products.category', $product->category->slug) }}" class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors">
                    {{ $product->category->name }}
                </a>
            @endif
            @if($product->is_featured)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 uppercase tracking-tighter">
                    Nổi bật
                </span>
            @endif
        </div>

        <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2 line-clamp-1 group-hover:text-zinc-700 dark:group-hover:text-zinc-300 transition-colors">
            <a href="{{ route('products.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        <div class="flex items-center justify-between mt-6">
            <div class="flex flex-col">
                <span class="text-xs text-zinc-500 dark:text-zinc-500 font-medium">Giá bán</span>
                <span class="text-xl font-bold text-zinc-900 dark:text-white">
                    {{ is_numeric($product->price) ? number_format($product->price) . ' ₫' : ($product->price ?: 'Liên hệ') }}
                </span>
            </div>
            
            <a href="{{ route('products.show', $product->slug) }}" class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-900 dark:text-white group-hover:bg-zinc-900 group-hover:text-white dark:group-hover:bg-white dark:group-hover:text-zinc-900 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</div>
