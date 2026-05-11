@if ($paginator->hasPages())
    <nav aria-label="Phân trang" class="v-pagination">

        {{-- Nút trước --}}
        @if ($paginator->onFirstPage())
            <span class="v-pg-btn disabled" aria-disabled="true" aria-label="Trang trước">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="v-pg-btn" aria-label="Trang trước">‹</a>
        @endif

        {{-- Trang số --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="v-pg-dots" aria-hidden="true">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="v-pg-btn active" aria-current="page" aria-label="Trang {{ $page }}">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="v-pg-btn" aria-label="Trang {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Nút sau --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="v-pg-btn" aria-label="Trang sau">›</a>
        @else
            <span class="v-pg-btn disabled" aria-disabled="true" aria-label="Trang sau">›</span>
        @endif
    </nav>
@endif
