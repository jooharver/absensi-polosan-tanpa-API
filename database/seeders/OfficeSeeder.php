<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Office::create([
            'name' => 'Politeknik Negeri Malang',
            'latitude' => -7.9469964,
            'longitude' => 112.6164256,
            'radius' => 300,
        ]);
    }
}
