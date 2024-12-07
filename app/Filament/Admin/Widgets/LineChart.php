<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Absen; // Import model Absen
use Illuminate\Support\Facades\DB;

class LineChart extends ChartWidget
{
    protected static ?string $heading = 'Rekap Kehadiran Tahun 2024';

    protected function getData(): array
    {
        // Mengambil total hadir yang valid (tidak NULL dan tidak '00:00:00') per bulan
        $hadirPerBulan = Absen::select(
                DB::raw('MONTH(tanggal) as bulan'), // Ambil bulan dari kolom tanggal
                DB::raw('COUNT(*) as total') // Hitung jumlah hadir yang valid
            )
            ->whereNotNull('hadir') // Kolom hadir tidak NULL
            ->where('hadir', '!=', '00:00:00') // Kolom hadir tidak '00:00:00'
            ->groupBy(DB::raw('MONTH(tanggal)')) // Kelompokkan berdasarkan bulan
            ->orderBy(DB::raw('MONTH(tanggal)')) // Urutkan berdasarkan bulan
            ->pluck('total', 'bulan'); // Ambil data dalam format bulan => total

        // Inisialisasi data default untuk semua bulan
        $data = array_fill(1, 12, 0); // Isi 12 bulan dengan 0

        // Masukkan data hasil query ke dalam array
        foreach ($hadirPerBulan as $bulan => $total) {
            $data[$bulan] = $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Kehadiran',
                    'data' => array_values($data), // Ambil nilai data per bulan
                    'borderColor' => '#4CAF50', // Warna garis
                    'backgroundColor' => 'rgba(76, 175, 80, 0.2)', // Warna latar belakang garis
                    'fill' => true, // Mengisi area di bawah garis
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Menggunakan line chart
    }
}
