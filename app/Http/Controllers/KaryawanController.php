<?php
namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Auth;

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
        $mpdf->Output('Karyawan.pdf', 'D');
    }

    //fLutter get data karyawan
    public function show(Request $request)
    {
        // Mengambil ID karyawan berdasarkan user yang login
        $user = Auth::user();

        if (!$user || !$user->karyawan_id) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak ditemukan atau tidak memiliki akses',
            ], 404);
        }

        // Mengambil data karyawan terkait
        $karyawan = Karyawan::with('posisi') // Mengambil data posisi jika diperlukan
            ->where('id_karyawan', $user->karyawan_id)
            ->first();

        if (!$karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $karyawan,
        ], 200);
    }
}
