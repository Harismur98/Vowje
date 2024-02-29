<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Shops;
use App\Models\User_voucher;
use Carbon\Carbon;

class ClaimController extends Controller
{
    public function vclaim($id)
{
    $voucher_id = $id;

    // Retrieve the voucher based on the provided ID
    $voucher = Voucher::find($voucher_id);

    if (!$voucher) {
        // Handle the case where the voucher does not exist
        return response()->json(['error' => 'Voucher not found'], 404);
    }

    // Compare total_used and max_voucher_used
    if ($voucher->total_used < $voucher->max_voucher_used) {

        // Check if the voucher is expired
        $expired_date = Carbon::parse($voucher->expired_date);

        if (!$expired_date->isPast()) {
            
            // Voucher is not expired, save voucher ID to user_voucher table
            // Assuming you have a UserVoucher model and table
            $userVoucher = User_voucher::create([
                'user_id' => auth()->user()->id, // Assuming you are using authentication
                'voucher_id' => $voucher_id,
            ]);

            // Return a success response
            return response()->json(['message' => 'Voucher claimed successfully'], 200);
        } else {
            // Voucher is expired, return an error response
            return response()->json(['error' => 'Voucher has expired'], 400);
        }
    } else {
        // total_used >= max_voucher_used, return an error response
        return response()->json(['error' => 'Maximum voucher usage reached'], 400);
    }
}
}
