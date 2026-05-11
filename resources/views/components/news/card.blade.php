@props(['post'])

@php
    $imageUrl = (str_starts_with($post->image, 'http') ? $post->image : ($post->image ? \Illuminate\Support\Facades\Storage::url($post->image) : asset('assets/frontend/images/placeholder-news.jpg')));
@endphp

<article class="group">
    <a href="{{ route('news.show', $post->slug) }}" class="block mb-6 overflow-hidden rounded-2xl aspect-video bg-zinc-100 dark:bg-zinc-800">
        <img src="{{ $imageUrl }}" 
             alt="{{ $post->title }}" 
             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
             loading="lazy">
    </a>

    <div class="space-y-3">
        <div class="flex items-center gap-3">
            @if($post->category)
                <a href="{{ route('news.category', $post->category->slug) }}" class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">
                    {{ $post->category->name }}
                </a>
            @endif
            <span class="w-1 h-1 rounded-full bg-zinc-300 dark:bg-zinc-700"></span>
            <time class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">
                {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
            </time>
        </div>

        <h3 class="text-xl font-bold text-zinc-900 dark:text-white leading-tight group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors">
            <a href="{{ route('news.show', $post->slug) }}">
                {{ $post->title }}
            </a>
        </h3>

        <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 leading-relaxed">
            {{ $post->excerpt ?: 'Khám phá những thông tin mới nhất về công nghệ máy may và xu hướng ngành may mặc.' }}
        </p>
    </div>
</article>
