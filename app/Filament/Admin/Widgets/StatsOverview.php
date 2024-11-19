<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Karyawan; // Pastikan namespace model sesuai
use App\Models\Absensi; // Pastikan namespace model sesuai
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    // protected function getStats(): array
    // {
    //     // Hitung total karyawan
    //     // $totalKaryawan = Karyawan::count();

    //     // // Hitung total karyawan hadir hari ini (status 'Hadir')
    //     // // 

    //     // // Hitung total izin hari ini$totalHadir = Absensi::where('status', 'Hadir')
    //     // //     ->whereDate('created_at', now()->toDateString()) // Filter untuk hari ini
    //     // //     ->count(); (status 'Izin')
    //     // $totalIzin = Absensi::where('status', 'Izin')
    //     //     ->whereDate('created_at', now()->toDateString()) // Filter untuk hari ini
    //     //     ->count();

    //     // return [
    //     //     Stat::make('Total Karyawan', $totalKaryawan)
    //     //         ->description("$totalKaryawan total")
    //     //         ->descriptionIcon('heroicon-m-user-group')
    //     //         ->color('success'),

    //     //     // Stat::make('Karyawan Hadir Hari ini', $totalHadir)
    //     //     //     ->description("$totalHadir hadir")
    //     //     //     ->descriptionIcon('heroicon-m-check-circle')
    //     //     //     ->color($totalHadir > 0 ? 'success' : 'danger'),

    //     //     Stat::make('Total Izin', $totalIzin)
    //     //         ->description("$totalIzin izin")
    //     //         ->descriptionIcon('heroicon-m-information-circle')
    //     //         ->color('warning'),
    //     // ];
    // }
}
