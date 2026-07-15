<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlashSale extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'view_all_url',
        'starts_at',
        'ends_at',
        'show_countdown',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'show_countdown' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(FlashSaleItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activeItems(): HasMany
    {
        return $this->items()->where('is_active', true);
    }

    public function scopeCurrent(Builder $query, ?CarbonInterface $moment = null): Builder
    {
        $moment ??= now();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $query) use ($moment): void {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', $moment);
            })
            ->where(function (Builder $query) use ($moment): void {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $moment);
            })
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }
}
