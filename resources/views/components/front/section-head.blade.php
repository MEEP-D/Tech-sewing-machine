<div {{ $attributes->merge(['class' => 'v-section-head v-reveal']) }}>
    @if($kicker ?? $slot->kicker ?? null)
        <span class="v-section-kicker">{{ $kicker ?? $slot->kicker }}</span>
    @endif
    
    @if($title ?? $slot->title ?? null)
        <h2>{{ $title ?? $slot->title }}</h2>
    @endif
    
    @if($content ?? $slot->content ?? null)
        <p>{{ $content ?? $slot->content }}</p>
    @endif
    
    {{ $slot }}
</div>
