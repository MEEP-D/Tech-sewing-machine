<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use App\Services\SeoService;
use Illuminate\View\View;

class AdminContentController extends Controller
{
    public function home(SeoService $seoService): View
    {
        $featuredProducts = Product::published()
            ->with('category')
            ->featured()
            ->latest()
            ->take((int) Setting::getValue('home_featured_count', 6))
            ->get();

        $latestProducts = Product::published()
            ->with('category')
            ->latest()
            ->take((int) Setting::getValue('home_latest_products_count', 4))
            ->get();

        $latestPosts = Post::published()
            ->with('category')
            ->latest()
            ->take((int) Setting::getValue('home_latest_posts_count', 3))
            ->get();

        $productCategories = Setting::getValue('home_show_categories', true)
            ? Category::query()
                ->where('type', 'product')
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->take((int) Setting::getValue('home_categories_count', 6))
                ->get()
            : collect();

        $partners = Setting::getValue('home_show_partners', true)
            ? Partner::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->take((int) Setting::getValue('home_partners_count', 8))
                ->get()
            : collect();

        $seo = $seoService->defaults();
        $seo['schema_markup'][] = $seoService->organizationSchema();

        return view('front.home', compact(
            'featuredProducts',
            'latestProducts',
            'latestPosts',
            'productCategories',
            'partners',
            'seo'
        ));
    }
}
