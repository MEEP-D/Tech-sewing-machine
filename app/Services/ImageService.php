<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    protected string $disk = 'public';

    /**
     * Store and optimize an uploaded image.
     */
    public function store(UploadedFile $file, string $folder = 'uploads', int $width = null, int $height = null): string
    {
        $filename = Str::uuid() . '.webp';
        $path = $folder . '/' . $filename;

        $image = Image::read($file);

        if ($width || $height) {
            $image->scale(width: $width, height: $height);
        }

        // Convert to WebP and optimize
        $encoded = $image->toWebp(80);

        Storage::disk($this->disk)->put($path, (string) $encoded);

        return 'storage/' . $path;
    }

    /**
     * Delete a stored image.
     */
    public function delete(?string $path): void
    {
        if (! $path) return;

        $diskPath = str_replace('storage/', '', $path);
        Storage::disk($this->disk)->delete($diskPath);
    }

    /**
     * Replace old image with new optimized upload.
     */
    public function replace(?string $oldPath, UploadedFile $newFile, string $folder = 'uploads', int $width = null, int $height = null): string
    {
        $this->delete($oldPath);
        return $this->store($newFile, $folder, $width, $height);
    }
}
