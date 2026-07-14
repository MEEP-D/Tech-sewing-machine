<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_panel()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_panel()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_product_detail_page_displays_correctly()
    {
        $category = Category::create([
            'name' => 'May cong nghiep',
            'slug' => 'may-cong-nghiep',
            'type' => 'product',
        ]);

        Product::create([
            'name' => 'May may Juki 2026',
            'slug' => 'may-may-juki-2026',
            'price' => '25.000.000',
            'category_id' => $category->id,
            'status' => 'published',
            'is_featured' => false,
            'is_new' => true,
            'sort_order' => 0,
            'view_count' => 10,
            'specifications' => [['key' => 'Toc do', 'value' => '5000rpm']],
            'specification_images' => ['products/specification-images/spec-01.jpg'],
        ]);

        $response = $this->get('/san-pham/may-may-juki-2026');

        $response->assertStatus(200);
        $response->assertSee('May may Juki 2026');
        $response->assertSee('25.000.000');
        $response->assertSee('5000rpm');
        $response->assertSee('product-spec-gallery');
    }

    public function test_product_detail_page_renders_usage_guide_content_from_admin(): void
    {
        $category = Category::create([
            'name' => 'May lap trinh',
            'slug' => 'may-lap-trinh',
            'type' => 'product',
        ]);

        Product::create([
            'name' => 'May co huong dan',
            'slug' => 'may-co-huong-dan',
            'price' => '30.000.000',
            'category_id' => $category->id,
            'status' => 'published',
            'usage_guide_content' => '<p>Buoc 1: Cai dat may.</p>',
            'usage_guide_video_id' => 'guide12345',
            'usage_guide_attachment' => 'products/guides/huong-dan.pdf',
        ]);

        $response = $this->get('/san-pham/may-co-huong-dan');

        $response->assertOk();
        $response->assertSee('Hướng dẫn sử dụng');
        $response->assertSee('Buoc 1: Cai dat may.', false);
        $response->assertSee('https://www.youtube.com/embed/guide12345', false);
        $response->assertSee('/storage/products/guides/huong-dan.pdf', false);
        $response->assertDontSee('Đánh giá đang được cập nhật.');
    }
    public function test_product_detail_page_renders_active_promotion_popup_content(): void
    {
        $category = Category::create([
            'name' => 'May lay dau',
            'slug' => 'may-lay-dau',
            'type' => 'product',
        ]);

        Product::create([
            'name' => 'May lay dau tu dong',
            'slug' => 'may-lay-dau-tu-dong',
            'price' => '18.500.000',
            'category_id' => $category->id,
            'status' => 'published',
            'installment_percent' => true,
            'promotion_title' => 'Qua tang kem',
            'promotion_description' => 'Mua may lay dau tu dong - tang 1 iPhone',
            'promotion_gift_name' => 'iPhone 16',
            'promotion_gift_image' => 'products/promotions/iphone.jpg',
            'promotion_starts_at' => now()->subDay(),
            'promotion_ends_at' => now()->addDays(3),
        ]);

        $response = $this->get('/san-pham/may-lay-dau-tu-dong');

        $response->assertOk();
        $response->assertSee('Khuy');
        $response->assertSee('Qua tang kem');
        $response->assertSee('Mua may lay dau tu dong - tang 1 iPhone');
        $response->assertSee('/storage/products/promotions/iphone.jpg', false);
        $response->assertSee('data-promo-popup', false);
    }

    public function test_expired_promotion_badge_is_hidden(): void
    {
        $category = Category::create([
            'name' => 'May may',
            'slug' => 'may-may',
            'type' => 'product',
        ]);

        Product::create([
            'name' => 'May may het khuyen mai',
            'slug' => 'may-may-het-khuyen-mai',
            'price' => '18.500.000',
            'category_id' => $category->id,
            'status' => 'published',
            'installment_percent' => true,
            'promotion_title' => 'Qua tang kem',
            'promotion_description' => 'Da het han',
            'promotion_ends_at' => now()->subMinute(),
        ]);

        $response = $this->get('/san-pham/may-may-het-khuyen-mai');

        $response->assertOk();
        $response->assertDontSee('data-promo-popup', false);
    }

    public function test_product_index_page_renders_admin_hero_and_product_marquee(): void
    {
        $category = Category::create([
            'name' => 'May demo',
            'slug' => 'may-demo',
            'type' => 'product',
        ]);

        Setting::create(['key' => 'page_products_kicker', 'value' => 'Bo suu tap moi']);
        Setting::create(['key' => 'page_products_heading', 'value' => 'San pham moi nhat']);
        Setting::create(['key' => 'page_products_desc', 'value' => 'Noi dung mo ta tu admin cho hero san pham.']);
        Setting::create(['key' => 'page_products_hero_image', 'value' => 'site/products-hero.png']);

        foreach (range(1, 10) as $index) {
            Product::create([
                'name' => "May demo {$index}",
                'slug' => "may-demo-{$index}",
                'code' => "DEMO-{$index}",
                'short_description' => "Mo ta ngan cho may demo {$index}",
                'price' => '10.000.000',
                'category_id' => $category->id,
                'status' => 'published',
                'is_new' => true,
                'show_in_banner_switcher' => true,
            ]);
        }

        $response = $this->get('/san-pham');

        $response->assertOk();
        $response->assertSee('Bo suu tap moi');
        $response->assertSee('San pham moi nhat');
        $response->assertSee('Noi dung mo ta tu admin cho hero san pham.');
        $response->assertSee('/storage/site/products-hero.png', false);
        $response->assertSee('products-hero-marquee-track', false);
        $response->assertSee('products-hero-card', false);
        $response->assertSee('products-hero-card-meta', false);
        $response->assertSee('products-hero-card-summary', false);
        $this->assertSame(1, substr_count($response->getContent(), 'products-hero-marquee-row'));
        $response->assertDontSee('products-hero-marquee-row is-reverse', false);
        $response->assertSee('May demo 1');
    }
}
