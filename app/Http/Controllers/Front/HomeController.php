<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slider;
use App\Services\SeoService;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(SeoService $seoService): View
    {
        $sliders = Schema::hasTable('sliders')
            ? Slider::query()->where('is_active', true)->orderBy('sort_order')->get()
            : collect();

        $newProducts = Product::query()
            ->published()
            ->with(['category'])
            ->where('is_new', true)
            ->latest()
            ->take(8)
            ->get();

        $exclusiveWith = ['category'];
        if (Schema::hasTable('product_specs')) {
            $exclusiveWith[] = 'specs';
        }

        $hasCodeColumn = Schema::hasColumn('products', 'code');
        $hasExclusiveColumn = Schema::hasColumn('products', 'is_exclusive');

        $exclusiveProducts = Product::query()
            ->published()
            ->with($exclusiveWith)
            ->where(function ($query) use ($hasCodeColumn, $hasExclusiveColumn) {
                $query->where(function ($inner) use ($hasCodeColumn) {
                    if ($hasCodeColumn) {
                        $inner->where('code', 'X-1209D')->orWhere('sku', 'X-1209D');
                        return;
                    }

                    $inner->where('sku', 'X-1209D');
                });

                if ($hasExclusiveColumn) {
                    $query->orWhere('is_exclusive', true);
                }
            })
            ->when($hasExclusiveColumn, fn ($query) => $query->orderByDesc('is_exclusive'))
            ->latest()
            ->take(3)
            ->get();

        $highlightProduct = $exclusiveProducts->first();

        $menuCategories = Category::query()
            ->where('type', 'product')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with('childrenRecursive')
            ->orderBy('sort_order')
            ->get();

        $partners = Partner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $siteProfile = Schema::hasTable('settings') ? Setting::siteProfile() : [];

        $seo = $seoService->defaults();
        $seo['schema_markup'][] = $seoService->organizationSchema();

        return view('front.pages.home', [
            'sliders' => $sliders,
            'newProducts' => $newProducts,
            'exclusiveProducts' => $exclusiveProducts,
            'highlightProduct' => $highlightProduct,
            'menuCategories' => $menuCategories,
            'partners' => $partners,
            'siteProfile' => $siteProfile,
            'seo' => $seo,
        ]);
    }
}
