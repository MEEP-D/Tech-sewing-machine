<?php

namespace App\Providers;

use App\Services\MenuService;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Observers\PostObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tech-sewing');
        Post::observe(PostObserver::class);

        try {
            $isAdminRequest = request()->is('admin') || request()->is('admin/*');

            if (! $isAdminRequest && Schema::hasTable('settings')) {
                View::composer('front.*', function ($view) {
                    try {
                        $siteSettings = Setting::allAsMap();

                        $view->with('siteSettings', $siteSettings);
                        $view->with('siteProfile', Setting::siteProfile());
                        $view->with('siteContent', [
                            'header_quote_label' => $siteSettings['header_quote_label'] ?? '',
                            'footer_about_title' => $siteSettings['footer_about_title'] ?? '',
                            'footer_about_text' => $siteSettings['footer_about_text'] ?? '',
                            'home_slogan_title' => $siteSettings['home_slogan_title'] ?? '',
                            'home_slogan_subtitle' => $siteSettings['home_slogan_subtitle'] ?? '',
                            'home_partners_title' => $siteSettings['home_partners_title'] ?? '',
                            'home_service_title' => $siteSettings['home_service_title'] ?? '',
                            'home_service_description' => $siteSettings['home_service_description'] ?? '',
                            'home_service_primary_cta' => $siteSettings['home_service_primary_cta'] ?? '',
                            'home_service_secondary_cta' => $siteSettings['home_service_secondary_cta'] ?? '',
                            'home_highlight_contact_primary_name' => $siteSettings['home_highlight_contact_primary_name'] ?? 'Mr. Sáng',
                            'home_highlight_contact_primary_phone' => $siteSettings['home_highlight_contact_primary_phone'] ?? '0902 806 599',
                            'home_highlight_contact_secondary_name' => $siteSettings['home_highlight_contact_secondary_name'] ?? 'Mr. Bảo',
                            'home_highlight_contact_secondary_phone' => $siteSettings['home_highlight_contact_secondary_phone'] ?? '0898 303 287',
                            'about_title' => $siteSettings['about_title'] ?? '',
                            'about_subtitle' => $siteSettings['about_subtitle'] ?? '',
                            'about_company_name' => $siteSettings['about_company_name'] ?? '',
                            'about_intro' => $siteSettings['about_intro'] ?? '',
                            'about_slogan' => $siteSettings['about_slogan'] ?? '',
                            'about_body' => $siteSettings['about_body'] ?? '',
                            'contact_page_title' => $siteSettings['contact_page_title'] ?? '',
                            'contact_page_subtitle' => $siteSettings['contact_page_subtitle'] ?? '',
                            'page_news_heading' => $siteSettings['page_news_heading'] ?? 'Tin tuc',
                            'page_news_desc' => $siteSettings['page_news_desc'] ?? 'Cap nhat nhanh thi truong, san pham va huong dan van hanh thuc te cho xuong may.',
                            'page_news_hero_image' => $siteSettings['page_news_hero_image'] ?? null,
                            'page_products_heading' => $siteSettings['page_products_heading'] ?? 'San pham',
                            'page_products_desc' => $siteSettings['page_products_desc'] ?? 'Giai phap may cong nghiep, may lap trinh va phu kien cho xuong san xuat.',
                            'page_products_hero_image' => $siteSettings['page_products_hero_image'] ?? null,
                            'page_about_heading' => $siteSettings['page_about_heading'] ?? ($siteSettings['about_title'] ?? 'Gioi thieu'),
                            'page_about_desc' => $siteSettings['page_about_desc'] ?? ($siteSettings['about_subtitle'] ?? ''),
                            'page_about_hero_image' => $siteSettings['page_about_hero_image'] ?? null,
                            'page_contact_heading' => $siteSettings['page_contact_heading'] ?? ($siteSettings['contact_page_title'] ?? 'Lien he'),
                            'page_contact_desc' => $siteSettings['page_contact_desc'] ?? ($siteSettings['contact_page_subtitle'] ?? ''),
                            'page_contact_hero_image' => $siteSettings['page_contact_hero_image'] ?? null,
                            'newsletter_signup_image' => $siteSettings['newsletter_signup_image'] ?? null,
                            'home_faqs' => Setting::getValue('home_faqs', []),
                        ]);
                    } catch (\Throwable) {
                        $view->with('siteSettings', []);
                        $view->with('siteProfile', []);
                        $view->with('siteContent', []);
                    }
                });
            }

            if (! $isAdminRequest && Schema::hasTable('categories')) {
                View::composer(['front.partials.header', 'front.partials.footer'], function ($view) {
                    $view->with('menuCategories', Category::query()
                        ->where('type', 'product')
                        ->whereNull('parent_id')
                        ->where('is_active', true)
                        ->with('childrenRecursive')
                        ->orderBy('sort_order')
                        ->get());
                });
            }

            if (! $isAdminRequest && Schema::hasTable('pages')) {
                View::share('publicPages', Cache::rememberForever('site_pages_v2', fn () => Page::query()
                    ->active()
                    ->orderBy('title')
                    ->get(['title', 'slug'])
                    ->map(fn (Page $page) => [
                        'title' => $page->title,
                        'slug' => $page->slug,
                    ])
                    ->values()
                    ->all()));
            }

            if (! $isAdminRequest && Schema::hasTable('menus')) {
                View::share('siteMenus', Cache::rememberForever('site_menus_v2', fn () => app(MenuService::class)->grouped()));
            }

        } catch (\Throwable) {
            // Table might not exist yet during migration
        }
    }
}
