<?php
namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AbsenController extends Controller
{
    //    FLutter get history
    public function getHistory(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Pastikan user memiliki data karyawan terkait
        if (!$user || !$user->karyawans) {
            return response()->json(['message' => 'Data karyawan tidak ditemukan'], 404);
        }

        // Ambil absensi berdasarkan karyawan_id dan filter bulan dan tahun jika ada
        $history = $user->karyawans->absen()->get();

        return response()->json([
            'karyawan' => $user->karyawans,
            'history' => $history,
        ]);
    }

    public function exportPDF()
    {
        // Ambil data absensi dari database
        $absensis = Absen::all();

        // Siapkan HTML untuk PDF
        $html = view('exports.absensi_pdf', compact('absensis'))->render();

        // Buat instance mPDF dan konversi HTML ke PDF
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        // Unduh file PDF
        $mpdf->Output('Absensi.pdf', 'D');
    }

    public function index()
    {
        // Tampilkan data absen
        $absens = Absen::all();
        return response()->json($absens);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return view untuk membuat absen baru
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Absen $absen)
    {
        return response()->json($absen);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absen $absen)
    {
        // Return view untuk edit data absen
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absen $absen)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absen $absen)
    {

    }
}
