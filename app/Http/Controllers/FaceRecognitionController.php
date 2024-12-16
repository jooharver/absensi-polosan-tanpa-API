<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Process\Exception\ProcessFailedException;

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

            Log::info('Image data decoded.');

            // Save the image temporarily
            $imageName = 'face_' . time() . '.jpg';
            $imagePath = base_path('public/faces/' . $imageName);
            file_put_contents($imagePath, $image);

            Log::info('Image saved to: ' . $imagePath);

            // Define the Python script path
            $pythonScriptPath = base_path('scripts/recognize_face.py');

            Log::info('Python script path: ' . $pythonScriptPath);

	    // Build the command
	    $python = env('PYTHON_PATH','python3');
            $command = [$python, $pythonScriptPath, $imagePath];
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

                Log::info('Process output: ' . $output);
                Log::error('Process error output: ' . $error);

                // Close the pipes
                fclose($pipes[1]);
                fclose($pipes[2]);

                // Close the process and get the exit code
                $exitCode = proc_close($process);

                Log::info('Process exited with code: ' . $exitCode);

                // Process the Python script output
                if ($exitCode === 0) {
                    Log::info('Python script executed successfully.');

                    $result = json_decode($output, true);

                    Log::info('Decoded result: ' . json_encode($result));

                    if ($result && isset($result['matched']) && $result['matched'] === true) {
                        Log::info('Face matched. Presence accepted.');
                        return response()->json(['status' => 'success', 'message' => 'Face matched. Presence accepted.']);
                    } else {
                        Log::info('Face not recognized.');
                        return response()->json(['status' => 'fail', 'message' => 'Face not recognized.'], 401);
                    }
                } else {
                    Log::error('Error executing Python script.');
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error executing Python script.',
                        'details' => $error,
                    ], 500);
                }
            } else {
                Log::error('Unable to open process.');
                return response()->json(['status' => 'error', 'message' => 'Unable to open process.'], 500);
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
