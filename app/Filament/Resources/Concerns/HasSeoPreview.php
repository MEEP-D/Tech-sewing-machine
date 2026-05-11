<?php

namespace App\Filament\Resources\Concerns;

trait HasSeoPreview
{
    public static function seoPreview(array $seo): array
    {
        $title = $seo['meta_title'] ?? '';
        $description = $seo['meta_description'] ?? '';
        $url = $seo['canonical_url'] ?? '';

        return [
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'robots' => $seo['robots'] ?? 'index, follow',
            'schema_count' => count($seo['schema_markup'] ?? []),
        ];
    }
}
