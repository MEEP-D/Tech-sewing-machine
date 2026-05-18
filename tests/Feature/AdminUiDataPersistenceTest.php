<?php

namespace Tests\Feature;

use App\Filament\Pages\SeoSettings;
use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Menus\Pages\CreateMenu;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Filament\Resources\Menus\Pages\ListMenus;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Partners\Pages\CreatePartner;
use App\Filament\Resources\Partners\Pages\EditPartner;
use App\Filament\Resources\Partners\Pages\ListPartners;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Sections\Pages\CreateSection;
use App\Filament\Resources\Sections\Pages\EditSection;
use App\Filament\Resources\Sections\Pages\ListSections;
use App\Filament\Resources\Settings\Pages\CreateSetting;
use App\Filament\Resources\Settings\Pages\EditSetting;
use App\Filament\Resources\Settings\Pages\ListSettings;
use App\Filament\Resources\TagResource\Pages\ManageTags;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUiDataPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_see_category_menu_page(): void
    {
        $admin = $this->createAdmin();

        Livewire::actingAs($admin)
            ->test(CreateCategory::class)
            ->fillForm([
                'name' => 'Danh muc UI Test',
                'slug' => 'danh-muc-ui-test',
                'type' => 'product',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', [
            'name' => 'Danh muc UI Test',
            'slug' => 'danh-muc-ui-test',
            'type' => 'product',
        ]);
        $this->actingAs($admin)->get('/admin/categories')->assertOk()->assertSee('Danh muc UI Test');

        Livewire::actingAs($admin)
            ->test(CreateMenu::class)
            ->fillForm([
                'location' => 'header',
                'label' => 'Menu UI Test',
                'url' => '/menu-ui-test',
                'target' => '_self',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('menus', [
            'label' => 'Menu UI Test',
            'location' => 'header',
            'url' => '/menu-ui-test',
        ]);
        $this->actingAs($admin)->get('/admin/menus')->assertOk()->assertSee('Menu UI Test');

        Livewire::actingAs($admin)
            ->test(CreatePage::class)
            ->fillForm([
                'title' => 'Trang UI Test',
                'slug' => 'trang-ui-test',
                'layout_mode' => 'content',
                'cache_ttl' => 3600,
                'cache_enabled' => true,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('pages', [
            'title' => 'Trang UI Test',
            'slug' => 'trang-ui-test',
        ]);
        $this->actingAs($admin)->get('/admin/pages')->assertOk()->assertSee('Trang UI Test');
    }

    public function test_admin_can_create_and_see_post_product_section_setting_tag(): void
    {
        $admin = $this->createAdmin();
        $author = User::factory()->create(['is_admin' => true]);
        $productCategory = Category::create([
            'name' => 'Product Root',
            'slug' => 'product-root',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $newsCategory = Category::create([
            'name' => 'News Root',
            'slug' => 'news-root',
            'type' => 'news',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(CreatePost::class)
            ->fillForm([
                'title' => 'Bai viet UI Test',
                'slug' => 'bai-viet-ui-test',
                'type' => 'news',
                'status' => 'published',
                'author_id' => $author->id,
                'category_id' => $newsCategory->id,
                'excerpt' => 'Tom tat',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('posts', [
            'title' => 'Bai viet UI Test',
            'slug' => 'bai-viet-ui-test',
            'status' => 'published',
        ]);
        $this->actingAs($admin)->get('/admin/posts')->assertOk()->assertSee('Bai viet UI Test');

        Livewire::actingAs($admin)
            ->test(CreateProduct::class)
            ->fillForm([
                'name' => 'San pham UI Test',
                'slug' => 'san-pham-ui-test',
                'status' => 'published',
                'category_id' => $productCategory->id,
                'price' => '12000000',
                'is_featured' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('products', [
            'name' => 'San pham UI Test',
            'slug' => 'san-pham-ui-test',
            'status' => 'published',
        ]);
        $this->actingAs($admin)->get('/admin/products')->assertOk()->assertSee('San pham UI Test');

        Livewire::actingAs($admin)
            ->test(CreateSection::class)
            ->fillForm([
                'key' => 'section-ui-test',
                'title' => 'Section UI Test',
                'type' => 'content',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('sections', [
            'key' => 'section-ui-test',
            'title' => 'Section UI Test',
            'type' => 'content',
        ]);
        $this->actingAs($admin)->get('/admin/sections')->assertOk()->assertSee('Section UI Test');

        Livewire::actingAs($admin)
            ->test(CreateSetting::class)
            ->fillForm([
                'key' => 'ui_test_key',
                'label' => 'UI Test Label',
                'group' => 'general',
                'type' => 'text',
                'value' => 'ui-test-value',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('settings', [
            'key' => 'ui_test_key',
            'label' => 'UI Test Label',
            'value' => 'ui-test-value',
        ]);
        $this->actingAs($admin)->get('/admin/settings')->assertOk()->assertSee('UI Test Label');

        Tag::create([
            'name' => 'Tag UI Test',
            'slug' => 'tag-ui-test',
            'type' => 'product',
        ]);
        $this->assertDatabaseHas('tags', ['slug' => 'tag-ui-test']);
        $this->actingAs($admin)->get('/admin/tags')->assertOk()->assertSee('Tag UI Test');
    }

    public function test_admin_site_and_seo_settings_save_and_load_on_ui(): void
    {
        $admin = $this->createAdmin();

        Livewire::actingAs($admin)
            ->test(SiteSettings::class)
            ->set('data.site_title', 'Tech Sewing UI Test')
            ->set('data.site_description', 'Site description test')
            ->set('data.seo_default_title', 'SEO Default UI Test')
            ->set('data.seo_default_description', 'SEO description test')
            ->call('save');

        Livewire::actingAs($admin)
            ->test(SeoSettings::class)
            ->set('data.seo_default_title', 'SEO Title Final')
            ->set('data.seo_default_description', 'SEO Desc Final')
            ->set('data.seo_enable_schema', true)
            ->set('data.seo_enable_og', false)
            ->call('save');

        $this->assertSame('Tech Sewing UI Test', Setting::getValue('site_title'));
        $this->assertSame('Site description test', Setting::getValue('site_description'));
        $this->assertSame('SEO Title Final', Setting::getValue('seo_default_title'));
        $this->assertSame('SEO Desc Final', Setting::getValue('seo_default_description'));
        $this->assertTrue((bool) Setting::getValue('seo_enable_schema'));
        $this->assertFalse((bool) Setting::getValue('seo_enable_og'));

        Livewire::actingAs($admin)
            ->test(SiteSettings::class)
            ->assertSet('data.site_title', 'Tech Sewing UI Test')
            ->assertSet('data.seo_default_title', 'SEO Title Final');

        Livewire::actingAs($admin)
            ->test(SeoSettings::class)
            ->assertSet('data.seo_default_title', 'SEO Title Final')
            ->assertSet('data.seo_default_description', 'SEO Desc Final');

        $this->actingAs($admin)->get('/admin/site-settings')->assertOk()->assertSee('Tech Sewing UI Test');
        $this->actingAs($admin)->get('/admin/seo-settings')->assertOk()->assertSee('SEO Title Final');
    }

    public function test_admin_can_edit_and_delete_all_resources(): void
    {
        $admin = $this->createAdmin();

        $category = Category::create([
            'name' => 'Category Old',
            'slug' => 'category-old',
            'type' => 'product',
            'is_active' => true,
        ]);
        Livewire::actingAs($admin)->test(EditCategory::class, ['record' => $category->getKey()])
            ->fillForm(['name' => 'Category New', 'slug' => 'category-new'])
            ->call('save')
            ->assertHasNoFormErrors();
        Livewire::actingAs($admin)->test(ListCategories::class)
            ->callTableBulkAction(DeleteBulkAction::class, [$category]);
        $this->assertSoftDeleted('categories', ['id' => $category->id]);

        $menu = Menu::create([
            'location' => 'header',
            'label' => 'Menu Old',
            'url' => '/menu-old',
            'target' => '_self',
            'is_active' => true,
        ]);
        Livewire::actingAs($admin)->test(EditMenu::class, ['record' => $menu->getKey()])
            ->fillForm(['label' => 'Menu New', 'url' => '/menu-new'])
            ->call('save')
            ->assertHasNoFormErrors();
        Livewire::actingAs($admin)->test(ListMenus::class)
            ->callTableAction(DeleteAction::class, $menu);
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);

        $page = Page::create([
            'title' => 'Page Old',
            'slug' => 'page-old',
            'layout_mode' => 'content',
            'cache_enabled' => true,
            'cache_ttl' => 3600,
            'is_active' => true,
        ]);
        Livewire::actingAs($admin)->test(EditPage::class, ['record' => $page->getKey()])
            ->fillForm(['title' => 'Page New', 'slug' => 'page-new'])
            ->call('save')
            ->assertHasNoFormErrors();
        Livewire::actingAs($admin)->test(ListPages::class)
            ->callTableBulkAction(DeleteBulkAction::class, [$page]);
        $this->assertSoftDeleted('pages', ['id' => $page->id]);

        $partner = Partner::create([
            'name' => 'Partner Old',
            'logo' => 'partners/old.png',
            'url' => 'https://old.example.com',
            'is_active' => true,
        ]);
        Livewire::actingAs($admin)->test(EditPartner::class, ['record' => $partner->getKey()])
            ->fillForm([
                'name' => 'Partner New',
                'url' => 'https://new.example.com',
                'logo' => ['partners/old.png'],
            ])
            ->call('save')
            ->assertHasNoFormErrors();
        Livewire::actingAs($admin)->test(ListPartners::class)
            ->callTableBulkAction(DeleteBulkAction::class, [$partner]);
        $this->assertDatabaseMissing('partners', ['id' => $partner->id]);

        $author = User::factory()->create(['is_admin' => true]);
        $newsCategory = Category::create([
            'name' => 'News Cat',
            'slug' => 'news-cat',
            'type' => 'news',
            'is_active' => true,
        ]);
        $post = Post::create([
            'title' => 'Post Old',
            'slug' => 'post-old',
            'type' => 'news',
            'status' => 'published',
            'author_id' => $author->id,
            'category_id' => $newsCategory->id,
        ]);
        Livewire::actingAs($admin)->test(EditPost::class, ['record' => $post->getKey()])
            ->fillForm(['title' => 'Post New', 'slug' => 'post-new'])
            ->call('save')
            ->assertHasNoFormErrors();
        Livewire::actingAs($admin)->test(ListPosts::class)
            ->callTableBulkAction(DeleteBulkAction::class, [$post]);
        $this->assertSoftDeleted('posts', ['id' => $post->id]);

        $productCategory = Category::create([
            'name' => 'Product Cat',
            'slug' => 'product-cat',
            'type' => 'product',
            'is_active' => true,
        ]);
        $product = Product::create([
            'name' => 'Product Old',
            'slug' => 'product-old',
            'status' => 'published',
            'category_id' => $productCategory->id,
            'price' => '1000000',
        ]);
        Livewire::actingAs($admin)->test(EditProduct::class, ['record' => $product->getKey()])
            ->fillForm(['name' => 'Product New', 'slug' => 'product-new'])
            ->call('save')
            ->assertHasNoFormErrors();
        Livewire::actingAs($admin)->test(ListProducts::class)
            ->callTableBulkAction(DeleteBulkAction::class, [$product]);
        $this->assertSoftDeleted('products', ['id' => $product->id]);

        $section = Section::create([
            'key' => 'section-old',
            'title' => 'Section Old',
            'type' => 'content',
            'is_active' => true,
        ]);
        Livewire::actingAs($admin)->test(EditSection::class, ['record' => $section->getKey()])
            ->fillForm(['title' => 'Section New'])
            ->call('save')
            ->assertHasNoFormErrors();
        Livewire::actingAs($admin)->test(ListSections::class)
            ->callTableAction(DeleteAction::class, $section);
        $this->assertDatabaseMissing('sections', ['id' => $section->id]);

        $setting = Setting::create([
            'key' => 'setting_old',
            'label' => 'Setting Old',
            'group' => 'general',
            'type' => 'text',
            'value' => 'old',
        ]);
        Livewire::actingAs($admin)->test(EditSetting::class, ['record' => $setting->getKey()])
            ->fillForm(['label' => 'Setting New', 'value' => 'new'])
            ->call('save')
            ->assertHasNoFormErrors();
        Livewire::actingAs($admin)->test(ListSettings::class)
            ->callTableAction(DeleteAction::class, $setting);
        $this->assertDatabaseMissing('settings', ['id' => $setting->id]);

        Livewire::actingAs($admin)->test(ManageTags::class)
            ->callAction(CreateAction::class, data: [
                'name' => 'Tag Old',
                'slug' => 'tag-old',
                'type' => 'product',
            ]);

        $tag = Tag::where('slug', 'tag-old')->firstOrFail();

        Livewire::actingAs($admin)->test(ManageTags::class)
            ->callTableAction(EditAction::class, $tag, data: [
                'name' => 'Tag New',
                'slug' => 'tag-new',
                'type' => 'product',
            ])
            ->callTableAction(DeleteAction::class, $tag->fresh());

        $this->assertSoftDeleted('tags', ['id' => $tag->id]);
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'name' => 'Admin UI Test',
            'email' => 'admin-ui-'.uniqid().'@example.com',
            'is_admin' => true,
        ]);
    }

}
