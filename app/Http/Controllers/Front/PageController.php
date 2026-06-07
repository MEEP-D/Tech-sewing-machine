<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\PageRenderService;
use App\Services\SeoService;
use Illuminate\View\View;

class PageController extends Controller
{
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

        return view($view, compact('page', 'seo', 'html'));
    }
}
