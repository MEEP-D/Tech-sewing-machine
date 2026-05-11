<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\Product;
use App\Services\SeoService;
use Tests\TestCase;

class SeoServiceTest extends TestCase
{
    public function test_defaults_returns_expected_structure(): void
    {
        $defaults = (new SeoService())->defaults('Trang chủ', 'Mô tả trang chủ');

        $this->assertSame('Trang chủ | Thiết Bị May Mặc Công Nghiệp', $defaults['meta_title']);
        $this->assertSame('Mô tả trang chủ', $defaults['meta_description']);
        $this->assertSame('Trang chủ', $defaults['og_title']);
        $this->assertArrayHasKey('schema_markup', $defaults);
    }

    public function test_product_schema_maps_product_fields(): void
    {
        $product = new Product([
            'name' => 'Máy test',
            'sku' => 'SKU-1',
            'price' => '10.000.000',
            'brand' => 'TechSewing',
            'short_description' => 'Mô tả ngắn',
            'thumbnail' => 'products/thumb.jpg',
        ]);
        $product->slug = 'may-test';
        $product->setAttribute('url', 'http://localhost/san-pham/may-test');

        $schema = (new SeoService())->productSchema($product);

        $this->assertSame('Product', $schema['@type']);
        $this->assertSame('Máy test', $schema['name']);
        $this->assertSame('10000000', $schema['offers']['price']);
    }

    public function test_article_schema_maps_post_fields(): void
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
}
