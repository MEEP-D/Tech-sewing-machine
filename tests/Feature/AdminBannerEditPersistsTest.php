<?php

namespace Tests\Feature;

use App\Filament\Resources\Banners\Pages\EditBanner;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminBannerEditPersistsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_edit_banner_and_persist_changes(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $banner = Banner::create([
            'key' => 'test-banner',
            'title' => 'Old title',
            'subtitle' => 'Old subtitle',
            'image' => 'banners/old.jpg',
            'link' => '/lien-he',
            'button_text' => 'Old CTA',
            'size_label' => 'Desktop',
            'recommended_size' => '1920x900',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $uploaded = UploadedFile::fake()->image('new.jpg', 1600, 700);

        Livewire::actingAs($admin)
            ->test(EditBanner::class, ['record' => $banner->getKey()])
            ->fillForm([
                'title' => 'New title',
                'subtitle' => 'New subtitle',
                'image' => [$uploaded],
                'link' => '/san-pham',
                'button_text' => 'New CTA',
                'size_label' => 'Hero',
                'recommended_size' => '1600x700',
                'is_active' => false,
                'sort_order' => 9,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $banner->refresh();

        $this->assertSame('New title', $banner->title);
        $this->assertSame('New subtitle', $banner->subtitle);
        $this->assertSame('banners/new.jpg', $banner->image);
        $this->assertSame('/san-pham', $banner->link);
        $this->assertSame('New CTA', $banner->button_text);
        $this->assertSame('Hero', $banner->size_label);
        $this->assertSame('1600x700', $banner->recommended_size);
        $this->assertFalse($banner->is_active);
        $this->assertSame(9, $banner->sort_order);
    }
}
