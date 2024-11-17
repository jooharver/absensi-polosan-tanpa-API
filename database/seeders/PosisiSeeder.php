<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Posisi;
use Illuminate\Database\Seeder;

class PosisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Posisi::create([
            'posisi' => 'Manager',
            'jam_kerja_per_hari' => '8',
            'hari_kerja_per_minggu' => '5'
        ]);

        Posisi::create([
            'posisi' => 'Admin',
            'jam_kerja_per_hari' => '8',
            'hari_kerja_per_minggu' => '6'
        ]);

        Posisi::create([
            'posisi' => 'Staff IT',
            'jam_kerja_per_hari' => '6',
            'hari_kerja_per_minggu' => '5'
        ]);
    }
}
