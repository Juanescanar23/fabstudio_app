<?php

namespace Tests\Feature;

use App\Models\QuoteTemplate;
use Database\Seeders\RolesAndAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionReadinessCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_readiness_check_passes_with_minimum_operational_data(): void
    {
        $this->seed(RolesAndAdminSeeder::class);
        QuoteTemplate::factory()->create(['status' => 'active']);

        $this->artisan('app:readiness-check')
            ->expectsOutputToContain('Readiness check aprobado')
            ->assertSuccessful();
    }

    public function test_readiness_check_fails_without_admin_user(): void
    {
        QuoteTemplate::factory()->create(['status' => 'active']);

        $this->artisan('app:readiness-check')
            ->expectsOutputToContain('Readiness check no aprobado.')
            ->assertFailed();
    }
}
