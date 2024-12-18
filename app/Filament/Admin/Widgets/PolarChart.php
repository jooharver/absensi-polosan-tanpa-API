<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Absen; // Import model Absen
use Carbon\Carbon;

class PolarChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Presensi Bulan Ini';

    protected function getData(): array
    {
        $currentMonth = Carbon::now()->month; // Mendapatkan bulan saat ini
        $currentYear = Carbon::now()->year; // Mendapatkan tahun saat ini

        // Menghitung jumlah data berdasarkan kolom absensi dengan pengecualian '00:00:00' dan NULL, serta hanya bulan ini
        $totalHadir = Absen::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->whereNotNull('hadir')
            ->where('hadir', '!=', '00:00:00')
            ->count();

        $totalSakit = Absen::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->whereNotNull('sakit')
            ->where('sakit', '!=', '00:00:00')
            ->count();

        $totalIzin = Absen::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->whereNotNull('izin')
            ->where('izin', '!=', '00:00:00')
            ->count();

        $totalAlpha = Absen::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->whereNotNull('alpha')
            ->where('alpha', '!=', '00:00:00')
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Rekap Absensi Bulan Ini',
                    'data' => [$totalHadir, $totalSakit, $totalIzin, $totalAlpha],
                    'backgroundColor' => ['#B2C9AD', '#91AC8F','#66785F' ,'#4B5945' ], // Warna kategori
                ],
            ],
            'labels' => ['Hadir', 'Sakit', 'Izin', 'Alpha'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
