<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Services\SeoService;

class NewsController extends Controller
{
    public function index(SeoService $seoService)
    {
        $posts = Post::published()->latest()->paginate(10);
        $seo = $seoService->defaults(
            \App\Models\Setting::getValue('seo_news_title', 'Tin tức & Sự kiện'),
            \App\Models\Setting::getValue('seo_news_description', 'Cập nhật tin tức mới nhất về ngành may mặc và sự kiện công nghệ.')
        );
        
        return view('front.pages.news.index', compact('posts', 'seo'));
    }

    public function category($slug, SeoService $seoService)
    {
        $category = Category::where('slug', $slug)->where('type', 'news')->firstOrFail();
        $posts = $category->posts()->published()->latest()->paginate(10);
        
        $seo = $seoService->forModel($category);
        
        return view('front.pages.news.category', compact('category', 'posts', 'seo'));
    }

    public function show($slug, SeoService $seoService)
    {
        $post = Post::with('category', 'author')->where('slug', $slug)->published()->firstOrFail();
        $post->incrementViewCount();

        $relatedPosts = Post::published()
            ->with('category')
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->take(3)
            ->get();

        $seo = $seoService->forModel($post);
        $seo['schema_markup'][] = $seoService->articleSchema($post);
        $seo['schema_markup'][] = $seoService->breadcrumbSchema([
            ['name' => 'Trang chủ',  'url' => route('home')],
            ['name' => 'Tin tức',    'url' => route('news.index')],
            ['name' => $post->title, 'url' => $post->url],
        ]);

        return view('front.pages.news.show', compact('post', 'relatedPosts', 'seo'));
    }

}
