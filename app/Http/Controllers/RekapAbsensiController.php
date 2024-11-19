<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RekapAbsensiView;

class RekapAbsensiController extends Controller
{
    /**
     * Menampilkan rekap absensi berdasarkan user yang sedang login.
     */
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
