<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Utils\UniqueCodeGenerator;
use Illuminate\Support\Facades\Validator;

class VendorAuthController extends Controller
{
    public function register(Request $request){
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $uniqueCode = UniqueCodeGenerator::generateUniqueCode();
        
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
            'gender' => $request['gender'],
            'birthdate' => $request['birthdate'],
            'unique_code' => $uniqueCode,
            'role' => 1,
        ]);
    
        $token = $user->createToken('myapptoken')->plainTextToken;
    
        $response = [
            'user' => $user,
            'token' => $token,
            'uid' => $uniqueCode,
        ];
    
        return response($response, 201);
    }
    

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }
        
        $user->tokens()->delete();
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request) {
        // auth()->user()->tokens()->delete();
        if (auth()->check()) {
            auth()->user()->tokens()->delete();
        }

        return [
            'message' => 'Logged out'
        ];
    }

    public function changePassword(Request $request) {
        $fields = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed'
        ]);

        $user = auth()->user();
        if (!Hash::check($fields['old_password'], $user->password)) {
            return response([
                'message' => 'Wrong password'
            ], 401);
        }

        $user->password = bcrypt($fields['new_password']);
        $user->save();
        
        return response([
            'message' => 'Password changed'
        ], 200);
    }
}
