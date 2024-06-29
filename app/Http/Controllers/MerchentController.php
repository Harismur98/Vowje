<?php

namespace App\Http\Controllers;
use App\Models\Shops;
use Illuminate\Http\Request;

class MerchentController extends Controller
{
    public function index()
    {
        

        $response = [
            'qr_code_id' => $userId,
            'username' => $userName,
            'unique_code' => $unique_code,
            'credit_limit' => $credit_limit,

        ];

        return response($response, 200);
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
