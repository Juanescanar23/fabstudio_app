<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTwoFactorBridgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_exposes_existing_two_factor_secret_to_filament_mfa(): void
    {
        $user = User::factory()->create([
            'two_factor_secret' => encrypt('existing-secret'),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->assertSame('existing-secret', $user->getAppAuthenticationSecret());

        $user->saveAppAuthenticationSecret('rotated-secret');

        $this->assertSame('rotated-secret', $user->fresh()->getAppAuthenticationSecret());
        $this->assertNotNull($user->fresh()->two_factor_confirmed_at);
    }

    public function test_user_can_clear_filament_mfa_secret(): void
    {
        $user = User::factory()->create([
            'two_factor_secret' => encrypt('existing-secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code'])),
            'two_factor_confirmed_at' => now(),
        ]);

        $user->saveAppAuthenticationSecret(null);

        $this->assertNull($user->fresh()->two_factor_secret);
        $this->assertNull($user->fresh()->two_factor_recovery_codes);
        $this->assertNull($user->fresh()->two_factor_confirmed_at);
    }
}
