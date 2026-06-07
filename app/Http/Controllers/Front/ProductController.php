<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    private const ALLOWED_PER_PAGES = [8, 12, 16, 24];
    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->input('per_page', 8);

        return in_array($perPage, self::ALLOWED_PER_PAGES, true) ? $perPage : 8;
    }

    private function flattenCategoryIds(Category $category): array
    {
        $ids = [$category->id];

        foreach ($category->childrenRecursive as $child) {
            $ids = array_merge($ids, $this->flattenCategoryIds($child));
        }

        return $ids;
    }

    private function splitProductTags(Collection $tags): array
    {
        $functionPrefixes = ['chuc-nang-', 'function-', 'fn-'];
        $usagePrefixes = ['su-dung-', 'usage-', 'use-'];

        $functionTags = $tags->filter(function (Tag $tag) use ($functionPrefixes) {
            $slug = (string) $tag->slug;
            foreach ($functionPrefixes as $prefix) {
                if (str_starts_with($slug, $prefix)) {
                    return true;
                }
            }
            return false;
        })->values();

        $usageTags = $tags->filter(function (Tag $tag) use ($usagePrefixes) {
            $slug = (string) $tag->slug;
            foreach ($usagePrefixes as $prefix) {
                if (str_starts_with($slug, $prefix)) {
                    return true;
                }
            }
            return false;
        })->values();

        if ($functionTags->isEmpty() || $usageTags->isEmpty()) {
            $functionGuess = ['theu', 'vat-so', 'may', 'overlock', 'embroidery'];
            $usageGuess = ['cong-nghiep', 'gia-dinh', 'quan-ao', 'factory', 'home'];

            if ($functionTags->isEmpty()) {
                $functionTags = $tags->filter(fn (Tag $tag) => str($tag->slug)->contains($functionGuess))->values();
            }

            if ($usageTags->isEmpty()) {
                $usageTags = $tags->filter(fn (Tag $tag) => str($tag->slug)->contains($usageGuess))->values();
            }
        }

        return [
            'functionTags' => $functionTags,
            'usageTags' => $usageTags,
        ];
    }

    private function baseFilterData(): array
    {
        $parentCategories = Category::query()
            ->where('type', 'product')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with([
                'childrenRecursive' => fn ($query) => $query->where('is_active', true),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $categoryIdsByParent = $parentCategories->mapWithKeys(
            fn (Category $category): array => [$category->id => $this->flattenCategoryIds($category)]
        );

        $productCounts = Product::published()
            ->whereIn('category_id', $categoryIdsByParent->flatten()->unique()->values())
            ->selectRaw('category_id, count(*) as aggregate')
            ->groupBy('category_id')
            ->pluck('aggregate', 'category_id');

        $filterCategories = $parentCategories->map(function (Category $category) use ($categoryIdsByParent, $productCounts) {
            $totalCount = collect($categoryIdsByParent->get($category->id, []))
                ->sum(fn (int $categoryId): int => (int) ($productCounts[$categoryId] ?? 0));
            $category->setAttribute('product_count', $totalCount);

            return $category;
        });

        $productTags = Tag::query()
            ->where('type', 'product')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $splitTags = $this->splitProductTags($productTags);

        return [
            'filterCategories' => $filterCategories,
            'functionTags' => $splitTags['functionTags'],
            'usageTags' => $splitTags['usageTags'],
            'toolbarTags' => $productTags->take(14),
            'perPageOptions' => self::ALLOWED_PER_PAGES,
        ];
    }

    private function buildFilterData(Request $request): array
    {
        $filterData = $this->baseFilterData();

        return $filterData + [
            'selectedFilters' => [
                'types' => array_values(array_filter((array) $request->input('types', []))),
                'functions' => array_values(array_filter((array) $request->input('functions', []))),
                'usages' => array_values(array_filter((array) $request->input('usages', []))),
                'tag' => trim((string) $request->input('tag', '')),
                'min_price' => $request->input('min_price'),
                'max_price' => $request->input('max_price'),
                'per_page' => $this->resolvePerPage($request),
            ],
        ];
    }

    private function applyFilters($query, Request $request)
    {
        if ($brand = $request->get('brand')) {
            $query->where('brand', $brand);
        }

        $minPrice = $request->integer('min_price');
        if ($minPrice > 0) {
            $query->where('price', '>=', $minPrice);
        }

        $maxPrice = $request->integer('max_price');
        if ($maxPrice > 0) {
            $query->where('price', '<=', $maxPrice);
        }

        $selectedTypes = array_values(array_filter((array) $request->input('types', [])));
        if (!empty($selectedTypes)) {
            $selectedParents = Category::query()
                ->where('type', 'product')
                ->whereIn('slug', $selectedTypes)
                ->with([
                    'childrenRecursive' => fn ($q) => $q->where('is_active', true),
                ])
                ->get(['id']);

            $selectedCategoryIds = $selectedParents
                ->flatMap(fn (Category $category) => $this->flattenCategoryIds($category))
                ->unique()
                ->values()
                ->all();

            if (!empty($selectedCategoryIds)) {
                $query->whereIn('category_id', $selectedCategoryIds);
            }
        }

        $selectedFunctions = array_values(array_filter((array) $request->input('functions', [])));
        if (!empty($selectedFunctions)) {
            $query->whereHas('tags', fn ($q) => $q->where('type', 'product')->whereIn('slug', $selectedFunctions));
        }

        $selectedUsages = array_values(array_filter((array) $request->input('usages', [])));
        if (!empty($selectedUsages)) {
            $query->whereHas('tags', fn ($q) => $q->where('type', 'product')->whereIn('slug', $selectedUsages));
        }

        $selectedTag = trim((string) $request->input('tag', ''));
        if ($selectedTag !== '') {
            $matchedCategory = Category::query()
                ->where('type', 'product')
                ->where('slug', $selectedTag)
                ->where('is_active', true)
                ->with([
                    'childrenRecursive' => fn ($q) => $q->where('is_active', true),
                ])
                ->first();

            $query->where(function ($inner) use ($selectedTag, $matchedCategory) {
                $inner->whereHas('tags', fn ($q) => $q->where('type', 'product')->where('slug', $selectedTag));

                if ($matchedCategory) {
                    $categoryIds = $this->flattenCategoryIds($matchedCategory);
                    $inner->orWhereIn('category_id', $categoryIds);
                }
            });
        }

        $sort = $request->get('sort', 'latest');

        return match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->latest('updated_at'),
        };
    }

    public function index(Request $request, SeoService $seoService)
    {
        $perPage = $this->resolvePerPage($request);
        $query = Product::published()->with(['category', 'tags']);
        $query = $this->applyFilters($query, $request);

        $products = $query->paginate($perPage)->withQueryString();
        $seo = $seoService->defaults(
            \App\Models\Setting::getValue('seo_products_title', 'Tất cả sản phẩm'),
            \App\Models\Setting::getValue('seo_products_description', 'Danh sach may may cong nghiep chat luong cao')
        );
        $sort = $request->get('sort', 'latest');

        return view('front.pages.products.index', array_merge(
            compact('products', 'seo', 'sort'),
            $this->buildFilterData($request)
        ));
    }

    public function search(Request $request, SeoService $seoService)
    {
        $perPage = $this->resolvePerPage($request);
        $keyword = $request->get('q');

        $query = Product::published()
            ->with(['category', 'tags'])
            ->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('short_description', 'like', "%{$keyword}%");
            });

        $query = $this->applyFilters($query, $request);

        $products = $query->paginate($perPage)->withQueryString();
        $seo = $seoService->defaults("Kết quả tìm kiếm: {$keyword}");
        $sort = $request->get('sort', 'latest');

        return view('front.pages.products.index', array_merge(
            compact('products', 'seo', 'keyword', 'sort'),
            $this->buildFilterData($request)
        ));
    }

    public function category(Request $request, $slug, SeoService $seoService)
    {
        $perPage = $this->resolvePerPage($request);
        $category = Category::where('slug', $slug)->where('type', 'product')->firstOrFail();

        $query = $category->products()->published()->with(['category', 'tags']);
        $query = $this->applyFilters($query, $request);

        $products = $query->paginate($perPage)->withQueryString();
        $seo = $seoService->forModel($category);
        $sort = $request->get('sort', 'latest');

        return view('front.pages.products.category', array_merge(
            compact('category', 'products', 'seo', 'sort'),
            $this->buildFilterData($request)
        ));
    }

    public function show($slug, SeoService $seoService)
    {
        $with = ['category'];
        if (Schema::hasTable('product_specs')) {
            $with[] = 'specs';
        }
        $product = Product::with($with)->where('slug', $slug)->published()->firstOrFail();
        $product->incrementViewCount();

        $relatedProducts = Product::published()
            ->with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest('updated_at')
            ->take(12)
            ->get();

        // Fallback: nếu cùng category không đủ, bổ sung sản phẩm tương tự (khác category).
        if ($relatedProducts->count() < 4) {
            $fallbackProducts = Product::published()
                ->with('category')
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id')->all())
                ->latest('updated_at')
                ->take(12 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->concat($fallbackProducts)->values();
        }

        $seo = $seoService->forModel($product);
        $seo['schema_markup'][] = $seoService->productSchema($product);
        $seo['schema_markup'][] = $seoService->breadcrumbSchema(array_filter([
            ['name' => 'Trang chủ', 'url' => route('home')],
            ['name' => 'Sản phẩm', 'url' => route('products.index')],
            $product->category ? ['name' => $product->category->name, 'url' => route('products.category', $product->category->slug)] : null,
            ['name' => $product->name, 'url' => $product->url],
        ]));

        return view('front.pages.products.show', compact('product', 'relatedProducts', 'seo'));
    }
}
