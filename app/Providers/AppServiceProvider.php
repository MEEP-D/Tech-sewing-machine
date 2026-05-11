<?php

namespace App\Providers;

use App\Services\MenuService;
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

        try {
            if (Schema::hasTable('settings')) {
                View::composer(['front.layouts.app', 'front.partials.header'], function ($view) {
                    $view->with('siteSettings', \App\Models\Setting::all()->pluck('value', 'key')->toArray());
                });
            }

            if (Schema::hasTable('menus')) {
                View::share('siteMenus', app(MenuService::class)->grouped());
            }

        } catch (\Throwable) {
            // Table might not exist yet during migration
        }
    }
}

