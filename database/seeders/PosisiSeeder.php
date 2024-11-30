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
            'hari_kerja_per_minggu' => '5',
            'jam_masuk' => '07:00',
            'jam_keluar' => '15:00',
            'grace_period' => '00:15:00'
        ]);

        Posisi::create([
            'posisi' => 'Admin',
            'jam_kerja_per_hari' => '8',
            'hari_kerja_per_minggu' => '6',
            'jam_masuk' => '06:00',
            'jam_keluar' => '14:00',
            'grace_period' => '00:15:00'
        ]);

        Posisi::create([
            'posisi' => 'Staff IT',
            'jam_kerja_per_hari' => '6',
            'hari_kerja_per_minggu' => '5',
            'jam_masuk' => '08:00',
            'jam_keluar' => '14:00',
            'grace_period' => '00:15:00'
        ]);
    }
}
