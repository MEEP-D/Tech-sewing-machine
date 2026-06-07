<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'type'];

    protected $attributes = [
        'type' => 'product',
    ];

    protected static function booted(): void
    {
        static::saved(fn (): bool => Cache::forget('product_filter_data_v1'));
        static::deleted(fn (): bool => Cache::forget('product_filter_data_v1'));
        static::restored(fn (): bool => Cache::forget('product_filter_data_v1'));
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
