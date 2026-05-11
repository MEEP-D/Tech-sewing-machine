<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\HomePageService;
use App\Services\SeoService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(SeoService $seoService, HomePageService $homePageService): View
    {
        $home = $homePageService->data();

        $seo = $seoService->defaults();
        $seo['schema_markup'][] = $seoService->organizationSchema();

        return view('front.pages.home', array_merge($home, [
            'seo' => $seo,
        ]));
    }
}
