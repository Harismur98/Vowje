<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Utils\UniqueCodeGenerator;


class AuthController extends Controller
{
    //
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $uniqueCode = UniqueCodeGenerator::generateUniqueCode();

        $user = User::create([
            
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'],
            'unique_code' => $uniqueCode,
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
}
