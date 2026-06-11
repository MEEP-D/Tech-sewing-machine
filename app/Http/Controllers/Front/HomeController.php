<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Section;
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

        $newProductWith = ['category'];
        if (Schema::hasTable('product_specs')) {
            $newProductWith[] = 'specs';
        }

        $newProducts = Product::query()
            ->published()
            ->with($newProductWith)
            ->where(function ($query): void {
                $query->where('is_new', true)
                    ->orWhere('is_featured', true);
            })
            ->latest()
            ->take(8)
            ->get();

        $exclusiveWith = ['category'];
        if (Schema::hasTable('product_specs')) {
            $exclusiveWith[] = 'specs';
        }

        $hasExclusiveColumn = Schema::hasColumn('products', 'is_exclusive');
        $hasBannerSwitcherColumn = Schema::hasColumn('products', 'show_in_banner_switcher');

        $highlightProduct = Product::query()
            ->published()
            ->with($exclusiveWith)
            ->when(
                $hasExclusiveColumn,
                fn ($query) => $query->where('is_exclusive', true),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->latest()
            ->first();

        $bannerSwitcherProducts = Product::query()
            ->published()
            ->with($exclusiveWith)
            ->when(
                $hasBannerSwitcherColumn,
                fn ($query) => $query->where('show_in_banner_switcher', true),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->latest()
            ->take(12)
            ->get();

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

        $homeProductRows = collect();

        if (Schema::hasTable('sections')) {
            $homeProductRows = Section::query()
                ->where('is_active', true)
                ->where('type', 'product_row')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(function (Section $section) use ($exclusiveWith) {
                    $productIds = collect(data_get($section->style_config, 'product_ids', []))
                        ->filter()
                        ->map(fn ($id) => (int) $id)
                        ->unique()
                        ->values();

                    if ($productIds->isEmpty()) {
                        $section->setRelation('products', collect());

                        return $section;
                    }

                    $products = Product::query()
                        ->published()
                        ->with($exclusiveWith)
                        ->whereIn('id', $productIds)
                        ->get()
                        ->sortBy(fn (Product $product) => $productIds->search($product->id))
                        ->values();

                    $section->setRelation('products', $products);

                    return $section;
                })
                ->filter(fn (Section $section) => $section->getRelation('products')->isNotEmpty())
                ->values();
        }

        $siteProfile = Schema::hasTable('settings') ? Setting::siteProfile() : [];

        $seo = $seoService->defaults();
        $seo['schema_markup'][] = $seoService->organizationSchema();

        return view('front.pages.home', [
            'sliders' => $sliders,
            'newProducts' => $newProducts,
            'exclusiveProducts' => collect(),
            'bannerSwitcherProducts' => $bannerSwitcherProducts,
            'highlightProduct' => $highlightProduct,
            'homeProductRows' => $homeProductRows,
            'menuCategories' => $menuCategories,
            'partners' => $partners,
            'siteProfile' => $siteProfile,
            'seo' => $seo,
        ]);
    }
}
