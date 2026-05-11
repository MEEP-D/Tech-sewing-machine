@extends('front.layouts.app')

@section('content')
    <div style="padding-top: calc(var(--header-total) + 20px); padding-bottom: 60px;">
        @if($page->image)
            <div class="v-article-cover v-reveal" style="max-height: 400px; margin-bottom: 40px; border-radius: 0;">
                <img src="{{ asset($page->image) }}" alt="{{ $page->title }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
            </div>
        @endif

        <div class="v-reveal" style="padding: 0 5%;">
            @if($page->layout_mode === 'builder')
                {!! $html !!}
            @else
                <div class="prose" style="max-width: 100%">{!! $page->content !!}</div>
            @endif
        </div>
    </div>
@endsection
