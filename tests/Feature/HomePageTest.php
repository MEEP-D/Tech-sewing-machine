<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_successfully(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('TechSewing');
    }

    public function test_home_page_displays_featured_products(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $category = Category::create([
            'name' => 'May May Lap Trinh',
            'slug' => 'may-may-lap-trinh',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'name' => 'May test noi bat',
            'slug' => 'may-test-noi-bat',
            'price' => '10.000.000',
            'category_id' => $category->id,
            'status' => 'published',
            'is_featured' => true,
            'is_new' => false,
            'sort_order' => 1,
            'view_count' => 0,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('May test noi bat');
    }

    public function test_home_page_displays_active_banners_from_database(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        Banner::create([
            'key' => 'test-home-banner',
            'title' => 'Banner DB Title',
            'subtitle' => 'Banner DB Subtitle',
            'image' => 'banners/test.jpg',
            'link' => '/lien-he',
            'button_text' => 'Xem ngay',
            'size_label' => 'Hero',
            'recommended_size' => '1600x700',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Banner DB Title');
    }
}

