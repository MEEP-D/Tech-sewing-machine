<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PageFrontendRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_save_and_delete_clear_public_pages_cache_key(): void
    {
        Cache::forever('site_pages_v2', ['stale' => true]);

        $page = Page::create([
            'title' => 'Cache Test',
            'slug' => 'cache-test',
            'content' => '<p>Cache content</p>',
            'layout_mode' => 'content',
            'is_active' => true,
        ]);

        $this->assertFalse(Cache::has('site_pages_v2'));

        Cache::forever('site_pages_v2', ['stale' => true]);
        $page->delete();

        $this->assertFalse(Cache::has('site_pages_v2'));
    }

    public function test_about_route_prefers_page_content_when_page_exists(): void
    {
        Page::create([
            'title' => 'Gioi thieu',
            'slug' => '/gioi-thieu',
            'content' => '<p>About page from admin editor</p>',
            'layout' => 'blank',
            'layout_mode' => 'content',
            'is_active' => true,
        ]);

        $response = $this->get('/gioi-thieu');

        $response->assertOk();
        $response->assertSee('About page from admin editor', false);
    }

    public function test_layout_select_applies_different_template(): void
    {
        Page::create([
            'title' => 'Default Layout Page',
            'slug' => 'default-layout-page',
            'content' => '<p>Default content</p>',
            'layout' => 'default',
            'layout_mode' => 'content',
            'is_active' => true,
        ]);

        Page::create([
            'title' => 'Full Width Layout Page',
            'slug' => 'full-width-layout-page',
            'content' => '<p>Full width content</p>',
            'layout' => 'full_width',
            'layout_mode' => 'content',
            'is_active' => true,
        ]);

        $this->get('/default-layout-page')
            ->assertOk()
            ->assertSee('page-layout-default', false)
            ->assertDontSee('page-layout-full-width', false);

        $this->get('/full-width-layout-page')
            ->assertOk()
            ->assertSee('page-layout-full-width', false);
    }

    public function test_layout_mode_select_applies_builder_wrapper(): void
    {
        Page::create([
            'title' => 'Builder Mode Page',
            'slug' => 'builder-mode-page',
            'content' => '<p>Builder mode content</p>',
            'layout' => 'default',
            'layout_mode' => 'builder',
            'style_config' => [
                'max_width' => '920px',
            ],
            'is_active' => true,
        ]);

        $this->get('/builder-mode-page')
            ->assertOk()
            ->assertSee('page-builder-layout', false)
            ->assertSee('max-width: 920px', false);
    }
}
