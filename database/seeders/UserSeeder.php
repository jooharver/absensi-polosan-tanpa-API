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
        User::create([
            'name' => 'Ela',
            'email' => 'ela@email.com',
            'password' => Hash::make('ela'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);

        User::create([
            'name' => 'Joo',
            'email' => 'j@email.com',
            'password' => Hash::make('joo'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);

        User::create([
            'name' => 'Daffa',
            'email' => 'd@email.com',
            'password' => Hash::make('daffa'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);

        User::create([
            'name' => 'Rofiq',
            'email' => 'r@email.com',
            'password' => Hash::make('ROF'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);

        User::create([
            'name' => 'Budie Arie Setiadi',
            'email' => 'b@email.com',
            'password' => Hash::make('budie'), // Menggunakan Hash untuk menyimpan password
            'karyawan_id' => '1',
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);

        User::create([
            'name' => 'Karina',
            'email' => 'k@email.com',
            'password' => Hash::make('karina'), // Menggunakan Hash untuk menyimpan password
            'karyawan_id' => '3',
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);

        User::create([
            'name' => 'Bjorka',
            'email' => 'bj@email.com',
            'password' => Hash::make('bjorka'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);

        User::create([
            'name' => 'Ayu',
            'email' => 'a@email.com',
            'password' => Hash::make('ayu'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);

        User::create([
            'name' => 'P. Didy',
            'email' => 'p@email.com',
            'password' => Hash::make('ahhh'), // Menggunakan Hash untuk menyimpan password
            'karyawan_id' => '5',
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
    }
}
