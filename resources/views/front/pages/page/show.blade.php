@extends('layouts.app')

@section('content')
    {{-- Header Section --}}
    <section class="pt-32 pb-20 bg-zinc-50 dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-6xl font-bold text-zinc-900 dark:text-white tracking-tight mb-6">
                    {{ $page->title }}
                </h1>
                @if($page->subtitle)
                    <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed font-medium">
                        {{ $page->subtitle }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <section class="py-24 bg-white dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-zinc dark:prose-invert max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-a:text-zinc-900 dark:prose-a:text-white prose-p:text-lg prose-p:leading-relaxed">
                {!! $html !!}
            </div>
        </div>
    </section>
@endsection
