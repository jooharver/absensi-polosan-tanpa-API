<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Models\RekapAbsensiView;
use Illuminate\Support\Facades\Auth;

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

    //Flutter get Rekap karyawan
    public function getRekapByLoggedInUser(Request $request)
    {
        try {
            // Mendapatkan user yang sedang login
            $user = Auth::user();

            // Pastikan user memiliki data karyawan terkait
            if (!$user || !$user->karyawans) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee data not found',
                ], 404);
            }

            // Cari rekap berdasarkan karyawan_id
            $rekap = RekapAbsensiView::where('karyawan_id', $user->karyawans->id_karyawan)->first();

            if (!$rekap) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance recap not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $rekap,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch attendance recap',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
