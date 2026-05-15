@foreach($categories as $category)
    <li class="{{ $category->childrenRecursive->isNotEmpty() ? 'has-children' : '' }}">
        <a href="{{ route('products.category', $category->slug) }}">
            @if($level === 1)<i class="fas fa-angle-right"></i> @endif{{ $category->name }}
        </a>
        @if($category->childrenRecursive->isNotEmpty() && $level < 3)
            <ul class="{{ $level === 1 ? 'sub-links' : '' }}">
                @include('front.partials.category-tree', ['categories' => $category->childrenRecursive, 'level' => $level + 1])
            </ul>
        @endif
    </li>
@endforeach
