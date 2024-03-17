<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Models\Shops;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

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

            $fields = [
                'name' => 'required|string',
                'description' => 'required|string',
                'logo' => 'image|mimes:jpeg,png,jpg',
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $fields);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            // Store the file in storage\app\public folder
            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('uploads', 'public');
    
            $userId = Auth::id();
    
            $shop = Shops::create([
                'user_id' => $userId,
                'name' => $request['name'],
                'description' => $request['description'],
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

    public function update(Request $request, $id)
    {
        // Find the shop by ID
        $shop = Shops::find($id);

        // Check if the shop exists
        if (!$shop) {
            $response = [
                'message' => 'Shop not found',
            ];
            return response()->json($response, 404);
        }

        // Check if the authenticated user is the owner of the shop
        if ($shop->user_id != Auth::id()) {
            $response = [
                'message' => 'Unauthorized. You are not the owner of this shop.',
            ];
            return response()->json($response, 401);
        }

        // Validate request fields
        $fields = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'logo' => 'image|mimes:jpeg,png,jpg',
        ]);

        // Validate the request
        $validator = Validator::make($request->all(), $fields);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Update shop details
        $shop->name = $request['name'];
        $shop->description = $request['description'];

        // Update logo if provided
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('uploads', 'public');
            $shop->filename = $fileName;
            $shop->original_name = $file->getClientOriginalName();
            $shop->file_path = $filePath;
        }

        // Save the changes
        $shop->save();

        // Response
        $response = [
            'message' => 'Shop updated successfully',
            'shop' => $shop,
        ];
        return response()->json($response, 200);
    }

    public function destroy(string $id)
    {
        // Find the shop by ID
        $shop = Shops::find($id);

        // Check if the shop exists
        if (!$shop) {
            $response = [
                'message' => 'Shop not found',
            ];
            return response()->json($response, 404);
        }

        // Check if the authenticated user is the owner of the shop
        if ($shop->user_id != Auth::id()) {
            $response = [
                'message' => 'Unauthorized. You are not the owner of this shop.',
            ];
            return response()->json($response, 401);
        }

        // Delete the shop
        $shop->delete();

        // Response
        $response = [
            'message' => 'Shop deleted successfully',
        ];
        return response()->json($response, 200);
    }

}
