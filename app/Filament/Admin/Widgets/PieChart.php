<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Karyawan; // Model Karyawan
use App\Models\Absen; // Model Absen

class PieChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Kehadiran Hari Ini';

    protected function getData(): array
    {
        // Mengambil total karyawan
        $totalKaryawan = Karyawan::count();

        // Mengambil jumlah karyawan yang telah mengisi kolom hadir
        $hadir = Absen::whereNotNull('hadir')->distinct('karyawan_id')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Statistik Kehadiran',
                    'data' => [$hadir, $totalKaryawan - $hadir], // Hadir dan Tidak Hadir
                    'backgroundColor' => ['#4CAF50', '#F44336'], // Warna hijau dan merah
                ],
            ],
            'labels' => ['Hadir', 'Tidak Hadir'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
