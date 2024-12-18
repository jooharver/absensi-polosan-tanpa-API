<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Absen; // Import model Absen
use Illuminate\Support\Facades\DB;

class BarChart extends ChartWidget
{
    protected static ?string $heading = 'Rekap Alpha atau Terlambat Tahun 2024';

    protected function getData(): array
    {
        // Mengambil total alpha yang valid (tidak NULL dan tidak '00:00:00') per bulan
        $alphaPerBulan = Absen::select(
                DB::raw('MONTH(tanggal) as bulan'), // Ambil bulan dari kolom tanggal_absen
                DB::raw('COUNT(*) as total') // Hitung jumlah alpha yang valid
            )
            ->whereNotNull('alpha') // Kolom alpha tidak NULL
            ->where('alpha', '!=', '00:00:00') // Kolom alpha tidak '00:00:00'
            ->groupBy(DB::raw('MONTH(tanggal)')) // Kelompokkan berdasarkan bulan
            ->orderBy(DB::raw('MONTH(tanggal)')) // Urutkan berdasarkan bulan
            ->pluck('total', 'bulan'); // Ambil data dalam format bulan => total

        // Inisialisasi data default untuk semua bulan
        $data = array_fill(1, 12, 0); // Isi 12 bulan dengan 0

        // Masukkan data hasil query ke dalam array
        foreach ($alphaPerBulan as $bulan => $total) {
            $data[$bulan] = $total;
        }

        // Daftar warna untuk setiap bulan (warna-warni)
        $colors = [
            '#4B5945',
            '#5C6C56',
            '#6D7F67',
            '#7E926F',
            '#8F9D80',
            '#A0B491',
            '#B1C1A2',
            '#A8C6A4',
            '#B2C9AD',
            '#9FBBA6',
            '#96B1A8',
            '#8DA6AA',

        ];

        // Pastikan jumlah warna sesuai dengan jumlah data (12 bulan)
        $backgroundColors = array_slice($colors, 0, count($data));

        return [
            'datasets' => [
                [
                    'label' => 'Total Alpha',
                    'data' => array_values($data), // Ambil nilai data per bulan
                    'backgroundColor' => $backgroundColors, // Warna berbeda untuk setiap bar
                    // 'borderColor' => '#9E9E9E', // Warna abu-abu untuk border/bar label
                    // 'borderWidth' => 1, // Set border width
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Menggunakan bar chart
    }
}
