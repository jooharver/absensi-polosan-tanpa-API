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
            'email' => 'ela@sayangku.com',
            'password' => Hash::make('ela'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);

        User::create([
            'name' => 'Joo',
            'email' => 'j@email.com',
            'password' => Hash::make('joo'), // Menggunakan Hash untuk menyimpan password
            'remember_token' => Str::random(60), // Menggunakan Str untuk generate remember_token
        ]);
    }
}
