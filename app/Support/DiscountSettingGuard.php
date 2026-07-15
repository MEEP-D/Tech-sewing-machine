<?php

namespace App\Support;

use App\Models\FlashSaleItem;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class DiscountSettingGuard
{
    public const MESSAGE = 'Sản phẩm này đã có giảm giá ở nơi khác. Vui lòng chỉ giữ một loại giảm giá để tránh hiển thị sai giá.';

    public static function productHasProductDiscount(int $productId): bool
    {
        return Product::query()
            ->whereKey($productId)
            ->where('discount_percent', '>', 0)
            ->exists();
    }

    public static function productHasFlashSaleDiscount(int $productId, ?int $exceptFlashSaleId = null): bool
    {
        if (! Schema::hasTable('flash_sale_items')) {
            return false;
        }

        return FlashSaleItem::query()
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->where('discount_percent', '>', 0)
            ->when($exceptFlashSaleId, fn ($query) => $query->where('flash_sale_id', '!=', $exceptFlashSaleId))
            ->exists();
    }
}
