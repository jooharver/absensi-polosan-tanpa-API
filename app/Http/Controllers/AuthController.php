<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Buat token untuk autentikasi
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'allowed' => true,
        ]);
    }

    public function getHistory(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Pastikan user memiliki data karyawan terkait
        if (!$user || !$user->karyawans) {
            return response()->json(['message' => 'Employee data not found'], 404);
        }

        // Ambil absensi berdasarkan karyawan_id
        $history = $user->karyawans->absensis()->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'karyawan' => $user->karyawans,
            'history' => $history,
        ]);
    }
}
