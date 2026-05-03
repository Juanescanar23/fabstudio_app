<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_filament_admin_panel(): void
    {
        $this->seed(RolesAndAdminSeeder::class);

        $admin = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))
            ->firstOrFail();
        $admin->forceFill([
            'two_factor_secret' => encrypt('test-secret'),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_client_user_cannot_access_filament_admin_panel(): void
    {
        $this->seed(RolesAndAdminSeeder::class);

        $client = User::factory()->create();
        $client->assignRole('client');

        $this->actingAs($client)
            ->get('/admin')
            ->assertForbidden();
    }
}
