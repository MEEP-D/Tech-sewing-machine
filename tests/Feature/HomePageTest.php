<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpec;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_successfully(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('TechSewing');
    }

    public function test_home_page_displays_featured_products(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $category = Category::create([
            'name' => 'May May Lap Trinh',
            'slug' => 'may-may-lap-trinh',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'name' => 'May test noi bat',
            'slug' => 'may-test-noi-bat',
            'price' => '10.000.000',
            'category_id' => $category->id,
            'status' => 'published',
            'is_featured' => true,
            'is_new' => false,
            'sort_order' => 1,
            'view_count' => 0,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('May test noi bat');
    }

    public function test_home_page_renders_configured_home_blocks_and_full_product_card_data(): void
    {
        Setting::updateOrCreate(['key' => 'home_service_title'], ['value' => 'Dich vu lap dat tan noi', 'group' => 'homepage']);
        Setting::updateOrCreate(['key' => 'home_service_description'], ['value' => 'Bao tri va huong dan van hanh day du.', 'group' => 'homepage']);
        Setting::updateOrCreate(['key' => 'home_service_primary_cta'], ['value' => 'Dat lich', 'group' => 'homepage']);
        Setting::updateOrCreate(['key' => 'home_service_secondary_cta'], ['value' => 'Xem chinh sach', 'group' => 'homepage']);
        Setting::updateOrCreate(['key' => 'newsletter_signup_title'], ['value' => 'Nhan tin uu dai moi', 'group' => 'homepage']);
        Setting::updateOrCreate(['key' => 'newsletter_signup_description'], ['value' => 'Cap nhat uu dai va dich vu moi nhat.', 'group' => 'homepage']);
        Setting::updateOrCreate(['key' => 'newsletter_signup_button_text'], ['value' => 'Nhan tin', 'group' => 'homepage']);
        Setting::updateOrCreate(['key' => 'newsletter_signup_note'], ['value' => 'Thong tin cua ban duoc bao mat.', 'group' => 'homepage']);

        $category = Category::create([
            'name' => 'May may lap trinh',
            'slug' => 'may-may-lap-trinh',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'name' => 'May hien thi day du',
            'slug' => 'may-hien-thi-day-du',
            'code' => 'FULL-01',
            'price' => '12000000',
            'short_description' => "Tinh nang mot\nTinh nang hai\nTinh nang ba",
            'specifications' => [
                ['key' => 'Toc do', 'value' => '5000rpm'],
                ['key' => 'Kho may', 'value' => '40cm'],
                ['key' => 'Dien ap', 'value' => '220V'],
            ],
            'category_id' => $category->id,
            'status' => 'published',
            'is_featured' => true,
            'is_new' => true,
            'show_in_banner_switcher' => true,
            'sort_order' => 1,
            'view_count' => 0,
        ]);

        ProductSpec::create([
            'product_id' => $product->id,
            'key' => ' ',
            'value' => ' ',
            'sort_order' => 0,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('service-banner-content');
        $response->assertSee('Dich vu lap dat tan noi');
        $response->assertSee('newsletter-signup-inner');
        $response->assertSee('Nhan tin uu dai moi');
        $response->assertSee('banner-specs-row');
        $response->assertSee('Toc do');
        $response->assertSee('Kho may');
        $response->assertSee('Dien ap');
        $response->assertSee('Tinh nang mot');
        $response->assertSee('Tinh nang hai');
        $response->assertSee('Tinh nang ba');
    }

}
