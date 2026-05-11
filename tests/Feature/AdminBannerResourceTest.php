<?php

namespace Tests\Feature;

use App\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBannerResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_banner_fields_persist_correctly(): void
    {
        $banner = Banner::create([
            'key' => 'home-hero',
            'title' => 'Banner chính',
            'subtitle' => 'Banner mô tả',
            'image' => 'site/banner.jpg',
            'link' => 'https://example.com',
            'button_text' => 'Xem ngay',
            'size_label' => '1920x720',
            'recommended_size' => '1920 x 720 px',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->assertSame('home-hero', $banner->key);
        $this->assertSame('Banner chính', $banner->title);
        $this->assertSame('site/banner.jpg', $banner->image);
        $this->assertTrue($banner->is_active);
    }
}
