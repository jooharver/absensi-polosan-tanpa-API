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
            PermissionsSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
            AbsenSeeder::class,
            SetThrSeeder::class,
            IzinSeeder::class,

        ]);

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin')
        ]);
        $role = 'Super Admin';
        $user->assignRole($role);
        // $permission = Permission::create(['name' => 'edit articles']);
    }
}
