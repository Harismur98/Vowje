<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function serve($path, $size = null)
    {
        $fullPath = 'public/' . $path;  // Note the 'public/' prefix

        Log::info('Attempting to serve image: ' . $fullPath);

        if (!Storage::exists($fullPath)) {
            Log::error('Image not found: ' . $fullPath);
            return response()->json(['error' => 'Image not found'], 404);
        }

        try {
            $image = Image::make(Storage::get($fullPath));

            if ($size) {
                list($width, $height) = explode('x', $size);
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            return $image->response();
        } catch (\Exception $e) {
            Log::error('Error processing image: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing image'], 500);
        }
    }
}