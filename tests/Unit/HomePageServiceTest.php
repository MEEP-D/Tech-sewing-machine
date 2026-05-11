<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Section;
use App\Models\Setting;
use App\Models\User;
use App\Services\HomePageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_returns_expected_keys_and_records(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $category = Category::create([
            'name' => 'Máy công nghiệp',
            'slug' => 'may-cong-nghiep',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Section::create([
            'key' => 'hero',
            'title' => 'Hero',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'name' => 'Máy nổi bật',
            'slug' => 'may-noi-bat',
            'price' => '10000000',
            'category_id' => $category->id,
            'status' => 'published',
            'is_featured' => true,
            'is_new' => false,
            'sort_order' => 1,
        ]);

        Product::create([
            'name' => 'Máy mới',
            'slug' => 'may-moi',
            'price' => '12000000',
            'category_id' => $category->id,
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
            'author_id' => $admin->id,
            'status' => 'published',
            'type' => 'news',
            'published_at' => now()->subDay(),
        ]);

        Partner::create([
            'name' => 'Partner 1',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Setting::updateOrCreate(['key' => 'home_hero_image'], ['value' => 'images/custom.jpg', 'group' => 'homepage']);

        $data = app(HomePageService::class)->data();

        $this->assertArrayHasKey('sections', $data);
        $this->assertArrayHasKey('featuredProducts', $data);
        $this->assertCount(1, $data['featuredProducts']);
        $this->assertSame('may-noi-bat', $data['featuredProducts']->first()->slug);
        $this->assertCount(1, $data['latestProducts']);
        $this->assertCount(1, $data['latestPosts']);
        $this->assertCount(1, $data['productCategories']);
        $this->assertCount(1, $data['partners']);
        $this->assertSame('images/custom.jpg', $data['homeHeroImage']);
    }
}
