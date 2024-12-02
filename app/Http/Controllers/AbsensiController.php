<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Mpdf\Mpdf;
// use App\Models\Absensi;
// use Illuminate\Support\Facades\Auth;

// class AbsensiController extends Controller
// {
//     /**
//      * Menyimpan data absensi dan menghitung durasi hadir.
//      *
//      * @param \Illuminate\Http\Request $request
//      * @return \Illuminate\Http\Response
//      */
//     public function exportPDF()
//     {
//         // Ambil data absensi dari database
//         // $absensis = Absensi::all();

//         // Siapkan HTML untuk PDF
//         $html = view('exports.absensi_pdf', compact('absensis'))->render();

//         // Buat instance mPDF dan konversi HTML ke PDF
//         $mpdf = new Mpdf();
//         $mpdf->WriteHTML($html);

//         // Unduh file PDF
//         $mpdf->Output('Absensi.pdf', 'D');
//     }

//     // FLutter get history
//     public function getHistory(Request $request)
//     {
//         // Mendapatkan user yang sedang login
//         $user = Auth::user();

//         // Pastikan user memiliki data karyawan terkait
//         if (!$user || !$user->karyawans) {
//             return response()->json(['message' => 'Employee data not found'], 404);
//         }

//         // Ambil bulan dan tahun dari request
//         $month = $request->query('month');
//         $year = $request->query('year');

//         // Ambil absensi berdasarkan karyawan_id dan filter bulan dan tahun jika ada
//         $query = $user->karyawans->absensis()->orderBy('tanggal', 'desc');

//         if ($month && $year) {
//             $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
//         }

//         $history = $query->get();

//         return response()->json([
//             'karyawan' => $user->karyawans,
//             'history' => $history,
//         ]);
//     }

//      // Menampilkan form edit absensi
//      public function edit($id)
//      {
//          $absensi = Absensi::findOrFail($id);
//          return view('absensi.edit', compact('absensi'));
//      }

//      // Mengupdate absensi
//      public function update(Request $request, $id)
//      {
//          $absensi = Absensi::findOrFail($id);

//          $absenMasuk = $request->input('absen_masuk');
//          $absenKeluar = $request->input('absen_keluar');

//          // Debugging: Cek apakah waktu masuk dan keluar sudah sesuai
//          dd($absenMasuk, $absenKeluar);

//          if ($absenMasuk && $absenKeluar) {
//              $start = strtotime($absenMasuk);
//              $end = strtotime($absenKeluar);

//              // Debugging: Cek hasil perhitungan durasi
//              dd($start, $end);

//              $durationInSeconds = $end - $start;

//              // Format durasi ke jam:menit:detik
//              $hours = floor($durationInSeconds / 3600);
//              $minutes = floor(($durationInSeconds % 3600) / 60);
//              $seconds = $durationInSeconds % 60;

//              // Debugging: Cek durasi yang dihitung
//              dd($hours, $minutes, $seconds);

//              $absensi->hadir = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
//          }

//          $absensi->absen_masuk = $absenMasuk;
//          $absensi->absen_keluar = $absenKeluar;
//          $absensi->save();

//          return redirect()->route('admin.absensi')->with('success', 'Absensi berhasil diperbarui!');
//      }

// }

