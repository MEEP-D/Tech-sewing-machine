<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SeoMetaSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedProducts();
        $this->seedPosts();
        $this->seedPages();
        $this->seedMenus();
        $this->seedSections();
        $this->seedBanners();
    }

    protected function seedCategories(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Category> $categories */
        $categories = Category::query()->get();

        foreach ($categories as $category) {
            $title = "{$category->name} | Tech Sewing Machine";
            $description = $category->description ?: "Danh mục {$category->name} tại Tech Sewing Machine.";

            $category->seoMeta()->updateOrCreate([], [
                'meta_title' => $this->limit($title, 70),
                'meta_description' => $this->limit($description, 165),
                'og_title' => $category->name,
                'og_description' => $this->limit($description, 200),
                'og_image' => $category->image ?: 'images/og-default.jpg',
                'canonical_url' => $category->url,
                'focus_keyword' => $category->name,
                'schema_markup' => [],
                'no_index' => false,
                'no_follow' => false,
            ]);
        }
    }

    protected function seedProducts(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Product> $products */
        $products = Product::query()->get();

        foreach ($products as $product) {
            $title = "{$product->name} | Tech Sewing Machine";
            $description = $product->short_description ?: "Thông tin sản phẩm {$product->name}, tư vấn báo giá và demo tại Tech Sewing Machine.";

            $product->seoMeta()->updateOrCreate([], [
                'meta_title' => $this->limit($title, 70),
                'meta_description' => $this->limit($description, 165),
                'og_title' => $product->name,
                'og_description' => $this->limit($description, 200),
                'og_image' => $product->thumbnail ?: 'images/og-default.jpg',
                'canonical_url' => $product->url,
                'focus_keyword' => $product->sku ?: $product->name,
                'schema_markup' => [],
                'no_index' => false,
                'no_follow' => false,
            ]);
        }
    }

    protected function seedPosts(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Post> $posts */
        $posts = Post::query()->get();

        foreach ($posts as $post) {
            $title = "{$post->title} | Tech Sewing Machine";
            $description = $post->excerpt ?: "Tin tức và hướng dẫn kỹ thuật từ Tech Sewing Machine.";

            $post->seoMeta()->updateOrCreate([], [
                'meta_title' => $this->limit($title, 70),
                'meta_description' => $this->limit($description, 165),
                'og_title' => $post->title,
                'og_description' => $this->limit($description, 200),
                'og_image' => $post->thumbnail ?: 'images/og-default.jpg',
                'canonical_url' => $post->url,
                'focus_keyword' => $post->category?->name ?: 'tin tức',
                'schema_markup' => [],
                'no_index' => false,
                'no_follow' => false,
            ]);
        }
    }

    protected function seedPages(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Page> $pages */
        $pages = Page::query()->get();

        foreach ($pages as $page) {
            $title = "{$page->title} | Tech Sewing Machine";
            $description = $page->excerpt ?: "Thông tin {$page->title} tại Tech Sewing Machine.";

            $page->seoMeta()->updateOrCreate([], [
                'meta_title' => $this->limit($title, 70),
                'meta_description' => $this->limit($description, 165),
                'og_title' => $page->title,
                'og_description' => $this->limit($description, 200),
                'og_image' => $page->image ?: 'images/og-default.jpg',
                'canonical_url' => url("/trang/{$page->slug}"),
                'focus_keyword' => $page->title,
                'schema_markup' => [],
                'no_index' => false,
                'no_follow' => false,
            ]);
        }
    }

    protected function seedMenus(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Menu> $menus */
        $menus = Menu::query()->get();

        foreach ($menus as $menu) {
            // Menu entries are usually navigational; keep SEO minimal and non-intrusive.
            $menu->seoMeta()->updateOrCreate([], [
                'meta_title' => $menu->label ? $this->limit("{$menu->label} | Tech Sewing Machine", 70) : null,
                'meta_description' => null,
                'og_title' => $menu->label ?: null,
                'og_description' => null,
                'og_image' => null,
                'canonical_url' => $menu->url ? url($menu->url) : null,
                'focus_keyword' => null,
                'schema_markup' => [],
                'no_index' => false,
                'no_follow' => false,
            ]);
        }
    }

    protected function seedSections(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Section> $sections */
        $sections = Section::query()->get();

        foreach ($sections as $section) {
            $title = $section->title ? "{$section->title} | Tech Sewing Machine" : null;
            $description = $section->content ?: null;

            $section->seoMeta()->updateOrCreate([], [
                'meta_title' => $title ? $this->limit($title, 70) : null,
                'meta_description' => $description ? $this->limit($description, 165) : null,
                'og_title' => $section->title ?: null,
                'og_description' => $description ? $this->limit($description, 200) : null,
                'og_image' => $section->image ?: null,
                'canonical_url' => null,
                'focus_keyword' => $section->key,
                'schema_markup' => [],
                'no_index' => true,
                'no_follow' => true,
            ]);
        }
    }

    protected function seedBanners(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Banner> $banners */
        $banners = Banner::query()->get();

        foreach ($banners as $banner) {
            $title = $banner->title ? "{$banner->title} | Tech Sewing Machine" : null;
            $description = $banner->subtitle ?: null;

            $banner->seoMeta()->updateOrCreate([], [
                'meta_title' => $title ? $this->limit($title, 70) : null,
                'meta_description' => $description ? $this->limit($description, 165) : null,
                'og_title' => $banner->title ?: null,
                'og_description' => $description ? $this->limit($description, 200) : null,
                'og_image' => $banner->image ?: null,
                'canonical_url' => $banner->link ? url($banner->link) : null,
                'focus_keyword' => $banner->key,
                'schema_markup' => [],
                'no_index' => true,
                'no_follow' => true,
            ]);
        }
    }

    protected function limit(string $text, int $max): string
    {
        $text = trim(preg_replace('/\s+/', ' ', $text) ?? '');
        if (mb_strlen($text) <= $max) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $max - 1)) . '…';
    }
}
