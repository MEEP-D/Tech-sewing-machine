<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Product;
use App\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_default_seo_data(): void
    {
        $defaults = (new SeoService())->defaults('Trang chủ', 'Mô tả trang chủ');

        $this->assertSame('Trang chủ | Thiết Bị May Mặc Công Nghiệp', $defaults['meta_title']);
        $this->assertSame('Mô tả trang chủ', $defaults['meta_description']);
        $this->assertSame('index, follow', $defaults['robots']);
    }

    public function test_it_can_generate_product_json_ld_schema(): void
    {
        $product = new Product([
            'name' => 'Máy test',
            'sku' => 'SKU-1',
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

    public function test_it_can_generate_breadcrumb_schema(): void
    {
        $schema = (new SeoService())->breadcrumbSchema([
            ['name' => 'Home', 'url' => 'http://localhost'],
            ['name' => 'Products', 'url' => 'http://localhost/san-pham'],
        ]);

        $this->assertSame('BreadcrumbList', $schema['@type']);
        $this->assertCount(2, $schema['itemListElement']);
    }
}
