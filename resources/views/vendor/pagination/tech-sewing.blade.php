@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $mobileWindowSize = 5;

        if ($lastPage <= $mobileWindowSize) {
            $mobileStartPage = 1;
            $mobileEndPage = $lastPage;
        } elseif ($currentPage <= $mobileWindowSize) {
            $mobileStartPage = 1;
            $mobileEndPage = $mobileWindowSize;
        } else {
            $mobileEndPage = min($lastPage, $currentPage);
            $mobileStartPage = max(1, $mobileEndPage - $mobileWindowSize + 1);
        }
    @endphp

    <nav aria-label="Phân trang" class="v-pagination">

        {{-- Nút trước --}}
        @if ($paginator->onFirstPage())
            <span class="v-pg-btn disabled" aria-disabled="true" aria-label="Trang trước">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="v-pg-btn" aria-label="Trang trước">‹</a>
        @endif

        <div class="v-pagination-pages v-pagination-pages-desktop">
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
        </div>

        <div class="v-pagination-pages v-pagination-pages-mobile">
            @if ($mobileStartPage > 1)
                <span class="v-pg-dots" aria-hidden="true">…</span>
            @endif

            @foreach (range($mobileStartPage, $mobileEndPage) as $page)
                @if ($page == $currentPage)
                    <span class="v-pg-btn active" aria-current="page" aria-label="Trang {{ $page }}">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="v-pg-btn" aria-label="Trang {{ $page }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($mobileEndPage < $lastPage)
                <span class="v-pg-dots" aria-hidden="true">…</span>
            @endif
        </div>

        {{-- Nút sau --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="v-pg-btn" aria-label="Trang sau">›</a>
        @else
            <span class="v-pg-btn disabled" aria-disabled="true" aria-label="Trang sau">›</span>
        @endif
    </nav>
@endif
