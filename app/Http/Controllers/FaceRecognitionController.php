<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Log;

class FaceRecognitionController extends Controller
{
    public function recognize(Request $request)
    {
        Log::info('Recognize method called.');

        // Validate input
        $request->validate([
            'image' => 'required|string',
        ]);

        try {
            Log::info('Input validation passed.');

            // Decode the base64 image
            $imageData = $request->input('image');
            $image = base64_decode($imageData);
            if (!$image) {
                Log::error('Failed to decode image data.');
                return response()->json(['status' => 'error', 'message' => 'Invalid image data.'], 400);
            }

            Log::info('Image data decoded.');

            // Save the image temporarily
            $imageName = 'face_' . time() . '.jpg';
            $tempDir = storage_path('app/public/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $imagePath = $tempDir . '/' . $imageName;
            file_put_contents($imagePath, $image);

            Log::info('Image saved to: ' . $imagePath);

            // Get the currently logged-in user
            $user = \Illuminate\Support\Facades\Auth::user();
            if (!$user) {
                Log::error('No authenticated user found.');
                return response()->json(['status' => 'error', 'message' => 'User not authenticated.'], 401);
            }

            // Get karyawan_id from the user
            $karyawan_id = $user->karyawan_id;
            if (!$karyawan_id) {
                Log::error('No karyawan_id associated with user.');
                return response()->json(['status' => 'error', 'message' => 'User has no associated karyawan.'], 400);
            }

            // Retrieve the face vector from the database
            $karyawan = Karyawan::find($karyawan_id);
            if (!$karyawan || !$karyawan->face_vector) {
                Log::error('No face vector found for karyawan_id: ' . $karyawan_id);
                return response()->json(['status' => 'error', 'message' => 'No face vector found for user.'], 400);
            }

            // Save the face vector to a temporary JSON file
            $faceVectorFilename = 'face_vector_' . time() . '.json';
            $faceVectorFilePath = $tempDir . '/' . $faceVectorFilename;
            file_put_contents($faceVectorFilePath, $karyawan->face_vector);

            Log::info('Face vector saved to: ' . $faceVectorFilePath);

            // Define the Python script path
            $pythonScriptPath = base_path('scripts/recognize_face.py');
            Log::info('Python script path: ' . $pythonScriptPath);

            // Build the command
            $command = "python3 " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg($imagePath) . " " . escapeshellarg($faceVectorFilePath);
            Log::info('Command to execute: ' . $command);

            // Execute the command
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);

            Log::info('Process output: ' . implode("\n", $output));
            Log::info('Process exited with code: ' . $returnVar);

            // Delete the temporary files
            if (file_exists($imagePath)) {
                unlink($imagePath);
                Log::info('Temporary image deleted.');
            }
            if (file_exists($faceVectorFilePath)) {
                unlink($faceVectorFilePath);
                Log::info('Temporary face vector file deleted.');
            }

            // Process the Python script output
            $result = json_decode(implode("\n", $output), true);

            if ($returnVar === 0 && $result && isset($result['matched'])) {
                if ($result['matched'] === true) {
                    Log::info('Face matched. Proceeding with attendance.');

                    // Call hitungHadir method in AbsenController
                    $absenController = new AbsenController();
                    $absen = app('App\Http\Controllers\AbsenController')->recordAttendance($karyawan_id);

                    // Call hitungHadir method and pass the Absen model
                    if ($absen) {
                        $absenController->hitungHadir($absen);
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Attendance recorded successfully.',
                    ], 200);
                } else {
                    Log::info('Face not recognized.');
                    $errorDetails = $result['error'] ?? '';
                    return response()->json(['status' => 'fail', 'message' => 'Face not recognized.', 'details' => $errorDetails], 401);
                }
            } else {
                Log::error('Error executing Python script.');
                $errorDetails = $result['error'] ?? 'Unknown error';
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error executing Python script.',
                    'details' => $errorDetails,
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception caught in recognize method: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
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

        // Convert the face to a vector
        $faceVector = $this->convertFaceToVector($imagePath);
        if ($faceVector === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Face processing failed or no valid face vector returned.',
            ], 500);
        }

        // Save the face vector to the database
        $this->saveFaceVectorToDatabase($karyawan, $faceVector);

        return response()->json([
            'status' => 'success',
            'message' => 'Face vector successfully saved to the database.',
        ]);
    }

    private function convertFaceToVector($imagePath)
    {
        // Path to the Python script
        $pythonScriptPath = base_path('scripts/add_face.py');
        Log::info('Python script path: ' . $pythonScriptPath);

        // Build the command
        $command = ["python3", $pythonScriptPath, $imagePath];
        Log::info('Command to execute: ' . implode(' ', $command));
?
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
}
