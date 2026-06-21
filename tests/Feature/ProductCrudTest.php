<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
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
}
