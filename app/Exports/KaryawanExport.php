<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KaryawanExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil semua data Karyawan dengan relasi posisi
        return Karyawan::with('posisi')->get()->map(function ($karyawan) {
            return [
                'ID Karyawan' => $karyawan->id_karyawan,
                'NIK' => $karyawan->nik,
                'Nama' => $karyawan->nama,
                'Tanggal Lahir' => $karyawan->tanggal_lahir,
                'Jenis Kelamin' => $karyawan->jenis_kelamin,
                'Alamat' => $karyawan->alamat,
                'Agama' => $karyawan->agama,
                'No Telepon' => $karyawan->no_telepon,
                'Email' => $karyawan->email,
                'Tanggal Masuk' => $karyawan->tanggal_masuk,
                'Foto Path' => $karyawan->foto_path,
                'Posisi' => optional($karyawan->posisi)->posisi, // Mengambil kolom posisi dari relasi
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Karyawan',
            'NIK',
            'Nama',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Alamat',
            'Agama',
            'No Telepon',
            'Email',
            'Tanggal Masuk',
            'Foto Path',
            'Posisi',
        ];
    }
}
