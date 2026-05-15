<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class NewsController extends Controller
{
    private function buildNewsSidebarData(): array
    {
        $allNewsCategories = Category::query()
            ->where('type', 'news')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $featuredPosts = Post::published()
            ->with('category')
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        if ($featuredPosts->isEmpty()) {
            $featuredPosts = Post::published()
                ->with('category')
                ->latest('view_count')
                ->latest('published_at')
                ->take(5)
                ->get();
        }

        $pickCategoryByKeywords = static function (Collection $categories, array $keywords): ?Category {
            return $categories->first(function (Category $category) use ($keywords) {
                $haystack = mb_strtolower($category->name . ' ' . $category->slug);
                foreach ($keywords as $keyword) {
                    if (str_contains($haystack, mb_strtolower($keyword))) {
                        return true;
                    }
                }

                return false;
            });
        };

        $marketCategory = $pickCategoryByKeywords($allNewsCategories, ['thi truong', 'nganh', 'hoi cho']);
        $productCategory = $pickCategoryByKeywords($allNewsCategories, ['san pham', 'may']);
        $guideCategory = $pickCategoryByKeywords($allNewsCategories, ['huong dan', 'ky thuat']);

        return [
            'featuredPosts' => $featuredPosts,
            'marketCategory' => $marketCategory,
            'productCategory' => $productCategory,
            'guideCategory' => $guideCategory,
        ];
    }

    public function index(Request $request, SeoService $seoService)
    {
        $keyword = trim((string) $request->query('q', ''));

        $query = Post::published()->with('category');
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('excerpt', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        $posts = $query->latest('updated_at')->paginate(9)->withQueryString();
        $seo = $seoService->defaults(
            \App\Models\Setting::getValue('seo_news_title', 'Tin tức & Sự kiện'),
            \App\Models\Setting::getValue('seo_news_description', 'Cập nhật tin tức mới nhất về ngành may mặc và sự kiện công nghệ.')
        );

        return view('front.pages.news.index', array_merge(
            compact('posts', 'seo', 'keyword'),
            $this->buildNewsSidebarData()
        ));
    }

    public function category(Request $request, string $slug, SeoService $seoService)
    {
        $category = Category::where('slug', $slug)->where('type', 'news')->firstOrFail();
        $keyword = trim((string) $request->query('q', ''));

        $query = $category->posts()->published()->with('category');
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('excerpt', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        $posts = $query->latest('updated_at')->paginate(9)->withQueryString();

        $seo = $seoService->forModel($category);

        return view('front.pages.news.category', array_merge(
            compact('category', 'posts', 'seo', 'keyword'),
            $this->buildNewsSidebarData()
        ));
    }

    public function show(string $slug, SeoService $seoService)
    {
        $post = Post::with('category', 'author')->where('slug', $slug)->published()->firstOrFail();
        $post->incrementViewCount();

        $relatedPosts = Post::published()
            ->with('category')
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('updated_at')
            ->take(3)
            ->get();

        $seo = $seoService->forModel($post);
        $seo['schema_markup'][] = $seoService->articleSchema($post);
        $seo['schema_markup'][] = $seoService->breadcrumbSchema([
            ['name' => 'Trang chủ', 'url' => route('home')],
            ['name' => 'Tin tức', 'url' => route('news.index')],
            ['name' => $post->title, 'url' => $post->url],
        ]);

        return view('front.pages.news.show', array_merge(
            compact('post', 'relatedPosts', 'seo'),
            $this->buildNewsSidebarData()
        ));
    }
}