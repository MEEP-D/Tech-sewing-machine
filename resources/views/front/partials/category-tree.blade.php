@foreach($categories as $category)
    @php($children = collect(data_get($category, 'children', [])))
    @php($hasInstallmentProducts = (bool) data_get($category, 'has_installment_products', false))
    <li class="{{ $children->isNotEmpty() ? 'has-children' : '' }}">
        <a href="{{ route('products.category', data_get($category, 'slug')) }}" @class(['mega-link-installment' => $hasInstallmentProducts])>{{ data_get($category, 'name') }}</a>
        @if($children->isNotEmpty() && $level < 3)
            <ul class="{{ $level === 1 ? 'sub-links' : '' }}">
                @include('front.partials.category-tree', ['categories' => $children, 'level' => $level + 1])
            </ul>
        @endif
    </li>
@endforeach
