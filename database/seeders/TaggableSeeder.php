<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaggableSeeder extends Seeder
{
    public function run(): void
    {
        $tagBySlug = Tag::query()->get()->keyBy('slug');

        $productTags = [
            'may-may-lap-trinh-brother-bas-311h' => ['lap-trinh'],
            'may-vat-so-5-chi-juki-mo-6714s' => ['may-vat-so'],
            'may-may-mot-kim-juki-ddl-900c' => ['may-1-kim'],
        ];

        foreach ($productTags as $productSlug => $tagSlugs) {
            $product = Product::where('slug', $productSlug)->first();
            if (! $product) {
                continue;
            }

            foreach ($tagSlugs as $tagSlug) {
                $tag = $tagBySlug->get($tagSlug);
                if (! $tag) {
                    continue;
                }

                DB::table('taggables')->insertOrIgnore([
                    'tag_id' => $tag->id,
                    'taggable_id' => $product->id,
                    'taggable_type' => Product::class,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $postTags = [
            'cach-bao-tri-may-may-1-kim-juki' => ['bao-tri', 'ky-thuat'],
            'xu-ly-loi-bo-mui-tren-may-vat-so' => ['ky-thuat'],
        ];

        foreach ($postTags as $postSlug => $tagSlugs) {
            $post = Post::where('slug', $postSlug)->first();
            if (! $post) {
                continue;
            }

            foreach ($tagSlugs as $tagSlug) {
                $tag = $tagBySlug->get($tagSlug);
                if (! $tag) {
                    continue;
                }

                DB::table('taggables')->insertOrIgnore([
                    'tag_id' => $tag->id,
                    'taggable_id' => $post->id,
                    'taggable_type' => Post::class,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

