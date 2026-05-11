<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        // Chỉ select các cột cần thiết cho XML sitemap — giảm memory usage
        $products = Product::published()
            ->select(['id', 'slug', 'updated_at'])
            ->latest('updated_at')
            ->get();

        $posts = Post::published()
            ->select(['id', 'slug', 'updated_at', 'published_at'])
            ->latest('updated_at')
            ->get();

        // Chỉ lấy danh mục đang active
        $categories = Category::where('is_active', true)
            ->select(['id', 'slug', 'type', 'updated_at'])
            ->orderBy('type')
            ->orderBy('sort_order')
            ->get();

        return response()->view('front.sitemap', [
            'products'   => $products,
            'posts'      => $posts,
            'categories' => $categories,
        ])->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $sitemapUrl = route('sitemap');

        $content  = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "\nSitemap: {$sitemapUrl}\n";

        return response($content)->header('Content-Type', 'text/plain');
    }
}

