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
            return response()->json(['message' => 'Employee data not found'], 404);
        }

        // Ambil absensi berdasarkan karyawan_id dan filter bulan dan tahun jika ada
        $history = $user->karyawans->absen()->get();

        return response()->json([
            'karyawan' => $user->karyawans,
            'history' => $history,
        ]);
    }

    //Still API from face recognition
    public function recordAttendance($karyawan_id)
    {
        try {
            // Get today's date
            $today = Carbon::today();

            // Check for existing Absen record for today
            $absen = Absen::where('karyawan_id', $karyawan_id)
                ->whereDate('tanggal', $today)
                ->first();

            if (!$absen) {
                // No Absen record for today, create one
                $absen = new Absen();
                $absen->karyawan_id = $karyawan_id;
                $absen->tanggal = $today;
                $absen->save();
            }

            if (!$absen->jam_masuk) {
                // User has not checked in yet, record check-in time
                $absen->jam_masuk = Carbon::now();
                $absen->save();

                Log::info('Check-in recorded for karyawan_id: ' . $karyawan_id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Check-in recorded successfully',
                    'data' => [
                        'type' => 'check_in',
                        'time' => $absen->jam_masuk,
                    ]
                ], 200);
            } elseif (!$absen->jam_keluar) {
                // User has checked in but not checked out, record check-out time
                $absen->jam_keluar = Carbon::now();
                $absen->save();

                Log::info('Check-out recorded for karyawan_id: ' . $karyawan_id);

                // Call hitungHadir method now that both jam_masuk and jam_keluar are set
                $this->hitungHadir($absen);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Check-out recorded successfully',
                    'data' => [
                        'type' => 'check_out',
                        'time' => $absen->jam_keluar,
                    ]
                ], 200);
            } else {
                // User has already checked in and out today
                Log::info('User has already checked in and out today.');

                return response()->json([
                    'status' => 'info',
                    'message' => 'You have already checked in and out today',
                    'data' => [
                        'check_in' => $absen->jam_masuk,
                        'check_out' => $absen->jam_keluar,
                    ]
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Error in recordAttendance: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to record attendance',
                'error' => $e->getMessage()
            ], 500);
        }
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
