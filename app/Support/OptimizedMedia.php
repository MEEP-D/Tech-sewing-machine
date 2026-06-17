<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class OptimizedMedia
{
    /**
     * Resolve a path into a browser URL and optionally generate a scaled WebP variant.
     */
    public function url(?string $path, array $options = []): ?string
    {
        if (! is_string($path)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($path));

        if ($path === '') {
            return null;
        }

        if ($this->isRemote($path)) {
            return $path;
        }

        $asset = $this->normalize($path);

        if ($asset === null) {
            return null;
        }

        $width = max(0, (int) ($options['width'] ?? 0));
        $height = max(0, (int) ($options['height'] ?? 0));
        $quality = min(90, max(45, (int) ($options['quality'] ?? 78)));

        if (($width > 0 || $height > 0) && $this->canOptimize($asset['extension']) && is_file($asset['source_path'])) {
            $variantUrl = $this->createVariantUrl($asset, $width, $height, $quality);

            if ($variantUrl !== null) {
                return $variantUrl;
            }
        }

        $existingModernUrl = $this->existingModernFormatUrl($asset);

        return $this->toBrowserUrl($existingModernUrl ?? $asset['url']);
    }

    protected function isRemote(string $path): bool
    {
        return str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, '//')
            || str_starts_with($path, 'data:');
    }

    protected function canOptimize(string $extension): bool
    {
        return in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true);
    }

    protected function normalize(string $path): ?array
    {
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $storagePath = ltrim(substr($path, 8), '/');

            return [
                'disk' => 'storage',
                'relative_path' => $storagePath,
                'source_path' => storage_path('app/public/' . $storagePath),
                'url' => Storage::disk('public')->url($storagePath),
                'extension' => strtolower(pathinfo($storagePath, PATHINFO_EXTENSION)),
            ];
        }

        if (str_starts_with($path, 'public/')) {
            $storagePath = ltrim(substr($path, 7), '/');

            return [
                'disk' => 'storage',
                'relative_path' => $storagePath,
                'source_path' => storage_path('app/public/' . $storagePath),
                'url' => Storage::disk('public')->url($storagePath),
                'extension' => strtolower(pathinfo($storagePath, PATHINFO_EXTENSION)),
            ];
        }

        if (
            str_starts_with($path, 'assets/')
            || str_starts_with($path, 'images/')
            || str_starts_with($path, 'upload/')
            || str_starts_with($path, 'css/')
            || str_starts_with($path, 'js/')
            || str_starts_with($path, 'fonts/')
            || is_file(public_path($path))
        ) {
            return [
                'disk' => 'public',
                'relative_path' => $path,
                'source_path' => public_path($path),
                'url' => asset($path),
                'extension' => strtolower(pathinfo($path, PATHINFO_EXTENSION)),
            ];
        }

        return [
            'disk' => 'storage',
            'relative_path' => $path,
            'source_path' => storage_path('app/public/' . $path),
            'url' => Storage::disk('public')->url($path),
            'extension' => strtolower(pathinfo($path, PATHINFO_EXTENSION)),
        ];
    }

    protected function existingModernFormatUrl(array $asset): ?string
    {
        if (! in_array($asset['extension'], ['jpg', 'jpeg', 'png'], true)) {
            return null;
        }

        $modernRelativePath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $asset['relative_path']);

        if (! is_string($modernRelativePath)) {
            return null;
        }

        if ($asset['disk'] === 'storage') {
            return is_file(storage_path('app/public/' . $modernRelativePath))
                ? Storage::disk('public')->url($modernRelativePath)
                : null;
        }

        return is_file(public_path($modernRelativePath))
            ? asset($modernRelativePath)
            : null;
    }

    protected function createVariantUrl(array $asset, int $width, int $height, int $quality): ?string
    {
        $directory = trim((string) pathinfo($asset['relative_path'], PATHINFO_DIRNAME), './');
        $name = pathinfo($asset['relative_path'], PATHINFO_FILENAME);
        $sizeSuffix = 'w' . ($width > 0 ? $width : 'auto') . '-h' . ($height > 0 ? $height : 'auto');
        $targetRelativePath = '__optimized/'
            . ($directory !== '' ? $directory . '/' : '')
            . $name . '-' . $sizeSuffix . '-q' . $quality . '.webp';

        if ($asset['disk'] === 'storage') {
            $targetAbsolutePath = storage_path('app/public/' . $targetRelativePath);
            $targetUrl = Storage::disk('public')->url($targetRelativePath);
        } else {
            $targetAbsolutePath = public_path($targetRelativePath);
            $targetUrl = asset($targetRelativePath);
        }

        $targetDirectory = dirname($targetAbsolutePath);

        if (! is_dir($targetDirectory)) {
            @mkdir($targetDirectory, 0755, true);
        }

        $sourceTimestamp = @filemtime($asset['source_path']) ?: 0;
        $targetTimestamp = @filemtime($targetAbsolutePath) ?: 0;

        if (! is_file($targetAbsolutePath) || $targetTimestamp < $sourceTimestamp) {
            try {
                $image = Image::read($asset['source_path']);
                $image->scaleDown(
                    width: $width > 0 ? $width : null,
                    height: $height > 0 ? $height : null,
                );

                file_put_contents($targetAbsolutePath, (string) $image->toWebp($quality));
            } catch (\Throwable) {
                return null;
            }
        }

        return is_file($targetAbsolutePath) ? $this->toBrowserUrl($targetUrl) : null;
    }

    protected function toBrowserUrl(string $url): string
    {
        if ($url === '' || str_starts_with($url, '//') || str_starts_with($url, 'data:')) {
            return $url;
        }

        $request = request();
        $requestHost = $request->getHost();

        if ($requestHost === '') {
            return $url;
        }

        $parts = parse_url($url);

        if (! is_array($parts) || ! isset($parts['host'], $parts['path'])) {
            return $url;
        }

        if (! hash_equals(strtolower((string) $parts['host']), strtolower($requestHost))) {
            return $url;
        }

        $browserUrl = $parts['path'];

        if (isset($parts['query']) && $parts['query'] !== '') {
            $browserUrl .= '?' . $parts['query'];
        }

        if (isset($parts['fragment']) && $parts['fragment'] !== '') {
            $browserUrl .= '#' . $parts['fragment'];
        }

        return $browserUrl;
    }
}
