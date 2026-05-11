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
                                <a href="{{ route('news.index') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Tin tức</a>
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
                    {{ $category->description ?: 'Tất cả bài viết thuộc chuyên mục ' . $category->name . '.' }}
                </p>
            </div>
        </div>
    </section>

    {{-- News Grid --}}
    <section class="py-24 bg-white dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-16">
                
                <!-- Sidebar -->
                <aside class="w-full lg:w-64 space-y-12">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900 dark:text-white mb-6">Danh mục tin</h4>
                        <div class="space-y-4">
                            <a href="{{ route('news.index') }}" class="block text-sm font-bold text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-all">Tất cả bài viết</a>
                            @foreach(App\Models\Category::where('type', 'news')->get() as $cat)
                                <a href="{{ route('news.category', $cat->slug) }}" class="block text-sm font-bold {{ ($category->id == $cat->id) ? 'text-zinc-900 dark:text-white underline decoration-2 underline-offset-8' : 'text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }} transition-all">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>

                <!-- Articles Grid -->
                <div class="flex-grow">
                    @if($posts->isEmpty())
                        <div class="py-32 text-center bg-zinc-50 dark:bg-zinc-900 rounded-3xl border-2 border-dashed border-zinc-200 dark:border-zinc-800">
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Chưa có bài viết</h3>
                            <p class="text-zinc-500 dark:text-zinc-400">Danh mục này hiện đang được cập nhật nội dung.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-16">
                            @foreach($posts as $post)
                                <x-news.card :post="$post" />
                            @endforeach
                        </div>

                        <div class="mt-20">
                            {{ $posts->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>
@endsection
