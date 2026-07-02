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
            'name' => 'May cong nghiep',
            'slug' => 'may-cong-nghiep',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'name' => 'May may test',
            'slug' => 'may-may-test',
            'sku' => 'SKU-001',
            'short_description' => 'Mo ta ngan',
            'description' => '<p>Noi dung</p>',
            'price' => '10000000',
            'brand' => 'TechSewing',
            'origin' => 'VN',
            'usage_guide_content' => '<p>Huong dan su dung</p>',
            'usage_guide_video_id' => 'abc123xyz',
            'usage_guide_attachment' => 'products/guides/huong-dan.pdf',
            'specifications' => [['key' => 'Toc do', 'value' => '5000rpm']],
            'thumbnail' => 'products/thumb.jpg',
            'gallery' => ['products/1.jpg'],
            'specification_images' => ['products/specification-images/spec-01.jpg'],
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
        $this->assertSame(['products/specification-images/spec-01.jpg'], $product->specification_images);
        $this->assertSame('abc123xyz', $product->usage_guide_video_id);
        $this->assertSame('products/guides/huong-dan.pdf', $product->usage_guide_attachment);
    }
}
