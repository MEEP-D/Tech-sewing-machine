<?php

namespace Tests\Feature;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Models\Page;
use App\Models\SeoMeta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPageResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_fields_persist_correctly(): void
    {
        $page = Page::create([
            'title' => 'Giới thiệu',
            'slug' => 'gioi-thieu',
            'excerpt' => 'Giới thiệu công ty',
            'content' => '<p>Nội dung</p>',
            'image' => 'pages/about.jpg',
            'is_active' => true,
            'layout' => 'content',
            'layout_mode' => 'content',
            'cache_enabled' => true,
            'cache_ttl' => 3600,
            'container_class' => 'section-shell',
        ]);

        $this->assertSame('gioi-thieu', $page->slug);
        $this->assertTrue($page->is_active);
        $this->assertSame('content', $page->layout);
    }

    public function test_page_seo_meta_persists_from_admin_form(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($admin)
            ->test(CreatePage::class)
            ->fillForm([
                'title' => 'Giới thiệu',
                'slug' => 'gioi-thieu',
                'layout_mode' => 'content',
                'cache_ttl' => 3600,
                'cache_enabled' => true,
                'is_active' => true,
                'seoMeta' => [
                    'meta_title' => 'Giới thiệu Tech Sewing',
                    'meta_description' => 'Thông tin doanh nghiệp Tech Sewing',
                    'focus_keyword' => 'máy may công nghiệp',
                    'og_title' => 'Giới thiệu Tech Sewing',
                    'og_description' => 'Thông tin doanh nghiệp Tech Sewing',
                    'canonical_url' => 'https://example.com/trang/gioi-thieu',
                    'no_index' => false,
                    'no_follow' => false,
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $page = Page::where('slug', 'gioi-thieu')->firstOrFail();
        $seo = SeoMeta::whereMorphedTo('seoable', $page)->first();

        $this->assertNotNull($seo);
        $this->assertSame('Giới thiệu Tech Sewing', $seo->meta_title);
        $this->assertSame('máy may công nghiệp', $seo->focus_keyword);
    }
}
