<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SlugService
{
    /**
     * Vietnamese character map for transliteration.
     */
    protected array $charMap = [
        'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'ae',
        'ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i',
        'î'=>'i','ï'=>'i','ð'=>'d','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o',
        'õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u',
        'ý'=>'y','þ'=>'th','ÿ'=>'y',
        // Vietnamese
        'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','è'=>'e','é'=>'e','ê'=>'e',
        'ì'=>'i','í'=>'i','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ù'=>'u',
        'ú'=>'u','ý'=>'y','ă'=>'a','ắ'=>'a','ặ'=>'a','ằ'=>'a','ẳ'=>'a',
        'ẵ'=>'a','ấ'=>'a','ầ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a','ắ'=>'a',
        'đ'=>'d','ơ'=>'o','ớ'=>'o','ợ'=>'o','ờ'=>'o','ở'=>'o','ỡ'=>'o',
        'ướ'=>'uo','ưở'=>'uo','ư'=>'u','ứ'=>'u','ự'=>'u','ừ'=>'u','ử'=>'u',
        'ữ'=>'u','ổ'=>'o','ỗ'=>'o','ộ'=>'o','ồ'=>'o','ố'=>'o','ỏ'=>'o',
        'ọ'=>'o','ủ'=>'u','ụ'=>'u','ũ'=>'u','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
        'ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ệ'=>'e','ề'=>'e','ế'=>'e','ể'=>'e',
        'ễ'=>'e','ặ'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','ấ'=>'a','ầ'=>'a',
        'ẩ'=>'a','ẫ'=>'a','ậ'=>'a','ắ'=>'a','ằ'=>'a','ẳ'=>'a','ẵ'=>'a',
        'ặ'=>'a','ỳ'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
    ];

    /**
     * Generate a URL-friendly slug from a Vietnamese string.
     */
    public function make(string $text): string
    {
        // Replace Vietnamese chars
        $text = strtr($text, $this->charMap);

        // Use Laravel's Str::slug for final cleanup
        return Str::slug($text);
    }

    /**
     * Generate unique slug for a given table/column.
     */
    public function unique(string $text, string $table, string $column = 'slug', ?int $ignoreId = null): string
    {
        $base = $this->make($text);
        $slug = $base;
        $counter = 1;

        while (true) {
            $query = DB::table($table)->where($column, $slug);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }

            if (! $query->exists()) {
                break;
            }

            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
