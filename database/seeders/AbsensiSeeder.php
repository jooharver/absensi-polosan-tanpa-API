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
            'jam_masuk' => '08:00',
            'jam_keluar' => '16:00',
            'durasi' => '8',
            'status' => 'Hadir'
        ]);

        Absensi::create([
            'karyawan_id' => '1',
            'tanggal' => '2024-10-28',
            'jam_masuk' => '08:00',
            'jam_keluar' => '16:00',
            'durasi' => '8',
            'status' => 'Hadir'
        ]);

        Absensi::create([
            'karyawan_id' => '1',
            'tanggal' => '2024-10-29',
            'jam_masuk' => '08:00',
            'jam_keluar' => '16:00',
            'durasi' => '8',
            'status' => 'Hadir'
        ]);

        Absensi::create([
            'karyawan_id' => '1',
            'tanggal' => '2024-10-30',
            'jam_masuk' => '08:00',
            'jam_keluar' => '16:00',
            'durasi' => '8',
            'status' => 'Hadir'
        ]);

        Absensi::create([
            'karyawan_id' => '1',
            'tanggal' => '2024-10-31',
            'jam_masuk' => '08:00',
            'jam_keluar' => '16:00',
            'durasi' => '8',
            'status' => 'Hadir'
        ]);
    }
}
