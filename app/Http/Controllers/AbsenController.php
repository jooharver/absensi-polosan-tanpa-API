<?php
namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    // public function hitungAlpha(Absen $absen)
    // {
    //     $sakit = strtotime($absen->sakit);
    //     $izin = strtotime($absen->izin);

    //     // Hitung total durasi alpha (sakit + izin)
    //     $totalAlpha = $sakit + $izin - strtotime('00:00:00');

    //     // Simpan durasi alpha ke dalam model
    //     $absen->alpha = gmdate('H:i:s', $totalAlpha);
    //     $absen->save();

    //     return $absen;
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
        // Validasi input
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'karyawan_id' => 'nullable|exists:karyawans,id_karyawan',
            'absen_masuk_id' => 'nullable|exists:absen_masuk,id_absen_masuk',
            'absen_keluar_id' => 'nullable|exists:absen_keluar,id_absen_keluar',
            'sakit' => 'required|date_format:H:i:s', // Pastikan 'sakit' tidak kosong
            'izin' => 'nullable|date_format:H:i:s',
            'alpha' => 'nullable|date_format:H:i:s',
            'keterangan' => 'nullable|string',
        ]);

        // Simpan data ke database
        $absen = Absen::create($validatedData);

        // Return response
        return response()->json([
            'message' => 'Data absen berhasil disimpan!',
            'data' => $absen
        ]);
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
        // Validasi input
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'karyawan_id' => 'nullable|exists:karyawans,id_karyawan',
            'absen_masuk_id' => 'nullable|exists:absen_masuk,id_absen_masuk',
            'absen_keluar_id' => 'nullable|exists:absen_keluar,id_absen_keluar',
            'sakit' => 'required|date_format:H:i:s', // Pastikan 'sakit' tidak kosong
            'izin' => 'nullable|date_format:H:i:s',
            'alpha' => 'nullable|date_format:H:i:s',
            'keterangan' => 'nullable|string',
        ]);

        // Update data di database
        $absen->update($validatedData);

        // Return response
        return response()->json([
            'message' => 'Data absen berhasil diperbarui!',
            'data' => $absen
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absen $absen)
    {
        $absen->delete();

        return response()->json([
            'message' => 'Data absen berhasil dihapus!'
        ]);
    }
}
