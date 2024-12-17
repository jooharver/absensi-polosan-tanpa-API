<?php

namespace App\Filament\Admin\Resources\KaryawanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class KaryawanStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Total semua karyawan
        $totalKaryawan = DB::table('karyawans')->count();

        // Total karyawan dengan id_posisi = 2
        $karyawanPosisi2 = DB::table('karyawans')
            ->where('posisi_id', 2)
            ->count();

        // Total karyawan dengan id_posisi = 3
        $karyawanPosisi3 = DB::table('karyawans')
            ->where('posisi_id', 3)
            ->count();

        return [
            Stat::make('Total Karyawan', $totalKaryawan)
                ->description('Jumlah seluruh karyawan'),

            Stat::make('Total Admin', $karyawanPosisi2)
                ->description('Jumlah karyawan dengan posisi Admin'),

            Stat::make('Total Staff IT', $karyawanPosisi3)
                ->description('Jumlah karyawan dengan posisi Staff IT'),
        ];
    }
}
