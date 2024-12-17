<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Log;
use App\Models\Absen;
use Carbon\Carbon;

class FaceRecognitionController extends Controller
{
    public function recognize(Request $request)
    {
        try {
            // Validasi input
            $request->validate(['image' => 'required|string']);
    
            // Decode dan simpan gambar sementara
            $imageData = base64_decode($request->input('image'));
            if (!$imageData) {
                return response()->json(['status' => 'error', 'message' => 'Invalid image data.'], 400);
            }
    
            $tempDir = storage_path('app/public/temp');
            if (!file_exists($tempDir)) mkdir($tempDir, 0755, true);
    
            $imagePath = $tempDir . '/face_' . time() . '.jpg';
            file_put_contents($imagePath, $imageData);
    
            // Ambil pengguna dan face vector karyawan
            $user = \Illuminate\Support\Facades\Auth::user();
            if (!$user || !$user->karyawan_id) {
                unlink($imagePath); // Hapus file jika ada error
                return response()->json(['status' => 'error', 'message' => 'User tidak valid.'], 500);
            }
    
            $karyawan = Karyawan::find($user->karyawan_id);
            if (!$karyawan || !$karyawan->face_vector) {
                unlink($imagePath);
                return response()->json(['status' => 'error', 'message' => 'Face vector tidak ditemukan.'], 500);
            }
    
            // Simpan face vector sementara
            $faceVectorPath = $tempDir . '/face_vector_' . time() . '.json';
            file_put_contents($faceVectorPath, $karyawan->face_vector);
    
            // Jalankan Python script untuk mengenali wajah
            $pythonScriptPath = base_path('scripts/recognize_face.py');
            $command = "python3 " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg($imagePath) . " " . escapeshellarg($faceVectorPath);
            exec($command, $output, $returnVar);
    
            // Hapus file sementara
            unlink($imagePath);
            unlink($faceVectorPath);
    
            // Proses hasil Python script
            if ($returnVar !== 0) {
                return response()->json(['status' => 'error', 'message' => 'Proses pengenalan wajah gagal.'], 500);
            }
    
            $result = json_decode(implode("\n", $output), true);
            if (isset($result['matched']) && $result['matched'] === true) {
                return $this->recordAttendance($user->karyawan_id);
            } else {
                return response()->json(['status' => 'fail', 'message' => 'Wajah tidak dikenali.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    private function recordAttendance($karyawan_id)
    {
        $today = Carbon::today();
        $absen = Absen::where('karyawan_id', $karyawan_id)
            ->where('tanggal', $today)
            ->first();
    
        if (!$absen) {
            $absen = new Absen();
            $absen->karyawan_id = $karyawan_id;
            $absen->tanggal = $today;
            $absen->jam_masuk = Carbon::now();



            // $absen->jam_masuk = Carbon::now()->subHours(1);
            $message = 'Presensi masuk berhasil dicatat';
            $type = 'check_in';
            $time = $absen->jam_masuk;
        } elseif (!$absen->jam_keluar) {
            $absen->jam_keluar = Carbon::now();



            // $absen->jam_keluar = Carbon::now()->subHours(1);
            $message = 'Presensi pulang berhasil dicatat';
            $type = 'check_out';
            $time = $absen->jam_keluar;
        } else {
            // Jika sudah ada jam_masuk dan jam_keluar
            return response()->json([
                'status' => 'info',
                'message' => 'Anda sudah presensi hari ini!',
                'data' => [
                    'check_in' => $absen->jam_masuk,
                    'check_out' => $absen->jam_keluar,
                ],
            ], 500);
        }
    
        // Simpan data baru atau update data lama
        $absen->save();
    
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => [
                'type' => $type,
                'time' => $time,
            ],
        ], 200);
    }    
    

    // Face add (from filament)
    public function saveFaceVector($imagePath, $id)
    {
        $imagePath = base_path('storage/app/public/' . $imagePath);
        Log::info('saveFaceVector method called.');
        Log::info('Karyawan ID: ' . $id);
        Log::info('Image path: ' . $imagePath);
    
        // Fetch Karyawan record
        $karyawan = Karyawan::findOrFail($id);
        Log::info('Karyawan found: ' . $karyawan->id);
    
        try {
            // Convert the face to a vector
            $faceVector = $this->convertFaceToVector($imagePath);
            if ($faceVector === null) {
                // Jika gagal memproses wajah
                throw new \Exception('Face processing failed or no valid face vector returned.');
            }
    
            // Save the face vector to the database
            $this->saveFaceVectorToDatabase($karyawan, $faceVector);
    
            // Hapus file foto setelah berhasil
            $this->deleteImage($imagePath);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Face vector successfully saved to the database.',
            ]);
        } catch (\Exception $e) {
            // Log error
            Log::error('Error during face vector saving: ' . $e->getMessage());
    
            // Hapus file foto jika gagal
            $this->deleteImage($imagePath);
    
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    private function convertFaceToVector($imagePath)
    {
        // Path to the Python script
        $pythonScriptPath = base_path('scripts/add_face.py');
        Log::info('Python script path: ' . $pythonScriptPath);

        // Build the command
        $command = ["python3", $pythonScriptPath, $imagePath];
        Log::info('Command to execute: ' . implode(' ', $command));
        // Prepare descriptors for stdout and stderr
        $descriptors = [
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
        ];

        // Open the process
        $process = proc_open($command, $descriptors, $pipes);

        if (is_resource($process)) {
            Log::info('Process opened successfully.');

            // Capture stdout and stderr
            $output = stream_get_contents($pipes[1]); // stdout
            $error = stream_get_contents($pipes[2]); // stderr

            // Close the pipes
            fclose($pipes[1]);
            fclose($pipes[2]);

            // Close the process and get the exit code
            $exitCode = proc_close($process);
            Log::info('Process exited with code: ' . $exitCode);

            if ($exitCode === 0) {
                Log::info('Python script executed successfully.');
                $result = json_decode($output, true);
                Log::info('Decoded result: ' . json_encode($result));

                if ($result && isset($result['face_vector'])) {
                    return $result['face_vector'];
                } else {
                    Log::error('Invalid or missing face vector in Python script output.');
                }
            } else {
                Log::error('Python script execution failed with exit code: ' . $exitCode);
                Log::error('Error output: ' . $error);
            }
        } else {
            Log::error('Unable to open process for Python script.');
        }

        return null; // Return null if processing fails
    }

    private function saveFaceVectorToDatabase($karyawan, $faceVector)
    {
        Log::info('Saving face vector to database.');
        $karyawan->face_vector = json_encode($faceVector);
        $karyawan->save();
        Log::info('Face vector saved successfully.');
    }

    private function deleteImage($imagePath)
    {
        try {
            if (file_exists($imagePath)) {
                unlink($imagePath);
                Log::info('Image deleted: ' . $imagePath);
            } else {
                Log::warning('Image not found for deletion: ' . $imagePath);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
        }
    }
}
