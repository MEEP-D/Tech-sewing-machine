<?php

namespace Tests\Feature;

use App\Services\SlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SlugServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SlugService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SlugService();
    }

    public function test_it_can_generate_slug_from_vietnamese_text()
    {
        $text = 'Máy may lập trình Brother BAS-311H';
        $expected = 'may-may-lap-trinh-brother-bas-311h';

        $this->assertEquals($expected, $this->service->make($text));
    }

    public function test_it_can_handle_complex_vietnamese_characters()
    {
        $text = 'Xưởng may tự động hóa 4.0 - Thiết bị dệt may';
        $expected = 'xuong-may-tu-dong-hoa-40-thiet-bi-det-may';

        $this->assertEquals($expected, $this->service->make($text));
    }

    public function test_it_can_generate_unique_slugs()
    {
        $category = DB::table('categories')->insertGetId([
            'name' => 'Máy công nghiệp',
            'slug' => 'may-cong-nghiep',
            'type' => 'product',
            'is_active' => 1,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('products')->insert([
            'name' => 'Máy test',
            'slug' => 'may-test',
            'category_id' => $category,
            'status' => 'published',
            'is_featured' => false,
            'is_new' => false,
            'sort_order' => 0,
            'view_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $text = 'Máy test';
        $slug = $this->service->unique($text, 'products');

        $this->assertEquals('may-test-1', $slug);
    }
}
