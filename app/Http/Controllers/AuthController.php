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

    public function logout(Request $request)
    {
        // Pastikan pengguna sedang login
        $user = Auth::user();

        if ($user) {
            // Hapus semua token pengguna
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout successful',
            ], 200);
        }

        return response()->json([
            'message' => 'No user logged in',
        ], 401);
    }
}
