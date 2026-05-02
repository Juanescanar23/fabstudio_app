<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        collect([
            'super_admin',
            'admin',
            'studio_member',
            'client',
        ])->each(fn (string $role) => Role::firstOrCreate(['name' => $role]));

        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@fabstudio.local')],
            [
                'name' => env('ADMIN_NAME', 'FAB STUDIO Admin'),
                'password' => env('ADMIN_PASSWORD', 'password'),
            ],
        );

        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}
