<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    private const SETTINGS_CACHE_KEY = 'site_settings_array';
    private const PROFILE_CACHE_KEY = 'site_profile_array';

    protected $fillable = ['key', 'value', 'label', 'type', 'group', 'sort_order', 'description'];
    protected $casts = [
        'social_links' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget(self::SETTINGS_CACHE_KEY);
            Cache::forget(self::PROFILE_CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget(self::SETTINGS_CACHE_KEY);
            Cache::forget(self::PROFILE_CACHE_KEY);
        });
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $settings = self::allAsMap();

        if (! array_key_exists($key, $settings)) {
            return $default;
        }

        $value = $settings[$key];

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $value;
    }

    public static function allAsMap(): array
    {
        return Cache::rememberForever(self::SETTINGS_CACHE_KEY, static function (): array {
            return self::query()->pluck('value', 'key')->toArray();
        });
    }

    public static function siteProfile(): array
    {
        return Cache::rememberForever(self::PROFILE_CACHE_KEY, static function (): array {
            $row = null;
            if (Schema::hasColumn('settings', 'hotline') && Schema::hasColumn('settings', 'email') && Schema::hasColumn('settings', 'address')) {
                $row = self::query()->whereNotNull('hotline')->orWhereNotNull('email')->orWhereNotNull('address')->first();
            }

            return [
                'hotline' => $row?->hotline ?: self::getValue('contact_hotline', self::getValue('hotline', '')),
                'email' => $row?->email ?: self::getValue('contact_email', self::getValue('email', '')),
                'address' => $row?->address ?: self::getValue('contact_address', self::getValue('address', '')),
                'social_links' => $row?->social_links ?: self::getValue('social_links', []),
            ];
        });
    }
}
