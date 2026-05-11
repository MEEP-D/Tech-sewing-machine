@props(['product'])

<a href="{{ route('products.show', $product->slug) }}" {{ $attributes->merge(['class' => 'v-product-card']) }}>
    <div class="v-product-badge-row">
        <span class="v-product-badge">{{ $product->category?->name ?? 'Sản phẩm' }}</span>
        @if($product->is_new)
            <span class="v-product-badge is-light">Mới</span>
        @endif
    </div>
    
    <div class="v-product-img">
        @if($product->thumbnail)
            <img src="{{ (str_starts_with($product->thumbnail, 'http')) ? $product->thumbnail : \Illuminate\Support\Facades\Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy">
        @else
            <div class="v-product-img-placeholder">
                <i class="fas fa-image"></i>
            </div>
        @endif
    </div>
    
    <h3>{{ $product->name }}</h3>
    <p class="v-product-summary">{{ $product->short_description ?: 'Giải pháp may công nghiệp được tối ưu cho hiệu suất, độ ổn định.' }}</p>
    
    <div class="v-product-specs">
        <div>
            <strong>{{ $product->sku ?? 'N/A' }}</strong>
            <span>SKU</span>
        </div>
        <div>
            <strong>{{ $product->price ?? 'Liên hệ' }}</strong>
            <span>Giá bán</span>
        </div>
    </div>
    
    <span class="v-card-link">Tìm hiểu thêm</span>
</a>
