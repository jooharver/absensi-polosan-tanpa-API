<?php
namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    //    FLutter get history
    public function getHistory(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Pastikan user memiliki data karyawan terkait
        if (!$user || !$user->karyawans) {
            return response()->json(['message' => 'Employee data not found'], 404);
        }

        // Ambil bulan dan tahun dari request
        $month = $request->query('month');
        $year = $request->query('year');

        // Ambil absensi berdasarkan karyawan_id dan filter bulan dan tahun jika ada
        $query = $user->karyawans->absen()->orderBy('tanggal', 'desc');

        if ($month && $year) {
            $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }

        $history = $query->get();

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

//lawas

    // public function hitungAlpha(Absen $model)
    // {
    //     if ($model->jam_masuk && $model->jam_keluar) {
    //         // Ambil data posisi karyawan
    //         $posisi = $model->karyawan->posisi;

    //         if ($posisi && $posisi->jam_masuk && $posisi->jam_kerja_per_hari) {
    //             $batasJamMasuk = strtotime($posisi->jam_masuk);
    //             $jamKerjaPerHari = $posisi->jam_kerja_per_hari;

    //             // Hitung keterlambatan (alpha)
    //             $jamMasuk = strtotime($model->jam_masuk);
    //             $selisihDetik = $jamMasuk - $batasJamMasuk;
    //             $durasiAlphaJam = 0;

    //             if ($selisihDetik > 0) {
    //                 // Bulatkan ke atas keterlambatan dalam jam penuh
    //                 $durasiAlphaJam = ceil($selisihDetik / 3600);
    //                 $durasiAlphaJam = min($durasiAlphaJam, $jamKerjaPerHari); // Batas maksimal alpha = jam kerja per hari
    //             }

    //             // Hitung durasi kerja total
    //             $jamKeluar = strtotime($model->jam_keluar);
    //             $totalDurasiKerja = $jamKeluar - $jamMasuk;
    //             $totalDurasiKerjaJam = floor($totalDurasiKerja / 3600); // Durasi kerja dalam jam penuh

    //             // Pastikan hadir tidak melebihi total durasi kerja atau jam kerja per hari
    //             $durasiHadirJam = max(0, min($totalDurasiKerjaJam, $jamKerjaPerHari - $durasiAlphaJam));

    //             // Simpan hasil
    //             $model->alpha = gmdate('H:i:s', $durasiAlphaJam * 3600); // Format H:i:s
    //             $model->hadir = gmdate('H:i:s', $durasiHadirJam * 3600); // Format H:i:s
    //             $model->save();
    //         }
    //     }
    // }


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
