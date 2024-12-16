<?php

namespace Database\Seeders;

use App\Models\Izin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Izin::create([
            'karyawan_id' => '2',
            'jenis' => 'sakit',
            'start' => '2024-12-01',
            'end' => '2024-12-01',
            'keterangan' => 'Demam',
        ]);

        Izin::create([
            'karyawan_id' => '3',
            'jenis' => 'izin',
            'start' => '2024-12-01',
            'end' => '2024-12-03',
            'keterangan' => 'Menikah',
        ]);


        Izin::create([
            'karyawan_id' => '4',
            'jenis' => 'izin',
            'start' => '2024-12-01',
            'end' => '2024-12-01',
            'keterangan' => 'Sunatan',
        ]);
    }
}
