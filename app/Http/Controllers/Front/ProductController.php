<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Services\SeoService;

class ProductController extends Controller
{
    private function applyFilters($query, Request $request)
    {
        if ($brand = $request->get('brand')) {
            $query->where('brand', $brand);
        }

        if ($priceRange = $request->get('price_range')) {
            // format: 0-10000000, 10000000-50000000, 50000000-
            $parts = explode('-', $priceRange);
            if (count($parts) === 2) {
                if (is_numeric($parts[0])) $query->where('price', '>=', $parts[0]);
                if (is_numeric($parts[1])) $query->where('price', '<=', $parts[1]);
            }
        }

        $sort = $request->get('sort', 'latest');
        return match($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->latest('updated_at'),
        };
    }

    public function index(Request $request, SeoService $seoService)
    {
        $query = Product::published()->with('category');
        $query = $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();
        $seo = $seoService->defaults(
            \App\Models\Setting::getValue('seo_products_title', 'Tất cả sản phẩm'),
            \App\Models\Setting::getValue('seo_products_description', 'Danh sách máy may công nghiệp chất lượng cao')
        );
        $sort = $request->get('sort', 'latest');
        
        return view('front.pages.products.index', compact('products', 'seo', 'sort'));
    }

    public function search(Request $request, SeoService $seoService)
    {
        $keyword = $request->get('q');

        $query = Product::published()
            ->with('category')
            ->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%")
                  ->orWhere('short_description', 'like', "%{$keyword}%");
            });

        $query = $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();
        $seo = $seoService->defaults("Kết quả tìm kiếm: {$keyword}");
        $sort = $request->get('sort', 'latest');

        return view('front.pages.products.index', compact('products', 'seo', 'keyword', 'sort'));
    }

    public function category(Request $request, $slug, SeoService $seoService)
    {
        $category = Category::where('slug', $slug)->where('type', 'product')->firstOrFail();
        
        $query = $category->products()->published()->with('category');
        $query = $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();
        $seo = $seoService->forModel($category);
        $sort = $request->get('sort', 'latest');
        
        return view('front.pages.products.category', compact('category', 'products', 'seo', 'sort'));
    }


    public function show($slug, SeoService $seoService)
    {
        $product = Product::with('category')->where('slug', $slug)->published()->firstOrFail();
        $product->incrementViewCount();

        $relatedProducts = Product::published()
            ->with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $seo = $seoService->forModel($product);
        $seo['schema_markup'][] = $seoService->productSchema($product);
        $seo['schema_markup'][] = $seoService->breadcrumbSchema(array_filter([
            ['name' => 'Trang chủ',        'url' => route('home')],
            ['name' => 'Sản phẩm',         'url' => route('products.index')],
            $product->category ? ['name' => $product->category->name, 'url' => route('products.category', $product->category->slug)] : null,
            ['name' => $product->name,     'url' => $product->url],
        ]));

        return view('front.pages.products.show', compact('product', 'relatedProducts', 'seo'));
    }
}

