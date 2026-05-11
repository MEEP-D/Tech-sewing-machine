<?php

namespace Tests\Feature;

use App\Filament\Pages\SeoSettings;
use App\Filament\Pages\SiteSettings;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Section;
use App\Models\Setting;
use App\Models\User;
use App\Services\HomePageService;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDataFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_load_existing_settings_into_form_state(): void
    {
        Setting::updateOrCreate(['key' => 'site_title'], ['value' => 'Saved Site', 'group' => 'branding']);
        Setting::updateOrCreate(['key' => 'seo_default_title'], ['value' => 'Saved SEO', 'group' => 'seo']);

        $admin = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($admin)
            ->test(SiteSettings::class)
            ->assertSet('data.site_title', 'Saved Site')
            ->assertSet('data.seo_default_title', 'Saved SEO');

        Livewire::actingAs($admin)
            ->test(SeoSettings::class)
            ->assertSet('data.seo_default_title', 'Saved SEO');
    }

    public function test_admin_pages_save_form_state_to_database(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($admin)
            ->test(SiteSettings::class)
            ->set('data.site_title', 'Tech Sewing Machine')
            ->set('data.site_description', 'Máy may công nghiệp')
            ->set('data.site_logo_type', 'image')
            ->set('data.site_logo_height', 56)
            ->set('data.site_logo_width', 220)
            ->set('data.seo_default_title', 'Default SEO Title')
            ->set('data.seo_default_description', 'Default SEO Description')
            ->call('save');

        Livewire::actingAs($admin)
            ->test(SeoSettings::class)
            ->set('data.seo_default_title', 'SEO Title')
            ->set('data.seo_default_description', 'SEO Description')
            ->set('data.seo_enable_schema', false)
            ->set('data.seo_enable_og', true)
            ->call('save');

        $this->assertSame('Tech Sewing Machine', Setting::getValue('site_title'));
        $this->assertSame('Máy may công nghiệp', Setting::getValue('site_description'));
        $this->assertSame('image', Setting::getValue('site_logo_type'));
        $this->assertSame(56, Setting::getValue('site_logo_height'));
        $this->assertSame(220, Setting::getValue('site_logo_width'));
        $this->assertSame('SEO Title', Setting::getValue('seo_default_title'));
        $this->assertSame('SEO Description', Setting::getValue('seo_default_description'));
        $this->assertFalse((bool) Setting::getValue('seo_enable_schema'));
        $this->assertTrue((bool) Setting::getValue('seo_enable_og'));
    }

    public function test_core_admin_resources_persist_expected_data_shapes(): void
    {
        $productCategory = Category::create([
            'name' => 'Máy công nghiệp',
            'slug' => 'may-cong-nghiep',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $newsCategory = Category::create([
            'name' => 'Tin tức',
            'slug' => 'tin-tuc',
            'type' => 'news',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'name' => 'Máy may test',
            'slug' => 'may-may-test',
            'sku' => 'SKU-001',
            'short_description' => 'Mô tả ngắn',
            'description' => '<p>Nội dung</p>',
            'price' => '10000000',
            'brand' => 'TechSewing',
            'origin' => 'VN',
            'specifications' => [['key' => 'Tốc độ', 'value' => '5000rpm']],
            'thumbnail' => 'products/thumb.jpg',
            'gallery' => ['products/1.jpg'],
            'category_id' => $productCategory->id,
            'status' => 'published',
            'is_featured' => true,
            'is_new' => true,
            'sort_order' => 1,
            'view_count' => 5,
        ]);

        $post = Post::create([
            'title' => 'Bài viết test',
            'slug' => 'bai-viet-test',
            'excerpt' => 'Tóm tắt',
            'content' => '<p>Nội dung</p>',
            'thumbnail' => 'posts/thumb.jpg',
            'category_id' => $newsCategory->id,
            'author_id' => User::factory()->create()->id,
            'status' => 'published',
            'type' => 'news',
            'published_at' => now(),
            'is_featured' => true,
            'view_count' => 3,
        ]);

        $menu = Menu::create([
            'location' => 'header',
            'label' => 'Trang chủ',
            'url' => '/',
            'route_name' => 'home',
            'target' => '_self',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $section = Section::create([
            'key' => 'hero',
            'name' => 'Hero',
            'title' => 'Hero section',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Partner::create([
            'name' => 'Partner 1',
            'logo' => 'partners/logo.png',
            'website' => 'https://example.com',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->assertSame('may-may-test', $product->slug);
        $this->assertSame(['products/1.jpg'], $product->gallery);
        $this->assertTrue($product->is_featured);
        $this->assertSame('bai-viet-test', $post->slug);
        $this->assertSame('header', $menu->location);
        $this->assertSame('hero', $section->key);
        $this->assertSame('may-cong-nghiep', $productCategory->slug);
        $this->assertSame('tin-tuc', $newsCategory->slug);
    }

    public function test_service_layer_uses_database_state_for_admin_and_frontend_data(): void
    {
        $productCategory = Category::create([
            'name' => 'Máy công nghiệp',
            'slug' => 'may-cong-nghiep',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'name' => 'Máy featured',
            'slug' => 'may-featured',
            'price' => '10000000',
            'category_id' => $productCategory->id,
            'status' => 'published',
            'is_featured' => true,
            'is_new' => false,
            'sort_order' => 1,
        ]);

        Product::create([
            'name' => 'Máy latest',
            'slug' => 'may-latest',
            'price' => '12000000',
            'category_id' => $productCategory->id,
            'status' => 'published',
            'is_featured' => false,
            'is_new' => true,
            'sort_order' => 2,
        ]);

        Post::create([
            'title' => 'Tin mới',
            'slug' => 'tin-moi',
            'category_id' => Category::create([
                'name' => 'Tin tức',
                'slug' => 'tin-tuc',
                'type' => 'news',
                'is_active' => true,
                'sort_order' => 1,
            ])->id,
            'author_id' => User::factory()->create()->id,
            'status' => 'published',
            'type' => 'news',
            'published_at' => now(),
        ]);

        Section::create([
            'key' => 'hero',
            'name' => 'Hero',
            'title' => 'Hero section',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Menu::create([
            'location' => 'header',
            'label' => 'Trang chủ',
            'url' => '/',
            'route_name' => 'home',
            'target' => '_self',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $data = app(HomePageService::class)->data();
        $menus = app(MenuService::class)->grouped();

        $this->assertArrayHasKey('featuredProducts', $data);
        $this->assertCount(1, $data['featuredProducts']);
        $this->assertSame('may-featured', $data['featuredProducts']->first()->slug);
        $this->assertArrayHasKey('header', $menus);
        $this->assertSame('Trang chủ', $menus['header'][0]['label']);
    }
}
