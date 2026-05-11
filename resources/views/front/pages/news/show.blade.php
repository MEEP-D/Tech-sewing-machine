@extends('layouts.app')

@section('content')
    @php
        $imageUrl = (str_starts_with($post->image, 'http') ? $post->image : ($post->image ? \Illuminate\Support\Facades\Storage::url($post->image) : asset('assets/frontend/images/placeholder-news.jpg')));
    @endphp

    <article class="pt-32 pb-24 bg-white dark:bg-zinc-950">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <nav class="flex mb-12" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-bold uppercase tracking-widest text-zinc-400">
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
                            <span class="text-zinc-900 dark:text-white line-clamp-1">{{ $post->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <header class="mb-16">
                <div class="flex items-center gap-4 mb-8">
                    @if($post->category)
                        <span class="px-3 py-1 bg-zinc-100 dark:bg-zinc-900 text-zinc-900 dark:text-white text-[10px] font-bold uppercase tracking-widest rounded-full">
                            {{ $post->category->name }}
                        </span>
                    @endif
                    <time class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">
                        {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                    </time>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-zinc-900 dark:text-white tracking-tight leading-[1.1] mb-10">
                    {{ $post->title }}
                </h1>
                
                @if($post->excerpt)
                    <p class="text-xl text-zinc-500 dark:text-zinc-400 font-medium leading-relaxed mb-12">
                        {{ $post->excerpt }}
                    </p>
                @endif

                <div class="aspect-video rounded-3xl overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                    <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                </div>
            </header>

            <div class="prose prose-zinc dark:prose-invert max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-a:text-zinc-900 dark:prose-a:text-white prose-p:text-lg prose-p:leading-relaxed prose-img:rounded-3xl">
                {!! $post->content !!}
            </div>

            <footer class="mt-20 pt-10 border-t border-zinc-100 dark:border-zinc-900">
                <div class="flex flex-wrap items-center justify-between gap-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-zinc-900 dark:bg-white flex items-center justify-center text-white dark:text-zinc-900 font-bold">
                            {{ substr($post->author->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-zinc-400 uppercase tracking-widest">Đăng bởi</span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $post->author->name ?? 'Administrator' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">Chia sẻ:</span>
                        <div class="flex gap-2">
                            <button class="w-10 h-10 rounded-full border border-zinc-200 dark:border-zinc-800 flex items-center justify-center text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:border-zinc-900 dark:hover:border-white transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </footer>

            {{-- Related Posts --}}
            @if($relatedPosts->isNotEmpty())
                <div class="mt-32 pt-20 border-t border-zinc-100 dark:border-zinc-900">
                    <h2 class="text-3xl font-bold text-zinc-900 dark:text-white tracking-tight mb-12">Bài viết liên quan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        @foreach($relatedPosts as $rel)
                            <x-news.card :post="$rel" />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </article>
@endsection
