<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashSaleItem extends Model
{
    protected $fillable = [
        'flash_sale_id',
        'product_id',
        'sale_price',
        'discount_percent',
        'badge_label',
        'status_label',
        'is_blinking',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'discount_percent' => 'integer',
        'is_blinking' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function flashSale(): BelongsTo
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getDisplayDiscountPercentAttribute(): int
    {
        return (int) ($this->discount_percent ?: $this->product?->discount_percent ?: 0);
    }

    public function getDisplayPriceAttribute(): string
    {
        $salePrice = trim((string) $this->sale_price);

        if ($salePrice !== '') {
            return $salePrice;
        }

        return $this->product?->formatted_discounted_price
            ?: $this->product?->formatted_price
            ?: $this->product?->price
            ?: 'Liên hệ';
    }

    public function getDisplayStatusLabelAttribute(): string
    {
        return trim((string) $this->status_label) ?: 'ĐANG BÁN CHẠY';
    }
}
