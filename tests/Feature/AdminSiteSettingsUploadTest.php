<?php

namespace Tests\Feature;

use App\Filament\Pages\SiteSettings;
use App\Filament\Pages\SeoSettings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSiteSettingsUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_logo_settings_can_be_saved_as_string_paths(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($user)
            ->test(SiteSettings::class)
            ->set('data.site_logo', 'site/logo.png')
            ->set('data.site_logo_dark', 'site/logo-dark.png')
            ->set('data.site_logo_mobile', 'site/logo-mobile.png')
            ->set('data.site_favicon', 'site/favicon.png')
            ->set('data.home_hero_image', 'site/hero.png')
            ->call('save');

        $this->assertSame('site/logo.png', Setting::getValue('site_logo'));
        $this->assertSame('site/logo-dark.png', Setting::getValue('site_logo_dark'));
        $this->assertSame('site/logo-mobile.png', Setting::getValue('site_logo_mobile'));
        $this->assertSame('site/favicon.png', Setting::getValue('site_favicon'));
        $this->assertSame('site/hero.png', Setting::getValue('home_hero_image'));
    }

    public function test_site_settings_can_save_tab_logo_and_frontend_renders_favicon(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($user)
            ->test(SiteSettings::class)
            ->set('data.site_favicon', 'site/tab-logo.png')
            ->call('save');

        $this->assertSame('site/tab-logo.png', Setting::getValue('site_favicon'));

        $this->get('/')
            ->assertOk()
            ->assertSee('/storage/site/tab-logo.png', false);
    }

    public function test_seo_settings_can_save_default_og_image_path(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($user)
            ->test(SeoSettings::class)
            ->set('data.seo_default_og_image_upload', ['site/default-og.png'])
            ->call('save');

        $this->assertSame('site/default-og.png', Setting::getValue('seo_default_og_image'));
    }
}
