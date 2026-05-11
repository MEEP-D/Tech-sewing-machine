<?php

namespace Tests\Unit;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_value_returns_default_when_missing(): void
    {
        $this->assertSame('fallback', Setting::getValue('missing', 'fallback'));
    }

    public function test_get_value_decodes_json_values(): void
    {
        Setting::create([
            'key' => 'homepage_sections',
            'value' => json_encode(['hero', 'cta']),
            'group' => 'homepage',
        ]);

        $this->assertSame(['hero', 'cta'], Setting::getValue('homepage_sections'));
    }
}
