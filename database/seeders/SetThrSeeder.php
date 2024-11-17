<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SetThr;

class SetThrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SetThr::create([
            'posisi_id' => '1',
            'besaran_thr' => '4000000',
        ]);

        SetThr::create([
            'posisi_id' => '2',
            'besaran_thr' => '2000000',
        ]);

        SetThr::create([
            'posisi_id' => '3',
            'besaran_thr' => '2500000',
        ]);
    }
}
