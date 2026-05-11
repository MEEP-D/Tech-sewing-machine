<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Product;
use App\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSeoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_seo_returns_expected_keys(): void
    {
        $service = new SeoService();

        $defaults = $service->defaults('Test Title', 'Test Description');

        $this->assertArrayHasKey('meta_title', $defaults);
        $this->assertArrayHasKey('meta_description', $defaults);
        $this->assertArrayHasKey('og_title', $defaults);
        $this->assertArrayHasKey('canonical_url', $defaults);
        $this->assertSame('index, follow', $defaults['robots']);
    }

    public function test_product_schema_contains_product_data(): void
    {
        $product = new Product([
            'name' => 'Máy test',
            'sku' => 'TEST-001',
            'price' => '10.000.000',
            'brand' => 'TechSewing',
            'short_description' => 'Mô tả ngắn',
        ]);
        $product->slug = 'may-test';
        $product->setAttribute('url', 'http://localhost/san-pham/may-test');

        $schema = (new SeoService())->productSchema($product);

        $this->assertSame('Product', $schema['@type']);
        $this->assertSame('Máy test', $schema['name']);
        $this->assertSame('10000000', $schema['offers']['price']);
    }

    public function test_article_schema_contains_article_data(): void
    {
        $post = new Post([
            'title' => 'Bài viết test',
            'excerpt' => 'Mô tả bài viết',
        ]);
        $post->slug = 'bai-viet-test';
        $post->setAttribute('url', 'http://localhost/tin-tuc/bai-viet-test');

        $schema = (new SeoService())->articleSchema($post);

        $this->assertSame('NewsArticle', $schema['@type']);
        $this->assertSame('Bài viết test', $schema['headline']);
        $this->assertSame('Mô tả bài viết', $schema['description']);
    }

    public function test_breadcrumb_schema_builds_list_items(): void
    {
        $schema = (new SeoService())->breadcrumbSchema([
            ['name' => 'Home', 'url' => 'http://localhost'],
            ['name' => 'Products', 'url' => 'http://localhost/san-pham'],
        ]);

        $this->assertSame('BreadcrumbList', $schema['@type']);
        $this->assertCount(2, $schema['itemListElement']);
    }
}
