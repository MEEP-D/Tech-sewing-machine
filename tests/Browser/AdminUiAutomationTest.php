<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminUiAutomationTest extends DuskTestCase
{
    use DatabaseMigrations;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin-ui-test@techsewing.vn',
            'is_admin' => true,
        ]);

        Category::create([
            'name' => 'UI Product Category',
            'slug' => 'ui-product-category',
            'type' => 'product',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'UI News Category',
            'slug' => 'ui-news-category',
            'type' => 'news',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_admin_create_forms_render_all_expected_inputs(): void
    {
        $forms = [
            '/admin/categories/create' => [
                'data.name',
                'data.slug',
                'data.type',
                'data.parent_id',
                'data.description',
                'data.image',
                'data.is_active',
                'data.sort_order',
                'data.seoMeta.meta_title',
                'data.seoMeta.meta_description',
                'data.seoMeta.focus_keyword',
                'data.seoMeta.og_title',
                'data.seoMeta.og_image',
                'data.seoMeta.og_description',
                'data.seoMeta.canonical_url',
                'data.seoMeta.no_index',
                'data.seoMeta.no_follow',
            ],
            '/admin/menus/create' => [
                'data.location',
                'data.label',
                'data.url',
                'data.route_name',
                'data.target',
                'data.parent_id',
                'data.sort_order',
                'data.icon',
                'data.css_class',
                'data.meta_config',
                'data.is_active',
            ],
            '/admin/pages/create' => [
                'data.title',
                'data.slug',
                'data.layout_mode',
                'data.cache_ttl',
                'data.cache_enabled',
                'data.is_active',
                'data.excerpt',
                'data.image',
                'data.content',
                'data.style_config',
                'data.seoMeta.meta_title',
                'data.seoMeta.meta_description',
                'data.seoMeta.focus_keyword',
                'data.seoMeta.og_title',
                'data.seoMeta.og_image',
                'data.seoMeta.og_description',
                'data.seoMeta.canonical_url',
                'data.seoMeta.no_index',
                'data.seoMeta.no_follow',
            ],
            '/admin/partners/create' => [
                'data.name',
                'data.logo',
                'data.url',
                'data.sort_order',
                'data.is_active',
            ],
            '/admin/posts/create' => [
                'data.title',
                'data.slug',
                'data.type',
                'data.status',
                'data.author_id',
                'data.category_id',
                'data.published_at',
                'data.excerpt',
                'data.content',
                'data.event_date',
                'data.event_location',
                'data.thumbnail',
                'data.is_featured',
                'data.seoMeta.meta_title',
                'data.seoMeta.meta_description',
                'data.seoMeta.focus_keyword',
                'data.seoMeta.og_title',
                'data.seoMeta.og_image',
                'data.seoMeta.og_description',
                'data.seoMeta.canonical_url',
                'data.seoMeta.no_index',
                'data.seoMeta.no_follow',
            ],
            '/admin/products/create' => [
                'data.name',
                'data.slug',
                'data.sku',
                'data.price',
                'data.status',
                'data.brand',
                'data.origin',
                'data.category_id',
                'data.short_description',
                'data.description',
                'data.thumbnail',
                'data.gallery',
                'data.specifications',
                'data.is_featured',
                'data.is_new',
                'data.sort_order',
                'data.seoMeta.meta_title',
                'data.seoMeta.meta_description',
                'data.seoMeta.focus_keyword',
                'data.seoMeta.og_title',
                'data.seoMeta.og_image',
                'data.seoMeta.og_description',
                'data.seoMeta.canonical_url',
                'data.seoMeta.no_index',
                'data.seoMeta.no_follow',
            ],
            '/admin/sections/create' => [
                'data.key',
                'data.title',
                'data.subtitle',
                'data.type',
                'data.content',
                'data.image',
                'data.sort_order',
                'data.is_active',
                'data.container_class',
                'data.bg_color',
                'data.text_color',
                'data.spacing_top',
                'data.spacing_bottom',
            ],
            '/admin/settings/create' => [
                'data.key',
                'data.label',
                'data.group',
                'data.type',
                'data.value',
            ],
        ];

        $this->browse(function (Browser $browser) use ($forms) {
            $browser->loginAs($this->admin);

            foreach ($forms as $path => $fields) {
                $browser->visit($path)
                    ->waitFor('form', 10);

                $this->assertFilamentFieldsPresent($browser, $fields, $path);
            }
        });
    }

    public function test_admin_settings_pages_render_all_expected_inputs(): void
    {
        $pages = [
            '/admin/site-settings' => [
                'data.site_title',
                'data.site_description',
                'data.site_logo_type',
                'data.site_logo_height',
                'data.site_logo_width',
                'data.site_logo_upload',
                'data.site_logo_dark_upload',
                'data.site_logo_mobile_upload',
                'data.site_favicon_upload',
                'data.home_hero_image_upload',
                'data.seo_default_title',
                'data.seo_default_canonical',
                'data.seo_default_og_image',
                'data.seo_organization_name',
                'data.seo_organization_url',
                'data.seo_robots_default',
                'data.seo_default_description',
                'data.seo_description',
            ],
            '/admin/seo-settings' => [
                'data.seo_default_title',
                'data.seo_default_canonical',
                'data.seo_default_og_image',
                'data.seo_organization_name',
                'data.seo_organization_url',
                'data.seo_robots_default',
                'data.seo_enable_schema',
                'data.seo_enable_og',
                'data.seo_default_description',
            ],
        ];

        $this->browse(function (Browser $browser) use ($pages) {
            $browser->loginAs($this->admin);

            foreach ($pages as $path => $fields) {
                $browser->visit($path)
                    ->waitFor('form', 10);

                $this->assertFilamentFieldsPresent($browser, $fields, $path);
            }
        });
    }

    public function test_admin_tag_modal_renders_all_expected_inputs(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/tags')
                ->waitFor('table', 10);

            $opened = $browser->script('
                const actions = [...document.querySelectorAll("button, a")];
                const createAction = actions.find((element) => {
                    const text = (element.textContent || "").toLowerCase();
                    const wireClick = element.getAttribute("wire:click") || "";

                    return text.includes("create")
                        || text.includes("new")
                        || text.includes("add")
                        || text.includes("thêm")
                        || wireClick.includes("create");
                });

                if (! createAction) {
                    return false;
                }

                createAction.click();

                return true;
            ')[0] ?? false;

            $this->assertTrue($opened, 'The create action was not found on /admin/tags.');

            $browser->waitFor('[role="dialog"]', 10);

            $this->assertFilamentFieldsPresent($browser, [
                'mountedActionsData.0.name',
                'mountedActionsData.0.slug',
                'mountedActionsData.0.type',
            ], '/admin/tags create modal');
        });
    }

    public function test_required_admin_create_forms_block_empty_submit(): void
    {
        $paths = [
            '/admin/categories/create',
            '/admin/menus/create',
            '/admin/pages/create',
            '/admin/partners/create',
            '/admin/posts/create',
            '/admin/products/create',
            '/admin/sections/create',
            '/admin/settings/create',
        ];

        $this->browse(function (Browser $browser) use ($paths) {
            $browser->loginAs($this->admin);

            foreach ($paths as $path) {
                $browser->visit($path)
                    ->waitFor('form', 10)
                    ->press('Create')
                    ->pause(750)
                    ->assertPathIs($path);
            }
        });
    }

    public function test_admin_can_create_category_and_persist_to_db(): void
    {
        $name = 'Dusk Category '.time();
        $slug = 'dusk-category-'.time();

        $this->browse(function (Browser $browser) use ($name, $slug) {
            $browser->loginAs($this->admin)
                ->visit('/admin/categories/create')
                ->waitFor('form', 10);

            $this->setFieldValue($browser, 'data.name', $name);
            $this->setFieldValue($browser, 'data.slug', $slug);
            $this->setFieldValue($browser, 'data.type', 'product');
            $this->submitForm($browser);

            $browser->pause(1200)->assertPathIs('/admin/categories');
        });

        $this->assertDatabaseHas('categories', [
            'name' => $name,
            'slug' => $slug,
            'type' => 'product',
        ]);
    }

    public function test_admin_can_create_menu_and_persist_to_db(): void
    {
        $label = 'Dusk Menu '.time();

        $this->browse(function (Browser $browser) use ($label) {
            $browser->loginAs($this->admin)
                ->visit('/admin/menus/create')
                ->waitFor('form', 10);

            $this->setFieldValue($browser, 'data.location', 'header');
            $this->setFieldValue($browser, 'data.label', $label);
            $this->setFieldValue($browser, 'data.url', '/dusk-menu');
            $this->setFieldValue($browser, 'data.target', '_self');
            $this->submitForm($browser);

            $browser->pause(1200)->assertPathIs('/admin/menus');
        });

        $menu = Menu::where('label', $label)->first();

        $this->assertNotNull($menu);
        $this->assertSame('header', $menu->location);
        $this->assertSame('/dusk-menu', $menu->url);
    }

    public function test_admin_can_create_setting_and_persist_to_db(): void
    {
        $key = 'dusk_setting_'.time();
        $label = 'Dusk Setting '.time();
        $value = 'value_'.time();

        $this->browse(function (Browser $browser) use ($key, $label, $value) {
            $browser->loginAs($this->admin)
                ->visit('/admin/settings/create')
                ->waitFor('form', 10);

            $this->setFieldValue($browser, 'data.key', $key);
            $this->setFieldValue($browser, 'data.label', $label);
            $this->setFieldValue($browser, 'data.group', 'general');
            $this->setFieldValue($browser, 'data.type', 'text');
            $this->setFieldValue($browser, 'data.value', $value);
            $this->submitForm($browser);

            $browser->pause(1200)->assertPathIs('/admin/settings');
        });

        $setting = Setting::where('key', $key)->first();

        $this->assertNotNull($setting);
        $this->assertSame($label, $setting->label);
        $this->assertSame('general', $setting->group);
        $this->assertSame('text', $setting->type);
        $this->assertSame($value, $setting->value);
    }

    /**
     * @param  array<int, string>  $fields
     */
    private function assertFilamentFieldsPresent(Browser $browser, array $fields, string $path): void
    {
        $missing = $browser->script('
            const fields = arguments[0];

            const cssEscape = window.CSS && window.CSS.escape
                ? window.CSS.escape
                : (value) => value.replace(/["\\\\]/g, "\\\\$&");

            const bracketName = (path) => {
                const [root, ...parts] = path.split(".");

                return root + parts.map((part) => "[" + part + "]").join("");
            };

            const selectorsFor = (path) => {
                const bracket = bracketName(path);
                const last = path.split(".").pop();

                return [
                    `[name="${cssEscape(path)}"]`,
                    `[name="${cssEscape(bracket)}"]`,
                    `[wire\\\\:model="${cssEscape(path)}"]`,
                    `[wire\\\\:model\\\\.live="${cssEscape(path)}"]`,
                    `[wire\\\\:model\\\\.blur="${cssEscape(path)}"]`,
                    `[wire\\\\:model\\\\.defer="${cssEscape(path)}"]`,
                    `[x-model="${cssEscape(path)}"]`,
                    `input[type="file"][id*="${cssEscape(last)}"]`,
                    `[contenteditable="true"][wire\\\\:model="${cssEscape(path)}"]`,
                    `[id$="${cssEscape(last)}"]`,
                ];
            };

            return fields.filter((path) => {
                return ! selectorsFor(path).some((selector) => {
                    try {
                        return document.querySelector(selector) !== null;
                    } catch (error) {
                        return false;
                    }
                });
            });
        ', [$fields])[0] ?? [];

        $this->assertSame([], $missing, 'Missing admin UI fields on '.$path.': '.implode(', ', $missing));
    }

    private function setFieldValue(Browser $browser, string $path, string|int|bool $value): void
    {
        $browser->script('
            const path = arguments[0];
            const value = arguments[1];

            const bracketName = (inputPath) => {
                const [root, ...parts] = inputPath.split(".");
                return root + parts.map((part) => "[" + part + "]").join("");
            };

            const escaped = (input) => {
                return input.replace(/["\\\\]/g, "\\\\$&");
            };

            const selectors = [
                `[name="${escaped(path)}"]`,
                `[name="${escaped(bracketName(path))}"]`,
                `[wire\\\\:model="${escaped(path)}"]`,
                `[wire\\\\:model\\\\.live="${escaped(path)}"]`,
                `[wire\\\\:model\\\\.blur="${escaped(path)}"]`,
                `[wire\\\\:model\\\\.defer="${escaped(path)}"]`,
            ];

            const element = selectors.map((selector) => document.querySelector(selector)).find(Boolean);

            if (! element) {
                return;
            }

            if (element.type === "checkbox") {
                element.checked = Boolean(value);
            } else {
                element.value = value;
            }

            element.dispatchEvent(new Event("input", { bubbles: true }));
            element.dispatchEvent(new Event("change", { bubbles: true }));
            element.dispatchEvent(new Event("blur", { bubbles: true }));
        ', [$path, $value]);
    }

    private function submitForm(Browser $browser): void
    {
        $browser->script('
            const form = document.querySelector("form");
            if (! form) {
                return;
            }

            const button = form.querySelector("button[type=submit]");
            if (button) {
                button.click();
            }
        ');
    }
}
