<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Product;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FrontendUiTest extends DuskTestCase
{
    public function test_homepage_and_contact_ui_render(): void
    {
        $category = Category::firstOrCreate([
            'slug' => 'may-cong-nghiep'
        ], [
            'name' => 'Máy công nghiệp',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Product::firstOrCreate([
            'slug' => 'may-noi-bat'
        ], [
            'name' => 'Máy nổi bật',
            'price' => '10000000',
            'category_id' => $category->id,
            'status' => 'published',
            'is_featured' => true,
            'is_new' => false,
            'sort_order' => 1,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000')
                ->pause(3000) // 👈 cho nó load thật
        
                ->screenshot('debug-home') // 👈 xem thực tế nó load gì
        
                ->assertSee('SẢN PHẨM')
        
                ->visit('http://127.0.0.1:8000/lien-he')
                ->pause(2000)
        
                ->assertPresent('form')
                ->assertPresent('button[type=submit]');
        });
    }
}