<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'file_path',
        'file_name',
        'mime_type',
        'size',
        'collection',
        'custom_properties',
        'sort_order',
    ];

    protected $casts = [
        'custom_properties' => 'array',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return asset($this->file_path);
    }
}
