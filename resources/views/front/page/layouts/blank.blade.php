@extends('front.layouts.app')

@section('content')
    <div style="padding-top: var(--header-total);">
        @if($page->layout_mode === 'builder')
            {!! $html !!}
        @else
            {!! $page->content !!}
        @endif
    </div>
@endsection
