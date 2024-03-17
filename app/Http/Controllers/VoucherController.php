<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shops;
use App\Models\Voucher;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::with('shop')->get();
        return response()->json(['data' => $vouchers], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = [
            'discount' => 'required|decimal:2',
            'total_used' => 'required|integer',
            'shop_id' => 'required|integer',
            'min_spend' => 'required|integer',
            'expired_date' => 'required|date',
            't&c' => 'required|string',
            'max_voucher_used' => 'required|integer',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $fields);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $voucher = Voucher::create([
            'discount' => $request['discount'],
            'total_used' => $request['total_used'],
            'shop_id' => $request['shop_id'],
            'min_spend' => $request['min_spend'],
            'expired_date' => $request['expired_date'],
            't&c' => $request['t&c'],
            'max_voucher_used' => $request['max_voucher_used'],
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
        $voucher = Voucher::findOrFail($id);
        return response()->json(['data' => $voucher], 200);
    }


    public function update(Request $request, $id)
    {
        // Define validation rules
        $rules = [
            'discount' => 'required|decimal:2',
            'total_used' => 'required|integer',
            'min_spend' => 'required|integer',
            'expired_date' => 'required|date',
            't&c' => 'required|string',
            'max_voucher_used' => 'required|integer',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Get the voucher
        $voucher = Voucher::findOrFail($id);

        // Check if the user ID matches the current user's ID associated with the shop
        $userId = auth()->user()->id;
        $shopUserId = $voucher->shop->user_id;
        if ($userId !== $shopUserId) {
            return response()->json(['error' => 'You are not authorized to update this voucher.'], 403);
        }

        // Update the voucher
        $voucher->update($request->all());

        return response()->json(['message' => 'Voucher updated successfully', 'data' => $voucher], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return response()->json(['message' => 'Voucher deleted successfully'], 200);
    }
}
