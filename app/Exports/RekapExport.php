<?php

namespace App\Exports;

use App\Models\RekapAbsensiView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil data rekap absensi dengan nama karyawan
        return RekapAbsensiView::with('karyawan')->get()->map(function ($rekap) {
            return [
                'Nama' => $rekap->karyawan->nama ?? 'Tidak Diketahui', // Pastikan nama karyawan ada
                'Total Hadir' => $this->formatTime($rekap->total_hadir), // Format waktu jadi H:i
                'Total Sakit' => $this->formatTime($rekap->total_sakit),
                'Total Izin' => $this->formatTime($rekap->total_izin),
                'Total Alpha' => $this->formatTime($rekap->total_alpha),
            ];
        });
    }

    /**
     * Memformat waktu dari H:i:s ke H:i
     */
    private function formatTime($time)
    {
        // Pastikan nilai tidak null atau kosong
        if (empty($time)) {
            return '00:00'; // Default jika nilai tidak valid
        }

        // Ambil hanya jam dan menit dari format H:i:s
        return substr($time, 0, 5); // Mengambil 5 karakter pertama (H:i)
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama',
            'Total Hadir',
            'Total Sakit',
            'Total Izin',
            'Total Alpha',
        ];
    }
}
