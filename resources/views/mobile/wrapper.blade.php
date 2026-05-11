@props(['class' => ''])

<div class="md:hidden {{ $class }}">
    {{ $slot }}
</div>
