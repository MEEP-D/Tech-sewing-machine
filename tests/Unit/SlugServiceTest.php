<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\SlugService;

class SlugServiceTest extends TestCase
{
    protected SlugService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SlugService();
    }

    public function test_it_can_slugify_vietnamese_text()
    {
        $text = 'Máy May Lập Trình Tự Động';
        $slug = $this->service->make($text);

        $this->assertEquals('may-may-lap-trinh-tu-dong', $slug);
    }

    public function test_it_handles_complex_vietnamese_chars()
    {
        $text = 'Hội chợ triển lãm Dệt may 2026';
        $slug = $this->service->make($text);

        $this->assertEquals('hoi-cho-trien-lam-det-may-2026', $slug);
    }

    public function test_it_removes_special_characters()
    {
        $text = 'Sản phẩm mới !!! (Giá cực tốt)';
        $slug = $this->service->make($text);

        $this->assertEquals('san-pham-moi-gia-cuc-tot', $slug);
    }
}
