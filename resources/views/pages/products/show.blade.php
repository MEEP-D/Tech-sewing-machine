@extends('layouts.app')

@section('content')
    @php
        $imageUrl = (str_starts_with($product->image, 'http') ? $product->image : ($product->image ? \Illuminate\Support\Facades\Storage::url($product->image) : asset('assets/frontend/images/placeholder.jpg')));
    @endphp

    <section class="pt-32 pb-24 bg-white dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <nav class="flex mb-12" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-bold uppercase tracking-widest text-zinc-400">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Trang chủ</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('products.index') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Sản phẩm</a>
                        </div>
                    </li>
                    @if($product->category)
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">{{ $product->category->name }}</a>
                        </div>
                    </li>
                    @endif
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-zinc-900 dark:text-white line-clamp-1">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24">
                
                <!-- Product Image -->
                <div class="space-y-6">
                    <div class="aspect-square bg-zinc-50 dark:bg-zinc-900 rounded-3xl overflow-hidden border border-zinc-200 dark:border-zinc-800 p-8 flex items-center justify-center">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $product->name }}" 
                             class="max-w-full max-h-full object-contain hover:scale-105 transition-transform duration-500">
                    </div>
                    
                    {{-- Gallery if exists --}}
                    @if($product->gallery && count($product->gallery) > 0)
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($product->gallery as $img)
                                <div class="aspect-square bg-zinc-50 dark:bg-zinc-900 rounded-xl overflow-hidden border border-zinc-100 dark:border-zinc-800 p-2 cursor-pointer hover:border-zinc-900 dark:hover:border-white transition-all">
                                    <img src="{{ (str_starts_with($img, 'http') ? $img : \Illuminate\Support\Facades\Storage::url($img)) }}" class="w-full h-full object-contain">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-10">
                    <div>
                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            @if($product->category)
                                <span class="px-3 py-1 bg-zinc-100 dark:bg-zinc-900 text-zinc-900 dark:text-white text-[10px] font-bold uppercase tracking-widest rounded-full">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                            @if($product->brand)
                                <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Brand: {{ $product->brand }}</span>
                            @endif
                            <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">SKU: {{ $product->sku ?? 'N/A' }}</span>
                        </div>

                        <h1 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight mb-6">
                            {{ $product->name }}
                        </h1>

                        <div class="text-3xl font-black text-zinc-900 dark:text-white mb-8">
                            {{ is_numeric($product->price) ? number_format($product->price) . ' ₫' : ($product->price ?: 'Liên hệ') }}
                        </div>

                        <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            {{ $product->short_description }}
                        </p>
                    </div>

                    <div class="pt-10 border-t border-zinc-100 dark:border-zinc-900 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('contact', ['product' => $product->id]) }}" class="flex-grow inline-flex items-center justify-center px-8 py-5 bg-zinc-900 text-white rounded-2xl font-bold text-lg dark:bg-white dark:text-zinc-900 hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-all shadow-xl shadow-zinc-900/10">
                            Nhận tư vấn & Báo giá
                        </a>
                        <button class="px-8 py-5 bg-zinc-50 border border-zinc-200 text-zinc-900 rounded-2xl font-bold text-lg dark:bg-zinc-900 dark:border-zinc-800 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all">
                            Lưu sản phẩm
                        </button>
                    </div>

                    <div class="pt-10 border-t border-zinc-100 dark:border-zinc-900 grid grid-cols-2 gap-8">
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-2">Trạng thái</span>
                            <span class="flex items-center gap-2 text-sm font-bold text-green-600">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                Còn hàng
                            </span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-2">Bảo hành</span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">12 Tháng chính hãng</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Description / Specs Tabs -->
            <div class="mt-32 border-t border-zinc-100 dark:border-zinc-900 pt-20" x-data="{ tab: 'description' }">
                <div class="flex gap-12 mb-12 border-b border-zinc-100 dark:border-zinc-900">
                    <button @click="tab = 'description'" :class="tab === 'description' ? 'text-zinc-900 dark:text-white border-b-2 border-zinc-900 dark:border-white' : 'text-zinc-400 border-b-2 border-transparent'" class="pb-6 text-sm font-bold uppercase tracking-widest transition-all">
                        Mô tả chi tiết
                    </button>
                    <button @click="tab = 'specs'" :class="tab === 'specs' ? 'text-zinc-900 dark:text-white border-b-2 border-zinc-900 dark:border-white' : 'text-zinc-400 border-b-2 border-transparent'" class="pb-6 text-sm font-bold uppercase tracking-widest transition-all">
                        Thông số kỹ thuật
                    </button>
                </div>

                <div x-show="tab === 'description'" x-cloak class="prose prose-zinc dark:prose-invert max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-a:text-zinc-900 dark:prose-a:text-white prose-p:leading-relaxed">
                    {!! $product->description !!}
                </div>

                <div x-show="tab === 'specs'" x-cloak class="max-w-4xl">
                    @if($product->specifications && count($product->specifications) > 0)
                        <div class="grid grid-cols-1 divide-y divide-zinc-100 dark:divide-zinc-900">
                            @foreach($product->specifications as $key => $value)
                                <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <span class="text-sm font-bold text-zinc-400 uppercase tracking-widest">{{ $key }}</span>
                                    <span class="md:col-span-2 text-zinc-900 dark:text-white font-medium">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-zinc-500 dark:text-zinc-400">Đang cập nhật thông số...</p>
                    @endif
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->isNotEmpty())
                <div class="mt-32 pt-20 border-t border-zinc-100 dark:border-zinc-900">
                    <h2 class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tight mb-12">Sản phẩm liên quan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($relatedProducts as $rel)
                            <x-product.card :product="$rel" />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection
