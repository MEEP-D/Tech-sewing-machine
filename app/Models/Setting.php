<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'label', 'type', 'group', 'sort_order', 'description'];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_settings_array');
        });

        static::deleted(function () {
            Cache::forget('site_settings_array');
        });
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = self::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        $value = $setting->value;

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $value;
    }
}
