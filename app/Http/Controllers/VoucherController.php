<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shops;
use App\Models\Voucher;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'discount' => 'required|decimal:2',
            'total_used' => 'required|integer',
            'shop_id' => 'required|integer',
            'min_spend' => 'required|integer',
            'expired_date' => 'required|date',
            't&c' => 'required|string',
        ]);

        $voucher = Voucher::create([
            'discount' => $fields['discount'],
            'total_used' => $fields['total_used'],
            'shop_id' => $fields['shop_id'],
            'min_spend' => $fields['min_spend'],
            'expired_date' => $fields['expired_date'],
            't&c' => $fields['t&c'],
        ]);

        $response = [
            'message' => 'Successfully create Voucher',
        ];

        return response($response, 201);
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
