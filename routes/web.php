<?php

use App\Exports\ThrExport;
use App\Exports\RekapExport;
use App\Exports\AbsensiExport;
use App\Exports\KaryawanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThrController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KaryawanController;



Route::get('/', function () {
	// return view('welcome');
	return redirect('/admin');
});

Route::get('/export-karyawan', function () {
    return Excel::download(new KaryawanExport(), 'karyawan_data.xlsx');
})->name('export-karyawan');

Route::get('/karyawan/export-pdf', [KaryawanController::class, 'exportPDF'])->name('karyawan.exportPDF');

Route::get('/export-absensi', function () {
    return Excel::download(new AbsensiExport(), 'absensi_data.xlsx');
})->name('export-absensi');

Route::get('/absensi/export-pdf', [AbsenController::class, 'exportPDF'])->name('absensi.exportPDF');

Route::get('/export-thr', [ThrController::class, 'export'])->name('export-thr');
Route::get('/thr/export-pdf', [ThrController::class, 'exportPDF'])->name('thr.exportPDF');

Route::get('/export-rekap', function () {
    return Excel::download(new RekapExport(), 'rekap_absensi_karyawan.xlsx');
})->name('export-rekap');

Route::get('/rekap/export-pdf', [RekapController::class, 'exportPDF'])->name('rekap.exportPDF');

// Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
//     Route::get('absensi/{id}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
//     Route::post('absensi/{id}/update', [AbsensiController::class, 'update'])->name('absensi.update');
// });

// Route::get('/admin/absensi/{id}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');



