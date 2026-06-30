<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_fields_persist_correctly(): void
    {
        $category = Category::create([
            'name' => 'May cong nghiep',
            'slug' => 'may-cong-nghiep',
            'description' => 'Danh muc may',
            'type' => 'product',
            'parent_id' => null,
            'image' => 'categories/may.jpg',
            'is_active' => true,
            'sort_order' => 1,
            'highlight_mega_label' => true,
        ]);

        $this->assertSame('may-cong-nghiep', $category->slug);
        $this->assertSame('product', $category->type);
        $this->assertTrue($category->is_active);
        $this->assertTrue($category->highlight_mega_label);
    }
}
