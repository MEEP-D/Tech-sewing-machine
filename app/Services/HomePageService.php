<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Section;
use App\Models\Setting;

class HomePageService
{
    public function data(): array
    {
        $sections = Section::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get()
            ->keyBy('key');

        $featuredProducts = Product::published()
            ->with('category')
            ->featured()
            ->latest()
            ->take(6)
            ->get();

        $latestProducts = Product::published()
            ->with('category')
            ->where('is_featured', false)
            ->latest()
            ->take(4)
            ->get();

        $latestPosts = Post::published()
            ->with('category')
            ->latest()
            ->take(3)
            ->get();

        $productCategories = Category::query()
            ->where('type', 'product')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $partners = Partner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        return [
            'sections' => $sections,
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'latestPosts' => $latestPosts,
            'productCategories' => $productCategories,
            'partners' => $partners,
            'homeHeroImage' => Setting::getValue('home_hero_image', asset('assets/frontend/images/anh1.jpg')),
            'homeHeroImageEnabled' => (bool) Setting::getValue('home_hero_image_enabled', true),
        ];
    }

    public function section(string $key): ?Section
    {
        return Section::query()->where('key', $key)->where('is_active', true)->first();
    }

    public function classFor(Section $section): string
    {
        $classes = array_filter([
            $section->container_class,
            $section->spacing_top,
            $section->spacing_bottom,
        ]);

        return implode(' ', $classes);
    }
}
