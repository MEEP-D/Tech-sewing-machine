<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::first();
        if ($product) {
            Media::firstOrCreate([
                'mediable_id' => $product->id,
                'mediable_type' => Product::class,
                'file_path' => $product->thumbnail ?: 'assets/frontend/images/anh2.jpg',
                'collection' => 'thumbnail',
            ], [
                'file_name' => 'thumbnail.jpg',
                'mime_type' => 'image/jpeg',
                'size' => 0,
                'custom_properties' => ['alt' => $product->name],
                'sort_order' => 0,
            ]);
        }

        $post = Post::first();
        if ($post) {
            Media::firstOrCreate([
                'mediable_id' => $post->id,
                'mediable_type' => Post::class,
                'file_path' => $post->thumbnail ?: 'assets/frontend/images/anh6.jpg',
                'collection' => 'thumbnail',
            ], [
                'file_name' => 'thumbnail.jpg',
                'mime_type' => 'image/jpeg',
                'size' => 0,
                'custom_properties' => ['alt' => $post->title],
                'sort_order' => 0,
            ]);
        }
    }
}

