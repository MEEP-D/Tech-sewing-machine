<?php

namespace App\Http\Controllers\Front;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Services\PageRenderService;
use App\Services\SeoService;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PageController extends Controller
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

        $latestPosts = Post::published()
            ->with('category')
            ->latest('published_at')
            ->take(6)
            ->get();

        $hotLatestPosts = $featuredPosts
            ->merge($latestPosts)
            ->unique('id')
            ->take(6)
            ->values();

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
            'latestPosts' => $latestPosts->take(5),
            'technicalGuidePosts' => $technicalGuidePosts,
            'marketCategory' => $marketCategory,
            'productCategory' => $productCategory,
            'guideCategory' => $guideCategory,
            'hotLatestPosts' => $hotLatestPosts,
        ];
    }

    public function show(string $slug, SeoService $seoService, PageRenderService $renderer): View
    {
        $normalizedSlug = ltrim(trim($slug), '/');
        $page = Page::query()
            ->whereIn('slug', [$normalizedSlug, '/' . $normalizedSlug])
            ->where('is_active', true)
            ->firstOrFail();
        $seo = $seoService->forModel($page);
        $isBuilderMode = $page->layout_mode === 'builder';
        $html = $renderer->renderedHtml($page, $isBuilderMode);

        $layout = $page->layout ?: 'default';
        $view = "front.pages.page.layouts.{$layout}";
        
        if (!view()->exists($view)) {
            $view = 'front.pages.page.show'; // fallback
        }

        return view($view, array_merge(
            compact('page', 'seo', 'html'),
            $this->buildNewsSidebarData()
        ));
    }
}
