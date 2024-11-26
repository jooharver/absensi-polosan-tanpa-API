<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RekapAbsensiController;
use App\Http\Controllers\FaceRecognitionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/history', [AuthController::class, 'getHistory']);

Route::middleware('auth:sanctum')->get('/rekap-absensi', [RekapAbsensiController::class, 'getRekapByLoggedInUser']);

Route::post('/face-recognition', [FaceRecognitionController::class, 'recognize']);
