<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Lead;
use App\Models\Page;
use App\Models\Post;
use App\Services\DynamicMailConfigService;
use App\Services\PageRenderService;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ContactController extends Controller
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

    public function about(SeoService $seoService, PageRenderService $renderer): View
    {
        $page = Page::query()
            ->whereIn('slug', ['gioi-thieu', '/gioi-thieu'])
            ->where('is_active', true)
            ->first();

        if ($page) {
            $seo = $seoService->forModel($page);
            $isBuilderMode = $page->layout_mode === 'builder';
            $html = $renderer->renderedHtml($page, $isBuilderMode);

            $layout = $page->layout ?: 'default';
            $view = "front.pages.page.layouts.{$layout}";

            if (! view()->exists($view)) {
                $view = 'front.pages.page.show';
            }

            return view($view, array_merge(
                compact('page', 'seo', 'html'),
                $this->buildNewsSidebarData()
            ));
        }

        $seo = $seoService->defaults('Về chúng tôi', 'Tìm hiểu về TechSewing - Giải pháp máy may công nghiệp hàng đầu.');

        return view('front.pages.about', compact('seo'));
    }

    public function index(SeoService $seoService): View
    {
        $seo = $seoService->defaults(
            \App\Models\Setting::getValue('seo_contact_title', 'Liên hệ TechSewing'),
            \App\Models\Setting::getValue('seo_contact_description', 'Nhận tư vấn giải pháp máy may công nghiệp, báo giá, demo và hỗ trợ kỹ thuật từ TechSewing.')
        );

        return view('front.pages.contact', compact('seo'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'interest' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'email.email' => 'Email không đúng định dạng.',
            'message.required' => 'Vui lòng nhập nội dung cần hỗ trợ.',
        ]);

        $lead = Lead::create([
            ...$validated,
            'source' => 'website_contact',
            'status' => 'new',
        ]);

        try {
            $adminEmail = \App\Models\Setting::getValue('contact_email', 'admin@techsewing.vn');
            app(DynamicMailConfigService::class)->apply();
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\NewLeadNotification($lead));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send new lead email: ' . $e->getMessage());
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Yêu cầu đã được gửi thành công. TechSewing sẽ liên hệ với bạn trong thời gian sớm nhất.');
    }
}
