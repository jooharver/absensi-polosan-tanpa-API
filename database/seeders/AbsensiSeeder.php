<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absensi;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Absensi::create([
            'karyawan_id' => '1',
            'tanggal' => '2024-10-27',
            'absen_masuk' => '08:00',
            'absen_keluar' => '16:00',
            'hadir' => '08:00',

        ]);

        Absensi::create([
            'karyawan_id' => '1',
            'tanggal' => '2024-10-28',
            'absen_masuk' => '08:00',
            'absen_keluar' => '16:00',
            'hadir' => '08:00',

        ]);

        Absensi::create([
            'karyawan_id' => '1',
            'tanggal' => '2024-10-29',
            'absen_masuk' => '08:00',
            'absen_keluar' => '16:00',
            'hadir' => '08:00',

        ]);

        Absensi::create([
            'karyawan_id' => '1',
            'tanggal' => '2024-10-30',
            'absen_masuk' => '08:00',
            'absen_keluar' => '16:00',
            'hadir' => '08:00',

        ]);

        Absensi::create([
            'karyawan_id' => '1',
            'tanggal' => '2024-10-31',
            'absen_masuk' => '08:00',
            'absen_keluar' => '16:00',
            'hadir' => '08:00',

        ]);
    }
}
