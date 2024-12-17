<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Tambahkan ini
use App\Models\User; // Pastikan model User sudah ada

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $role1 = 'Super Admin';
        $role2 = 'Admin';




        $user1 = User::create([
            'name' => 'Joo',
            'email' => 'j@email.com',
            'password' => Hash::make('joo'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
        $user1->assignRole($role1);

        $user2 = User::create([
            'name' => 'Daffa',
            'email' => 'd@email.com',
            'password' => Hash::make('daffa'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
        $user2->assignRole($role1);

        $user3 = User::create([
            'name' => 'Rofiq',
            'email' => 'r@email.com',
            'password' => Hash::make('ROF'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
        $user3->assignRole($role1);

        $user4 = User::create([
            'name' => 'Budie Arie S.',
            'email' => 'b@email.com',
            'password' => Hash::make('budie'), // Menggunakan Hash untuk menyimpan password
            'karyawan_id' => '1',
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
        $user4->assignRole($role1);

        $user5 = User::create([
            'name' => 'Karina',
            'email' => 'k@email.com',
            'password' => Hash::make('karina'), // Menggunakan Hash untuk menyimpan password
            'karyawan_id' => '3',
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
        $user5->assignRole($role2);

        $user6 = User::create([
            'name' => 'Bjorka',
            'email' => 'bj@email.com',
            'password' => Hash::make('bjorka'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
        $user6->assignRole($role2);

        $user7 = User::create([
            'name' => 'Ayu',
            'email' => 'a@email.com',
            'password' => Hash::make('ayu'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
        $user7->assignRole($role2);

        $user8 = User::create([
            'name' => 'P. Didy',
            'email' => 'p@email.com',
            'password' => Hash::make('ahhh'), // Menggunakan Hash untuk menyimpan password
            'karyawan_id' => '5',
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
        $user8->assignRole($role2);

    }
}
