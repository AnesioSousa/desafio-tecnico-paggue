<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // guard_name must match your config/auth.php “api” guard
        $guard = 'api';

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        Role::firstOrCreate(['name' => 'producer', 'guard_name' => $guard]);
        Role::firstOrCreate(['name' => 'client', 'guard_name' => $guard]);
    }
}
