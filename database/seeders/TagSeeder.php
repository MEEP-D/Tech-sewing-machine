<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Máy 1 kim', 'slug' => 'may-1-kim', 'type' => 'product'],
            ['name' => 'Máy vắt sổ', 'slug' => 'may-vat-so', 'type' => 'product'],
            ['name' => 'Lập trình', 'slug' => 'lap-trinh', 'type' => 'product'],
            ['name' => 'Bảo trì', 'slug' => 'bao-tri', 'type' => 'news'],
            ['name' => 'Kỹ thuật', 'slug' => 'ky-thuat', 'type' => 'news'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['slug' => $tag['slug']], $tag);
        }
    }
}

