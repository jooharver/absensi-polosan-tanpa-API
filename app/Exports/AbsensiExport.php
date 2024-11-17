<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil semua data Absensi dengan relasi karyawan
        return Absensi::with('karyawan')->get()->map(function ($absensis) {
            return [
                'ID Absensi' => $absensis->id_absensi,
                'NIK' => $absensis->karyawan->nama,
                'Nama' => $absensis->tanggal,
                'Tanggal Lahir' => $absensis->jam_masuk,
                'Jenis Kelamin' => $absensis->jam_keluar,
                'Alamat' => $absensis->durasi,
                'Agama' => $absensis->status,
                'No Telepon' => $absensis->keterangan,
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Absensi',
            'Nama',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Durasi',
            'Status',
            'Keterangan',
        ];
    }
}
