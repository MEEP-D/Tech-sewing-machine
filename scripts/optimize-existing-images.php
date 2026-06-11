<?php

$root = dirname(__DIR__);
$directories = [
    $root . '/storage/app/public',
    $root . '/public/assets/frontend/images',
];

$created = 0;
$skipped = 0;
$totalBefore = 0;
$totalAfter = 0;
$maxDimension = 1920;
$quality = 78;

foreach ($directories as $directory) {
    if (! is_dir($directory)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (! $file->isFile()) {
            continue;
        }

        $extension = strtolower($file->getExtension());
        if (! in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            continue;
        }

        $sourcePath = $file->getPathname();
        $targetPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $sourcePath);
        if (! is_string($targetPath)) {
            $skipped++;
            continue;
        }

        if (is_file($targetPath) && filemtime($targetPath) >= filemtime($sourcePath)) {
            $skipped++;
            continue;
        }

        $imageInfo = @getimagesize($sourcePath);
        if (! is_array($imageInfo)) {
            $skipped++;
            continue;
        }

        [$width, $height] = $imageInfo;
        $mime = $imageInfo['mime'] ?? '';
        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
            'image/png' => @imagecreatefrompng($sourcePath),
            default => null,
        };

        if (! $image) {
            $skipped++;
            continue;
        }

        $scale = min(1, $maxDimension / max($width, $height));
        $nextWidth = max(1, (int) round($width * $scale));
        $nextHeight = max(1, (int) round($height * $scale));

        if ($nextWidth !== $width || $nextHeight !== $height) {
            $canvas = imagecreatetruecolor($nextWidth, $nextHeight);
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $nextWidth, $nextHeight, $transparent);
            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $nextWidth, $nextHeight, $width, $height);
            imagedestroy($image);
            $image = $canvas;
        }

        if (! @imagewebp($image, $targetPath, $quality)) {
            imagedestroy($image);
            $skipped++;
            continue;
        }

        imagedestroy($image);
        clearstatcache(true, $targetPath);
        $totalBefore += filesize($sourcePath) ?: 0;
        $totalAfter += filesize($targetPath) ?: 0;
        $created++;
    }
}

printf(
    "created=%d skipped=%d before=%s after=%s saved=%s\n",
    $created,
    $skipped,
    number_format($totalBefore),
    number_format($totalAfter),
    number_format($totalBefore - $totalAfter)
);
