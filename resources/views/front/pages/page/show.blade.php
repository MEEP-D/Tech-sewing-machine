@extends('front.layouts.app')

@section('content')
<section class="container" style="padding: 2rem 1.5rem;">
    <div class="section-header"><h1 class="section-title">{{ $page->title }}</h1></div>
    <div>{!! $html !!}</div>
</section>
@endsection
