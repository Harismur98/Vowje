<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Models\Shops;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all shops
        $shops = Shops::all();

        // Check if there are any shops
        if ($shops->isEmpty()) {
            $response = [
                'message' => 'No shops found',
            ];
            return response()->json($response, 404);
        }
        // If shops are found, return them as a response
        return response()->json($shops, 200);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        if(auth()->user()->role < 2 ){

            $fields = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'logo' => 'image|mimes:jpeg,png,jpg',
            ]);
    
            // Store the file in storage\app\public folder
            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('uploads', 'public');
    
            $userId = Auth::id();
    
            $shop = Shops::create([
                'user_id' => $userId,
                'name' => $fields['name'],
                'description' => $fields['description'],
                'filename' => $fileName,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
            ]);
    
            $response = [
                'message' => 'Successfully create shop',
            ];
    
            return response($response, 201);
        }
        else{
            $response = [
                'message' => 'To register a shop please login using vendor account',
            ];
            return response($response, 401);    
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
