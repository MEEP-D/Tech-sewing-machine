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
        Schema::defaultStringLength(191);

        Paginator::defaultView('vendor.pagination.tech-sewing');
        Post::observe(PostObserver::class);

        try {
            View::composer('front.*', function ($view): void {
                $payload = [
                    'siteSettings' => [],
                    'siteProfile' => [],
                    'siteContent' => [],
                    'menuCategories' => collect(),
                    'publicPages' => [],
                    'siteMenus' => [],
                ];

                try {
                    if (Schema::hasTable('settings')) {
                        $siteSettings = Setting::allAsMap();

                        $payload['siteSettings'] = $siteSettings;
                        $payload['siteProfile'] = Setting::siteProfile();
                        $payload['siteContent'] = [
                            'header_quote_label' => $siteSettings['header_quote_label'] ?? '',
                            'footer_about_title' => $siteSettings['footer_about_title'] ?? '',
                            'footer_about_text' => $siteSettings['footer_about_text'] ?? '',
                            'home_partners_title' => $siteSettings['home_partners_title'] ?? '',
                            'home_service_title' => $siteSettings['home_service_title'] ?? '',
                            'home_service_description' => $siteSettings['home_service_description'] ?? '',
                            'home_service_primary_cta' => $siteSettings['home_service_primary_cta'] ?? '',
                            'home_service_secondary_cta' => $siteSettings['home_service_secondary_cta'] ?? '',
                            'home_service_image' => $siteSettings['home_service_image'] ?? null,
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
                            'page_news_heading' => $siteSettings['page_news_heading'] ?? 'Tin tức',
                            'page_news_desc' => $siteSettings['page_news_desc'] ?? 'Cập nhật nhanh thị trường, sản phẩm và hướng dẫn vận hành thực tế cho xưởng may.',
                            'page_news_hero_image' => $siteSettings['page_news_hero_image'] ?? null,
                            'page_products_heading' => $siteSettings['page_products_heading'] ?? 'Sản phẩm',
                            'page_products_desc' => $siteSettings['page_products_desc'] ?? 'Giải pháp máy công nghiệp, máy lập trình và phụ kiện cho xưởng sản xuất.',
                            'page_products_hero_image' => $siteSettings['page_products_hero_image'] ?? null,
                            'page_about_heading' => $siteSettings['page_about_heading'] ?? ($siteSettings['about_title'] ?? 'Giới thiệu'),
                            'page_about_desc' => $siteSettings['page_about_desc'] ?? ($siteSettings['about_subtitle'] ?? ''),
                            'page_about_hero_image' => $siteSettings['page_about_hero_image'] ?? null,
                            'page_contact_heading' => $siteSettings['page_contact_heading'] ?? ($siteSettings['contact_page_title'] ?? 'Liên hệ'),
                            'page_contact_desc' => $siteSettings['page_contact_desc'] ?? ($siteSettings['contact_page_subtitle'] ?? ''),
                            'page_contact_hero_image' => $siteSettings['page_contact_hero_image'] ?? null,
                            'newsletter_signup_image' => $siteSettings['newsletter_signup_image'] ?? null,
                            'newsletter_signup_title' => $siteSettings['newsletter_signup_title'] ?? '',
                            'newsletter_signup_description' => $siteSettings['newsletter_signup_description'] ?? '',
                            'newsletter_signup_button_text' => $siteSettings['newsletter_signup_button_text'] ?? '',
                            'newsletter_signup_note' => $siteSettings['newsletter_signup_note'] ?? '',
                            'home_faqs' => Setting::getValue('home_faqs', []),
                        ];
                    }

                    if (Schema::hasTable('categories')) {
                        $payload['menuCategories'] = collect(Cache::rememberForever('front_menu_categories_v1', function (): array {
                            $mapCategory = function (Category $category) use (&$mapCategory): array {
                                return [
                                    'name' => $category->name,
                                    'slug' => $category->slug,
                                    'highlight_mega_label' => $category->highlight_mega_label,
                                    'children' => $category->childrenRecursive->map($mapCategory)->values()->all(),
                                ];
                            };

                            return Category::query()
                                ->where('type', 'product')
                                ->whereNull('parent_id')
                                ->where('is_active', true)
                                ->with('childrenRecursive')
                                ->orderBy('sort_order')
                                ->get()
                                ->map($mapCategory)
                                ->values()
                                ->all();
                        }));
                    }

                    if (Schema::hasTable('pages')) {
                        $payload['publicPages'] = Cache::rememberForever('site_pages_v2', fn () => Page::query()
                            ->active()
                            ->orderBy('title')
                            ->get(['title', 'slug'])
                            ->map(fn (Page $page) => [
                                'title' => $page->title,
                                'slug' => ltrim((string) $page->slug, '/'),
                            ])
                            ->filter(fn (array $page) => $page['slug'] !== '')
                            ->values()
                            ->all());
                    }

                    if (Schema::hasTable('menus')) {
                        $payload['siteMenus'] = Cache::rememberForever('site_menus_v2', fn () => app(MenuService::class)->grouped());
                    }
                } catch (\Throwable) {
                    // Table might not exist yet during migration.
                }

                $view->with($payload);
            });

        } catch (\Throwable) {
            // Table might not exist yet during migration
        }
    }
}
