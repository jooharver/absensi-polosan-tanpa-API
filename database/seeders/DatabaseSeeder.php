<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PosisiSeeder::class,
            KaryawanSeeder::class,
            UserSeeder::class,
            SetThrSeeder::class,
            AdminActivityLogSeeder::class,
            PermissionsSeeder::class,
            RolesSeeder::class,
            OfficeSeeder::class,

            // RoleSeeder::class,
            // ModelHasRoleSeeder::class,
            // PermissionSeeder::class,
            // RoleHasPermissionSeeder::class,

        ]);

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin')
        ]);
        $role = Role::create(['name' => 'Admin']);
        $user->assignRole($role);
        // $permission = Permission::create(['name' => 'edit articles']);
    }
}
