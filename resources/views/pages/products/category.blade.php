@extends('layouts.app')

@section('content')
    {{-- Header Section --}}
    <section class="pt-32 pb-20 bg-zinc-50 dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <nav class="flex mb-8" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs font-bold uppercase tracking-widest text-zinc-400">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Trang chủ</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <a href="{{ route('products.index') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Sản phẩm</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="text-zinc-900 dark:text-white">{{ $category->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-4xl md:text-6xl font-bold text-zinc-900 dark:text-white tracking-tight mb-6">
                    {{ $category->name }}
                </h1>
                <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed font-medium">
                    {{ $category->description ?: 'Khám phá những dòng máy chuyên dụng thuộc nhóm ' . $category->name . '.' }}
                </p>
            </div>
        </div>
    </section>

    {{-- Filters and Product Grid --}}
    <section class="py-20 bg-white dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-12">
                
                <!-- Sidebar Filters -->
                <aside class="w-full lg:w-64 space-y-10">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900 dark:text-white mb-6">Danh mục</h4>
                        <div class="space-y-3">
                            <a href="{{ route('products.index') }}" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">Tất cả sản phẩm</a>
                            @foreach(App\Models\Category::where('type', 'product')->whereNull('parent_id')->get() as $cat)
                                <a href="{{ route('products.category', $cat->slug) }}" class="block text-sm font-medium {{ ($category->id == $cat->id) ? 'text-zinc-900 dark:text-white underline decoration-2 underline-offset-4' : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' }} transition-colors">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <form action="{{ url()->current() }}" method="GET" class="space-y-10">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900 dark:text-white mb-6">Sắp xếp</h4>
                            <select name="sort" onchange="this.form.submit()" class="w-full bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm rounded-xl focus:ring-zinc-900 focus:border-zinc-900 block p-3 dark:bg-zinc-900 dark:border-zinc-800 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-white dark:focus:border-white outline-none appearance-none">
                                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                            </select>
                        </div>
                    </form>
                </aside>

                <!-- Product Grid -->
                <div class="flex-grow">
                    @if($products->isEmpty())
                        <div class="py-32 text-center bg-zinc-50 dark:bg-zinc-900 rounded-3xl border-2 border-dashed border-zinc-200 dark:border-zinc-800">
                            <svg class="w-16 h-16 text-zinc-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Chưa có sản phẩm</h3>
                            <p class="text-zinc-500 dark:text-zinc-400">Chúng tôi đang cập nhật sản phẩm cho danh mục này.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                            @foreach($products as $product)
                                <x-product.card :product="$product" />
                            @endforeach
                        </div>

                        <div class="mt-20">
                            {{ $products->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>
@endsection
