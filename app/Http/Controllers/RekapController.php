<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Models\RekapAbsensiView;

class RekapController extends Controller
{
    public function exportPDF()
    {
        // Ambil data karyawan dari database
        $rekaps = RekapAbsensiView::all();

        // Siapkan HTML untuk PDF
        $html = view('exports.rekap_pdf', compact('rekaps'))->render();

        // Buat instance mPDF dan konversi HTML ke PDF
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        // Unduh file PDF
        $mpdf->Output('Rekap_Absensi.pdf', 'D');
    }
}
