<?php
namespace App\Http\Controllers;

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

    public function hitungHadir(Absen $model)
    {
        // Ambil posisi karyawan
        $posisi = $model->karyawan->posisi;

        if ($posisi) {
            // Ambil batas jam masuk dan jam keluar dari posisi karyawan
            $batasJamMasuk = strtotime($posisi->jam_masuk); // Jam masuk yang ditentukan (misalnya jam 07:00)
            $batasJamKeluar = strtotime($posisi->jam_keluar); // Jam keluar yang ditentukan (misalnya jam 15:00)

            $jamMasuk = strtotime($model->jam_masuk);
            $jamKeluar = $model->jam_keluar ? strtotime($model->jam_keluar) : null;

            // Jika jam masuk lebih awal dari batas jam masuk, set jam masuk ke batas jam masuk
            if ($jamMasuk < $batasJamMasuk) {
                $jamMasuk = $batasJamMasuk;
            }

            if ($jamKeluar) {
                // Menentukan jam keluar terdekat (dibulatkan ke jam penuh terdekat)
                $jamKeluarTerdekat = floor($jamKeluar / 3600) * 3600;

                // Jika jam keluar lebih besar atau sama dengan jam keluar yang ditentukan
                if ($jamKeluar >= $batasJamKeluar) {
                    $jamKeluarTerdekat = $batasJamKeluar;
                }

                // Hitung berapa jam penuh yang dilalui antara jam masuk dan jam keluar
                $durasiHadir = floor(($jamKeluarTerdekat - $jamMasuk) / 3600); // Hitung durasi dalam jam penuh

                // Jika durasi hadir kurang dari 1 jam, anggap sebagai 0
                if ($durasiHadir < 1) {
                    $durasiHadir = 0;
                }

                // Set durasi hadir
                $model->hadir = gmdate('H:i:s', $durasiHadir * 3600);
            } else {
                // Jika jam keluar belum diinputkan, beri nilai default
                $model->hadir = '00:00:00';
            }

            $model->save();
        } else {
            throw new \Exception('Posisi karyawan tidak ditemukan.');
        }
    }

    public function hitungAlpha(Absen $model)
    {
        if ($model->jam_masuk && $model->jam_keluar) {
            // Ambil data posisi karyawan
            $posisi = $model->karyawan->posisi;

            if ($posisi && $posisi->jam_masuk && $posisi->jam_kerja_per_hari) {
                $batasJamMasuk = strtotime($posisi->jam_masuk); // Batas jam masuk yang seharusnya
                $jamKerjaPerHari = $posisi->jam_kerja_per_hari; // Jam kerja per hari yang telah ditentukan

                // Hitung keterlambatan (alpha)
                $jamMasuk = strtotime($model->jam_masuk);
                $selisihDetik = $jamMasuk - $batasJamMasuk;
                $durasiAlphaJam = 0;

                if ($selisihDetik > 0) {
                    // Bulatkan ke atas keterlambatan dalam jam penuh
                    $durasiAlphaJam = ceil($selisihDetik / 3600);
                    $durasiAlphaJam = min($durasiAlphaJam, $jamKerjaPerHari); // Durasi alpha dibatasi jam kerja per hari
                }

                // Hitung durasi kerja total dengan memperhatikan batas jam keluar
                $jamKeluar = strtotime($model->jam_keluar);
                $jamKeluarBulat = floor($jamKeluar / 3600) * 3600; // Bulatkan ke bawah ke kelipatan jam terdekat

                // Hitung durasi hadir
                $totalDurasiHadirDetik = $jamKeluarBulat - $jamMasuk;
                $totalDurasiHadirJam = floor($totalDurasiHadirDetik / 3600); // Durasi hadir dalam jam penuh

                // Durasi hadir tidak boleh lebih dari jam kerja per hari
                $durasiHadirJam = min($totalDurasiHadirJam, $jamKerjaPerHari);

                // Simpan hasil
                $model->alpha = gmdate('H:i:s', $durasiAlphaJam * 3600); // Format H:i:s untuk alpha
                $model->hadir = gmdate('H:i:s', $durasiHadirJam * 3600); // Format H:i:s untuk hadir
                $model->save();
            }
        }
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
