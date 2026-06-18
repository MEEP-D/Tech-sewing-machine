<?php

namespace Tests\Feature;

use App\Filament\Pages\SiteSettings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_site_settings_page(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get('/admin/site-settings')
            ->assertStatus(200);
    }

    public function test_non_admin_cannot_access_site_settings_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/site-settings')
            ->assertStatus(403);
    }

    public function test_site_settings_save_persists_branding_values(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $component = Livewire::actingAs($user)
            ->test(SiteSettings::class)
            ->set('data.site_title', 'Tech Sewing')
            ->set('data.site_description', 'Máy may công nghiệp')
            ->set('data.site_logo_type', 'image')
            ->set('data.site_logo_height', 48)
            ->set('data.site_logo_width', 180);

        $component->call('save');

        $this->assertSame('Tech Sewing', Setting::getValue('site_title'));
        $this->assertSame('Máy may công nghiệp', Setting::getValue('site_description'));
        $this->assertSame('image', Setting::getValue('site_logo_type'));
        $this->assertSame(48, Setting::getValue('site_logo_height'));
        $this->assertSame(180, Setting::getValue('site_logo_width'));
    }

    public function test_site_settings_load_saved_values_on_mount(): void
    {
        Setting::updateOrCreate(['key' => 'site_title'], ['value' => 'Saved Title', 'group' => 'branding']);
        Setting::updateOrCreate(['key' => 'site_logo_type'], ['value' => 'text', 'group' => 'branding']);
        Setting::updateOrCreate(['key' => 'seo_default_title'], ['value' => 'SEO Saved', 'group' => 'seo']);

        $user = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($user)
            ->test(SiteSettings::class)
            ->assertSet('data.site_title', 'Saved Title')
            ->assertSet('data.site_logo_type', 'text');
    }
}
