<?php

namespace App\Models\Concerns;

trait ResolvesMediaUrl
{
    protected function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $path = str_replace('\\', '/', trim($path));
        if ($path === '') {
            return null;
        }

        if (
            str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, '//')
        ) {
            return $path;
        }

        if (str_starts_with($path, '/storage/')) {
            return $this->resolveOptimizedLocalAsset(ltrim($path, '/'));
        }

        if (str_starts_with($path, 'storage/')) {
            return $this->resolveOptimizedLocalAsset($path);
        }

        if (str_starts_with($path, 'public/')) {
            return $this->resolveOptimizedLocalAsset('storage/' . ltrim(substr($path, 7), '/'));
        }

        if (
            str_starts_with($path, 'assets/')
            || str_starts_with($path, 'images/')
            || str_starts_with($path, 'upload/')
        ) {
            return $this->resolveOptimizedLocalAsset($path);
        }

        if (str_starts_with($path, '/')) {
            return $this->resolveOptimizedLocalAsset(ltrim($path, '/'));
        }

        return $this->resolveOptimizedLocalAsset('storage/' . ltrim($path, '/'));
    }

    protected function resolveOptimizedLocalAsset(string $assetPath): string
    {
        $assetPath = ltrim(str_replace('\\', '/', $assetPath), '/');
        $extension = strtolower(pathinfo($assetPath, PATHINFO_EXTENSION));

        if (in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            $webpAssetPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $assetPath);

            if (is_string($webpAssetPath) && is_file(public_path($webpAssetPath))) {
                return asset($webpAssetPath);
            }
        }

        return asset($assetPath);
    }
}
