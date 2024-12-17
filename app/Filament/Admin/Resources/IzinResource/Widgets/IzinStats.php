<?php

namespace App\Filament\Admin\Resources\IzinResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class IzinStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Total izin dengan status 'pending'
        $totalPending = DB::table('izins')
            ->where('status', 'pending')
            ->count();

        // Total izin yang dibuat pada hari ini
        $izinHariIni = DB::table('izins')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        // Total izin dengan status 'approved' hari ini
        $approvedHariIni = DB::table('izins')
            ->where('status', 'approved')
            ->whereDate('updated_at', now()->toDateString()) // Filter berdasarkan tanggal hari ini
            ->count();

        return [
            Stat::make('Izin Pending', $totalPending)
                ->description('Jumlah izin dengan status pending')
                ->color('warning'),

            Stat::make('Izin Hari Ini', $izinHariIni)
                ->description('Izin yang dibuat hari ini')
                ->color('info'),

            Stat::make('Approved Hari Ini', $approvedHariIni)
                ->description('Izin yang disetujui hari ini')
                ->color('success'),
        ];
    }
}
