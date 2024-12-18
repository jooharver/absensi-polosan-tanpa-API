<?php

namespace App\Filament\Admin\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\Absen; // Model Absen
use App\Models\Karyawan; // Model Karyawan

class PieChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Kehadiran Hari Ini';

    protected function getData(): array
    {
        // Mengambil total karyawan
        $totalKaryawan = Karyawan::count();
        // Mendapatkan tanggal hari ini
        $today = Carbon::today()->toDateString();

        // Mengambil jumlah karyawan yang hadir hari ini dengan nilai hadir selain NULL dan '00:00:00'
        $hadir = Absen::where('tanggal', $today) // Pastikan hanya mengambil data untuk hari ini
            ->whereNotNull('hadir')
            ->where('hadir', '!=', '00:00:00') // Pastikan hadir bukan '00:00:00'
            ->distinct('karyawan_id')
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Statistik Kehadiran',
                    'data' => [$hadir, $totalKaryawan - $hadir], // Hadir dan Tidak Hadir
                    'backgroundColor' => ['#B2C9AD', '#66785F'], // Warna hijau dan merah
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
