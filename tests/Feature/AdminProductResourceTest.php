<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_fields_persist_correctly(): void
    {
        $category = Category::create([
            'name' => 'Máy công nghiệp',
            'slug' => 'may-cong-nghiep',
            'type' => 'product',
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
            'category_id' => $category->id,
            'status' => 'published',
            'is_featured' => true,
            'is_new' => true,
            'sort_order' => 1,
            'view_count' => 5,
        ]);

        $this->assertSame('may-may-test', $product->slug);
        $this->assertTrue($product->is_featured);
        $this->assertSame(['products/1.jpg'], $product->gallery);
    }
}
