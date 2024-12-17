<?php

namespace App\Filament\Admin\Resources\AbsenResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AbsenStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Ambil total karyawan
        $totalKaryawan = DB::table('karyawans')->count();

        // Ambil jumlah absen masuk hari ini
        $absenMasukHariIni = DB::table('absen')
            ->whereDate('tanggal', now()->toDateString()) // Filter berdasarkan tanggal hari ini
            ->whereNotNull('jam_masuk') // Pastikan jam_masuk tidak NULL
            ->count();

        // Ambil jumlah izin atau sakit hari ini
        $izinHariIni = DB::table('absen')
            ->whereDate('tanggal', now()->toDateString()) // Filter berdasarkan tanggal hari ini
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereNotNull('izin') // Kolom izin tidak NULL
                            ->where('izin', '!=', '00:00:00'); // Kolom izin bukan '00:00:00'
                })->orWhere(function ($subQuery) {
                    $subQuery->whereNotNull('sakit') // Kolom sakit tidak NULL
                            ->where('sakit', '!=', '00:00:00'); // Kolom sakit bukan '00:00:00'
                });
            })
            ->count();


        return [
            Stat::make('Total Karyawan', $totalKaryawan)
                ->description('Jumlah semua karyawan')
                ->color('primary'),

            Stat::make('Absen Masuk Hari Ini', $absenMasukHariIni)
                ->description('Karyawan yang absen masuk hari ini')
                ->color('success'),

            Stat::make('Izin dan Sakit Hari Ini', $izinHariIni)
                ->description('Karyawan yang izin atau sakit hari ini')
                ->color('warning'),
        ];
    }
}
