<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Absen;
use Carbon\Carbon;
use App\Models\Karyawan;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $karyawan = Karyawan::count(); // Total karyawan

        $today = Carbon::now('Asia/Jakarta')->toDateString(); // Mendapatkan tanggal hari ini
        $yesterday = Carbon::yesterday('Asia/Jakarta')->toDateString(); // Mendapatkan tanggal kemarin

        // Total absensi hadir hari ini dan kemarin
        $hadirHariIni = Absen::whereDate('tanggal', $today)
            ->whereNotNull('hadir')
            ->where('hadir', '!=', '00:00:00')
            ->count();

        $hadirHariKemarin = Absen::whereDate('tanggal', $yesterday)
            ->whereNotNull('hadir')
            ->where('hadir', '!=', '00:00:00')
            ->count();

        // Total absensi sakit hari ini dan kemarin
        $sakitHariIni = Absen::whereDate('tanggal', $today)
            ->whereNotNull('sakit')
            ->where('sakit', '!=', '00:00:00')
            ->count();

        $sakitHariKemarin = Absen::whereDate('tanggal', $yesterday)
            ->whereNotNull('sakit')
            ->where('sakit', '!=', '00:00:00')
            ->count();

        // Total absensi izin hari ini dan kemarin
        $izinHariIni = Absen::whereDate('tanggal', $today)
            ->whereNotNull('izin')
            ->where('izin', '!=', '00:00:00')
            ->count();

        $izinHariKemarin = Absen::whereDate('tanggal', $yesterday)
            ->whereNotNull('izin')
            ->where('izin', '!=', '00:00:00')
            ->count();

        // Total absensi alpha hari ini dan kemarin
        $alphaHariIni = Absen::whereDate('tanggal', $today)
            ->whereNotNull('alpha')
            ->where('alpha', '!=', '00:00:00')
            ->count();

        $alphaHariKemarin = Absen::whereDate('tanggal', $yesterday)
            ->whereNotNull('alpha')
            ->where('alpha', '!=', '00:00:00')
            ->count();

        return [
            Stat::make('Total Karyawan', $karyawan)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Total Hadir Hari Ini', $hadirHariIni)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([$hadirHariKemarin, $hadirHariIni]) // Menampilkan data chart untuk hadir
                ->color('success'),

            Stat::make('Total Sakit Hari Ini', $sakitHariIni)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([$sakitHariKemarin, $sakitHariIni]) // Menampilkan data chart untuk sakit
                ->color('warning'),

            Stat::make('Total Izin Hari Ini', $izinHariIni)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([$izinHariKemarin, $izinHariIni]) // Menampilkan data chart untuk izin
                ->color('primary'),

            Stat::make('Total Alpha Hari Ini', $alphaHariIni)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([$alphaHariKemarin, $alphaHariIni]) // Menampilkan data chart untuk alpha
                ->color('danger'),
        ];
    }
}
