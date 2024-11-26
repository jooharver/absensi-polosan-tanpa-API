<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaceRecognitionController extends Controller
{
    public function recognize(Request $request)
    {
        // Validate input
        $request->validate([
            'image' => 'required|string',
        ]);

        try {
            // Decode the base64 image
            $imageData = $request->input('image');
            $image = base64_decode($imageData);

            // Save the image temporarily
            $imageName = 'face_' . time() . '.jpg';
            $imagePath = base_path('public/faces/' . $imageName);
            file_put_contents($imagePath, $image);

            // Define the Python script path
            $pythonScriptPath = base_path(path: 'scripts/recognize_face.py');

            // Build the command
            $command = ["python3", $pythonScriptPath, $imagePath];

            // Prepare descriptors for stdout and stderr
            $descriptors = [
                1 => ['pipe', 'w'], // stdout
                2 => ['pipe', 'w'], // stderr
            ];

            // Open the process
            $process = proc_open($command, $descriptors, $pipes);

            if (is_resource($process)) {
                // Capture stdout and stderr
                $output = stream_get_contents($pipes[1]); // stdout
                $error = stream_get_contents($pipes[2]); // stderr

                // Close the pipes
                fclose($pipes[1]);
                fclose($pipes[2]);

                // Close the process and get the exit code
                $exitCode = proc_close($process);


                // Process the Python script output
                if ($exitCode === 0) {
                    $result = json_decode($output, true);

                    if ($result && isset($result['matched']) && $result['matched'] === true) {
                        return response()->json(['status' => 'success', 'message' => 'Face matched. Presence accepted.']);
                    } else {
                        return response()->json(['status' => 'fail', 'message' => 'Face not recognized.'], 401);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error executing Python script.',
                        'details' => $error,
                    ], 500);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Unable to open process.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
