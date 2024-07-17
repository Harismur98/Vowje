<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credit;
use Illuminate\Support\Facades\Auth;
use App\Models\Shops;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;

class Credits extends Controller
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
        $userId = Auth::id();
        // Get shop id from the shop table using user id
        $shop = Shops::where('user_id', $userId)->first();
        
        if (!$shop) {
            return response()->json(['error' => 'Shop not found'], 404);
        }

        // Validate the request
        $fields = [
            'credit' => 'required|numeric',
            'transaction_type' => 'required|in:add,deduct',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $fields);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Check if a credit record already exists for this shop
        $credit = Credit::where('shop_id', $shop->id)->first();

        if ($credit) {
            // Create a new credit record if it does not exist
            $credit = Credit::where('shop_id', $shop->id)->first();
            //plus with new credit
            $newCredit = $credit->credit + $request->credit;
            $credit->update([
                'credit' => $newCredit
            ]);
        }
        else {
            // Create a new credit record          
            $credit = Credit::create([
                'shop_id' => $shop->id,
                'credit' => $request->credit,
            ]);

            //return response()->json(['message' => 'Credit created successfully', 'credit' => $credit], 201);
        }

        // Create a new transaction
        $transaction = Transaction::create([
            'credit_id' => $credit->id,
            'amount' => $request->credit,
            'transaction_type' => $request->transaction_type,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Transaction processed successfully', 'transaction' => $transaction, 'credit' => $credit], 200);
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
