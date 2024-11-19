<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function exportPDF()
    {
        // Ambil data absensi dari database
        $absensis = Absensi::all();

        // Siapkan HTML untuk PDF
        $html = view('exports.absensi_pdf', compact('absensis'))->render();

        // Buat instance mPDF dan konversi HTML ke PDF
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        // Unduh file PDF
        $mpdf->Output('Absensi.pdf', 'D');
    }
}
