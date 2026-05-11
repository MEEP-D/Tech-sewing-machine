<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_fields_persist_correctly(): void
    {
        $author = User::factory()->create();
        $category = Category::create([
            'name' => 'Tin tức',
            'slug' => 'tin-tuc',
            'type' => 'news',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $post = Post::create([
            'title' => 'Bài viết test',
            'slug' => 'bai-viet-test',
            'excerpt' => 'Tóm tắt',
            'content' => '<p>Nội dung</p>',
            'thumbnail' => 'posts/thumb.jpg',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'status' => 'published',
            'type' => 'news',
            'published_at' => now(),
            'is_featured' => true,
            'view_count' => 3,
        ]);

        $this->assertSame('bai-viet-test', $post->slug);
        $this->assertSame('published', $post->status);
        $this->assertTrue($post->is_featured);
    }
}
