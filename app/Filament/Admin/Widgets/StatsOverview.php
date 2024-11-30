<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Absen;
use Carbon\Carbon;
use App\Models\Absensi;
use App\Models\Karyawan;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $karyawan = Karyawan::count();

        $today = Carbon::now('Asia/Jakarta')->toDateString(); // Mendapatkan tanggal hari ini

        $yesterday = Carbon::yesterday('Asia/Jakarta')->toDateString();

        $absensiHariIni = Absen::whereDate('tanggal', $today)->count();

        $absensiHariKemarin = Absen::whereDate('tanggal', $yesterday)->count();

        return [
            Stat::make('Total Karyawan', $karyawan)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Total Hadir Hari Ini', $absensiHariIni)
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Menambahkan ikon
                ->chart([$absensiHariKemarin, $absensiHariIni]) // Menampilkan data chart
                ->color('success'),
            Stat::make('Total Sakit Hari Ini', $absensiHariIni)
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Menambahkan ikon
                ->chart([$absensiHariKemarin, $absensiHariIni]) // Menampilkan data chart
                ->color('gray'),
            Stat::make('Total Izin Hari Ini', $absensiHariIni)
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Menambahkan ikon
                ->chart([$absensiHariKemarin, $absensiHariIni]) // Menampilkan data chart
                ->color('primary'),
            Stat::make('Total Alpha Hari Ini', $absensiHariIni)
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Menambahkan ikon
                ->chart([$absensiHariKemarin, $absensiHariIni]) // Menampilkan data chart
                ->color('danger'),


        ];


    }
}
