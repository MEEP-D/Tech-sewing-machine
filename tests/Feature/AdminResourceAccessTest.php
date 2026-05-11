<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(200);
    }

    public function test_non_admin_is_blocked_from_admin_routes(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(403);
    }
}
