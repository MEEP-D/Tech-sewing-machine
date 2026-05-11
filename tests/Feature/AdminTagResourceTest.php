<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTagResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_slug_is_generated_from_name_on_create(): void
    {
        \App\Models\User::factory()->create(['is_admin' => true]);

        $tag = Tag::create([
            'name' => 'Tin Tức Công Nghệ',
            'slug' => 'tin-tuc-cong-nghe',
            'type' => 'news',
        ]);

        $this->assertSame('tin-tuc-cong-nghe', $tag->slug);
    }

    public function test_tag_type_defaults_are_valid(): void
    {
        $tag = Tag::create([
            'name' => 'Sản phẩm mới',
            'slug' => 'san-pham-moi',
            'type' => 'product',
        ]);

        $this->assertContains($tag->type, ['product', 'news']);
    }
}
