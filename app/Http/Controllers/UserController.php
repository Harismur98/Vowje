<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userName = auth()->user()->name;
        $unique_code = auth()->user()->unique_code;
        $birthdate = auth()->user()->birthdate;
        $email = auth()->user()->email;
        $phone_num = auth()->user()->phone_num;

        $response = [
            'username' => $userName,
            'unique_code' => $unique_code,
            'birthdate' => $birthdate,
            'email' => $email,
            'phone_num' => $phone_num,
        ];

        return response()->json(['data' => $response], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
