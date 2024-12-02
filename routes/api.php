<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\FaceRecognitionController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AbsenController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// data for flutter
Route::middleware('auth:sanctum')->get('/history', [AbsenController::class, 'getHistory']);
Route::middleware('auth:sanctum')->get('/rekap-absensi', [RekapController::class, 'getRekapByLoggedInUser']);
Route::middleware('auth:sanctum')->get('/karyawan', [KaryawanController::class, 'show']);

// face recognition
Route::post('/face-recognition', [FaceRecognitionController::class, 'recognize']);
