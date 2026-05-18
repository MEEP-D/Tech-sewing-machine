<?php

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\NewsController;
use App\Http\Controllers\Front\NewsletterController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\SitemapController;
use Illuminate\Support\Facades\Route;

// --- Home ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Products ---
Route::prefix('san-pham')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/tim-kiem', [ProductController::class, 'search'])->name('search');
    Route::get('/danh-muc/{slug}', [ProductController::class, 'category'])->name('category');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

// --- News / Blog ---
Route::prefix('tin-tuc')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/danh-muc/{slug}', [NewsController::class, 'category'])->name('category');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('show');
});

// --- Static Pages ---
Route::get('/gioi-thieu', [App\Http\Controllers\Front\ContactController::class, 'about'])->name('about');
Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:5,1');
Route::get('/trang/{slug}', [PageController::class, 'show'])->name('pages.show');

// --- Newsletter ---
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('throttle:5,1');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{subscriber}/{hash}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// --- SEO Infrastructure ---
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// --- Friendly Page Slug (must stay last to avoid swallowing named routes) ---
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '^(?!san-pham$|tin-tuc$|trang$|gioi-thieu$|lien-he$|newsletter$|sitemap\\.xml$|robots\\.txt$)[A-Za-z0-9\\-]+$')
    ->name('pages.show.direct');
