<?php
namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class KaryawanController extends Controller
{
    public function exportPDF()
    {
        // Ambil data karyawan dari database
        $karyawans = Karyawan::all();

        // Siapkan HTML untuk PDF
        $html = view('exports.karyawans_pdf', compact('karyawans'))->render();

        // Buat instance mPDF dan konversi HTML ke PDF
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        // Unduh file PDF
        $mpdf->Output('karyawans.pdf', 'D');
    }
}
