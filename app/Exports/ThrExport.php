<?php

namespace App\Exports;

use App\Models\THR;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ThrExport implements FromArray, WithHeadings, WithTitle
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Ambil semua data THR beserta relasi ke karyawan dan posisi
        $thrData = THR::with('karyawan.posisi')->get();

        // Format data menjadi array yang sesuai
        $formattedData = [];
        foreach ($thrData as $item) {
            $formattedData[] = [
                'ID THR' => $item->id_thr,
                'Nama Karyawan' => optional($item->karyawan)->nama,   // Nama karyawan dari relasi
                'Posisi' => optional($item->karyawan->posisi)->posisi, // Nama posisi dari relasi posisi
                'THR' => $item->thr,
                'Created At' => $item->created_at,
                'Updated At' => $item->updated_at,
            ];
        }

        return $formattedData;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID THR',
            'Nama Karyawan',
            'Posisi',
            'THR',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Tabel THR'; // Judul sheet
    }
} //
