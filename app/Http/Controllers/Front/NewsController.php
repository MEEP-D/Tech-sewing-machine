<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class NewsController extends Controller
{
    private const HERO_POST_LIMIT = 10;

    private function heroPosts(): Collection
    {
        $limit = self::HERO_POST_LIMIT;
        $baseQuery = Post::published()
            ->with('category')
            ->latest('published_at')
            ->latest('updated_at');

        $featuredPosts = (clone $baseQuery)
            ->where('is_featured', true)
            ->take($limit)
            ->get();

        if ($featuredPosts->count() >= $limit) {
            return $featuredPosts;
        }

        $fallbackPosts = (clone $baseQuery)
            ->when(
                $featuredPosts->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id', $featuredPosts->pluck('id')->all())
            )
            ->take($limit - $featuredPosts->count())
            ->get();

        return $featuredPosts
            ->concat($fallbackPosts)
            ->take($limit)
            ->values();
    }

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

        $latestPosts = Post::published()
            ->with('category')
            ->latest('published_at')
            ->take(5)
            ->get();

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

        $technicalGuidePosts = collect();
        if ($guideCategory) {
            $technicalGuidePosts = Post::published()
                ->with('category')
                ->where('category_id', $guideCategory->id)
                ->latest('published_at')
                ->take(5)
                ->get();
        }

        return [
            'featuredPosts' => $featuredPosts,
            'latestPosts' => $latestPosts,
            'technicalGuidePosts' => $technicalGuidePosts,
            'marketCategory' => $marketCategory,
            'productCategory' => $productCategory,
            'guideCategory' => $guideCategory,
        ];
    }

    public function index(Request $request, SeoService $seoService)
    {
        $keyword = trim((string) $request->query('q', ''));
        $activeTag = trim((string) $request->query('tag', ''));

        $query = Post::published()->with(['category', 'tags']);
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('excerpt', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }
        if ($activeTag !== '') {
            $query->whereHas('tags', fn ($q) => $q->where('type', 'news')->where('slug', $activeTag));
        }

        $posts = $query->latest('updated_at')->paginate(9)->withQueryString();
        $newsTags = Tag::query()
            ->where('type', 'news')
            ->orderBy('name')
            ->get(['name', 'slug']);
        $seo = $seoService->defaults(
            \App\Models\Setting::getValue('seo_news_title', 'Tin tức & Sự kiện'),
            \App\Models\Setting::getValue('seo_news_description', 'Cập nhật tin tức mới nhất về ngành may mặc và sự kiện công nghệ.')
        );

        if ($request->ajax()) {
            return response()->json([
                'html' => view('front.pages.news._list', compact('posts'))->render(),
                'nextPageUrl' => $posts->nextPageUrl(),
            ]);
        }

        $heroPosts = $this->heroPosts();

        return view('front.pages.news.index', array_merge(
            compact('posts', 'seo', 'keyword', 'newsTags', 'activeTag', 'heroPosts'),
            $this->buildNewsSidebarData()
        ));
    }

    public function category(Request $request, string $slug, SeoService $seoService)
    {
        $category = Category::where('slug', $slug)->where('type', 'news')->firstOrFail();
        $keyword = trim((string) $request->query('q', ''));
        $activeTag = trim((string) $request->query('tag', ''));

        $query = $category->posts()->published()->with(['category', 'tags']);
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('excerpt', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }
        if ($activeTag !== '') {
            $query->whereHas('tags', fn ($q) => $q->where('type', 'news')->where('slug', $activeTag));
        }

        $posts = $query->latest('updated_at')->paginate(9)->withQueryString();
        $newsTags = Tag::query()
            ->where('type', 'news')
            ->orderBy('name')
            ->get(['name', 'slug']);
        $seo = $seoService->forModel($category);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('front.pages.news._list', compact('posts'))->render(),
                'nextPageUrl' => $posts->nextPageUrl(),
            ]);
        }

        $heroPosts = $this->heroPosts();

        return view('front.pages.news.category', array_merge(
            compact('category', 'posts', 'seo', 'keyword', 'newsTags', 'activeTag', 'heroPosts'),
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
