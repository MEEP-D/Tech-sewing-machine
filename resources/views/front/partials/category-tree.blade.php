@foreach($categories as $category)
    @php($children = collect(data_get($category, 'children', [])))
    <li class="{{ $children->isNotEmpty() ? 'has-children' : '' }}">
        <a href="{{ route('products.category', data_get($category, 'slug')) }}">
            @if($level === 1)<i class="fas fa-angle-right"></i> @endif{{ data_get($category, 'name') }}
        </a>
        @if($children->isNotEmpty() && $level < 3)
            <ul class="{{ $level === 1 ? 'sub-links' : '' }}">
                @include('front.partials.category-tree', ['categories' => $children, 'level' => $level + 1])
            </ul>
        @endif
    </li>
@endforeach
