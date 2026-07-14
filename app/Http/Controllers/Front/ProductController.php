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
    private const DEFAULT_PER_PAGE = 12;
    private const HERO_PRODUCT_LIMIT = 10;

    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->input('per_page', self::DEFAULT_PER_PAGE);

        return in_array($perPage, self::ALLOWED_PER_PAGES, true) ? $perPage : self::DEFAULT_PER_PAGE;
    }

    private function flattenCategoryIds(Category $category): array
    {
        $ids = [$category->id];

        foreach ($category->childrenRecursive as $child) {
            $ids = array_merge($ids, $this->flattenCategoryIds($child));
        }

        return $ids;
    }

    private function normalizedPriceExpression(): string
    {
        return "CAST(NULLIF(REGEXP_REPLACE(price, '[^0-9]', ''), '') AS UNSIGNED)";
    }

    private function applyProductCountsToCategories(Collection $categories, Collection $productCounts): Collection
    {
        return $categories->map(function (Category $category) use ($productCounts) {
            $children = $this->applyProductCountsToCategories($category->childrenRecursive, $productCounts);
            $category->setRelation('childrenRecursive', $children);

            $totalCount = (int) ($productCounts[$category->id] ?? 0)
                + $children->sum(fn (Category $child): int => (int) $child->getAttribute('product_count'));

            $category->setAttribute('product_count', $totalCount);

            return $category;
        });
    }

    private function filterCategoriesWithProducts(Collection $categories): Collection
    {
        return $categories
            ->map(function (Category $category) {
                $children = $this->filterCategoriesWithProducts($category->childrenRecursive);
                $category->setRelation('childrenRecursive', $children);

                return $category;
            })
            ->filter(fn (Category $category): bool => (int) $category->getAttribute('product_count') > 0)
            ->values();
    }

    private function directChildCategoriesWithProducts(Collection $categories, Collection $productCounts): Collection
    {
        return $categories
            ->flatMap(function (Category $category) use ($productCounts) {
                $directCount = (int) ($productCounts[$category->id] ?? 0);
                $items = collect();

                if ($category->parent_id !== null && $directCount > 0) {
                    $category->setAttribute('product_count', $directCount);
                    $items->push($category);
                }

                return $items->concat($this->directChildCategoriesWithProducts($category->childrenRecursive, $productCounts));
            })
            ->values();
    }

    private function parentProductCategories(): Collection
    {
        return Category::query()
            ->where('type', 'product')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with([
                'childrenRecursive' => fn ($query) => $query->where('is_active', true),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
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

    private function baseFilterData(Request $request, ?Category $scopeCategory = null): array
    {
        $parentCategories = $this->parentProductCategories();

        $categoryIdsByParent = $parentCategories->mapWithKeys(
            fn (Category $category): array => [$category->id => $this->flattenCategoryIds($category)]
        );

        $countQuery = Product::published();

        if ($scopeCategory) {
            $countQuery->whereIn('category_id', $this->flattenCategoryIds($scopeCategory));
        }

        $productCounts = $this->applyFilters($countQuery, $request, ['types'], false)
            ->whereIn('category_id', $categoryIdsByParent->flatten()->unique()->values())
            ->selectRaw('category_id, count(*) as aggregate')
            ->groupBy('category_id')
            ->pluck('aggregate', 'category_id');

        $filterCategories = $this->applyProductCountsToCategories($parentCategories, $productCounts);

        $toolbarCountQuery = Product::published();

        if ($scopeCategory) {
            $toolbarCountQuery->whereIn('category_id', $this->flattenCategoryIds($scopeCategory));
        }

        $toolbarCounts = $this->applyFilters($toolbarCountQuery, $request, ['tag'], false)
            ->whereIn('category_id', $categoryIdsByParent->flatten()->unique()->values())
            ->selectRaw('category_id, count(*) as aggregate')
            ->groupBy('category_id')
            ->pluck('aggregate', 'category_id');
        $toolbarTotalCount = (int) $toolbarCounts->sum();

        $selectedTypes = array_values(array_filter((array) $request->input('types', [])));
        $toolbarSourceCategories = $this->parentProductCategories();

        if (!empty($selectedTypes)) {
            $toolbarSourceCategories = $toolbarSourceCategories
                ->filter(fn (Category $category): bool => in_array($category->slug, $selectedTypes, true))
                ->values();
        }

        $toolbarCategories = $this->directChildCategoriesWithProducts($toolbarSourceCategories, $toolbarCounts);

        $productTags = Tag::query()
            ->where('type', 'product')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $splitTags = $this->splitProductTags($productTags);

        return [
            'filterCategories' => $filterCategories,
            'functionTags' => $splitTags['functionTags'],
            'usageTags' => $splitTags['usageTags'],
            'toolbarCategories' => $toolbarCategories,
            'toolbarTotalCount' => $toolbarTotalCount,
            'toolbarTags' => $productTags->take(14),
            'perPageOptions' => self::ALLOWED_PER_PAGES,
        ];
    }

    private function buildFilterData(Request $request, ?Category $scopeCategory = null): array
    {
        $filterData = $this->baseFilterData($request, $scopeCategory);

        return $filterData + [
            'selectedFilters' => [
                'q' => trim((string) $request->input('q', '')),
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

    private function heroProducts(): Collection
    {
        $limit = self::HERO_PRODUCT_LIMIT;
        $baseQuery = Product::published()
            ->with('category')
            ->orderByDesc('is_new')
            ->latest('updated_at');

        if (! Schema::hasColumn('products', 'show_in_banner_switcher')) {
            return $baseQuery->take($limit)->get();
        }

        $preferredProducts = (clone $baseQuery)
            ->where('show_in_banner_switcher', true)
            ->take($limit)
            ->get();

        if ($preferredProducts->count() >= $limit) {
            return $preferredProducts;
        }

        $fallbackProducts = (clone $baseQuery)
            ->when(
                $preferredProducts->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id', $preferredProducts->pluck('id')->all())
            )
            ->take($limit - $preferredProducts->count())
            ->get();

        return $preferredProducts
            ->concat($fallbackProducts)
            ->take($limit)
            ->values();
    }

    private function applyFilters($query, Request $request, array $excludedFilters = [], bool $applySorting = true)
    {
        $keyword = in_array('q', $excludedFilters, true)
            ? ''
            : trim((string) $request->input('q', ''));

        if ($keyword !== '') {
            $query->where(function ($inner) use ($keyword) {
                $inner->where('name', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('short_description', 'like', "%{$keyword}%");
            });
        }

        if (!in_array('brand', $excludedFilters, true) && ($brand = $request->get('brand'))) {
            $query->where('brand', $brand);
        }

        $minPrice = !in_array('min_price', $excludedFilters, true) ? $request->integer('min_price') : 0;
        $priceExpression = $this->normalizedPriceExpression();

        if ($minPrice > 0) {
            $query->whereRaw("{$priceExpression} >= ?", [$minPrice]);
        }

        $maxPrice = !in_array('max_price', $excludedFilters, true) ? $request->integer('max_price') : 0;
        if ($maxPrice > 0) {
            $query->whereRaw("{$priceExpression} <= ?", [$maxPrice]);
        }

        $selectedTypes = in_array('types', $excludedFilters, true)
            ? []
            : array_values(array_filter((array) $request->input('types', [])));
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

        $selectedFunctions = in_array('functions', $excludedFilters, true)
            ? []
            : array_values(array_filter((array) $request->input('functions', [])));
        if (!empty($selectedFunctions)) {
            $query->whereHas('tags', fn ($q) => $q->where('type', 'product')->whereIn('slug', $selectedFunctions));
        }

        $selectedUsages = in_array('usages', $excludedFilters, true)
            ? []
            : array_values(array_filter((array) $request->input('usages', [])));
        if (!empty($selectedUsages)) {
            $query->whereHas('tags', fn ($q) => $q->where('type', 'product')->whereIn('slug', $selectedUsages));
        }

        $selectedTag = in_array('tag', $excludedFilters, true)
            ? ''
            : trim((string) $request->input('tag', ''));
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

        if (!$applySorting) {
            return $query;
        }

        $sort = $request->get('sort', 'latest');

        return match ($sort) {
            'price_asc' => $query->orderByRaw("{$priceExpression} IS NULL, {$priceExpression} ASC"),
            'price_desc' => $query->orderByRaw("{$priceExpression} IS NULL, {$priceExpression} DESC"),
            default => $query->latest('updated_at'),
        };
    }

    public function index(Request $request, SeoService $seoService)
    {
        $perPage = $this->resolvePerPage($request);
        $query = Product::published()->with(['category', 'tags']);
        $query = $this->applyFilters($query, $request);

        $products = $query->paginate($perPage)->withQueryString();
        $heroProducts = $this->heroProducts();
        $seo = $seoService->defaults(
            \App\Models\Setting::getValue('seo_products_title', 'Tất cả sản phẩm'),
            \App\Models\Setting::getValue('seo_products_description', 'Danh sach may may cong nghiep chat luong cao')
        );
        $sort = $request->get('sort', 'latest');

        return view('front.pages.products.index', array_merge(
            compact('products', 'seo', 'sort', 'heroProducts'),
            $this->buildFilterData($request)
        ));
    }

    public function search(Request $request, SeoService $seoService)
    {
        $perPage = $this->resolvePerPage($request);
        $keyword = trim((string) $request->get('q', ''));

        $query = Product::published()->with(['category', 'tags']);

        $query = $this->applyFilters($query, $request);

        $products = $query->paginate($perPage)->withQueryString();
        $heroProducts = $this->heroProducts();
        $seo = $seoService->defaults("Kết quả tìm kiếm: {$keyword}");
        $sort = $request->get('sort', 'latest');

        return view('front.pages.products.index', array_merge(
            compact('products', 'seo', 'keyword', 'sort', 'heroProducts'),
            $this->buildFilterData($request)
        ));
    }

    public function category(Request $request, $slug, SeoService $seoService)
    {
        $perPage = $this->resolvePerPage($request);
        $category = Category::where('slug', $slug)
            ->where('type', 'product')
            ->with([
                'childrenRecursive' => fn ($query) => $query->where('is_active', true),
            ])
            ->firstOrFail();

        $query = Product::published()
            ->whereIn('category_id', $this->flattenCategoryIds($category))
            ->with(['category', 'tags']);
        $query = $this->applyFilters($query, $request);

        $products = $query->paginate($perPage)->withQueryString();
        $heroProducts = $this->heroProducts();
        $seo = $seoService->forModel($category);
        $sort = $request->get('sort', 'latest');

        return view('front.pages.products.category', array_merge(
            compact('category', 'products', 'seo', 'sort', 'heroProducts'),
            $this->buildFilterData($request, $category)
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
