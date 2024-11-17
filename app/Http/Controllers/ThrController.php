<?php

namespace App\Http\Controllers;

use App\Exports\ThrExport;
use App\Models\Karyawan;
use App\Models\THR;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ThrController extends Controller
{
    public function export()
    {
        return Excel::download(new ThrExport(), 'thr_data.xlsx');
    }

    public function exportPDF()
    {
        // Ambil data thr dari database
        $thrs = THR::with('karyawan')->get();

        // Siapkan HTML untuk PDF
        $html = view('exports.thr_pdf', compact('thrs'))->render();

        // Buat instance mPDF dan konversi HTML ke PDF
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        // Unduh file PDF
        $mpdf->Output('thr_.pdf', 'D');
    }
}
