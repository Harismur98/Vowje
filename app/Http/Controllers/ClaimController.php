<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Shops;
use App\Models\User_voucher;
use App\Models\User_stamp;
use Carbon\Carbon;
use App\Models\Stamp_points;

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

                $response = [
                    'message' => 'Voucher claimed successfully',
                    'voucher_id' => $voucher_id,
                ];

                // Return a success response
                return response()->json($response, 200);
            } else {
                // Voucher is expired, return an error response
                return response()->json(['error' => 'Voucher has expired'], 400);
            }
        } else {
            // total_used >= max_voucher_used, return an error response
            return response()->json(['error' => 'Maximum voucher usage reached'], 400);
        }
    }

    public function pointCollection($id) {
        $stamp_point = Stamp_points::find($id);
    
        if (!$stamp_point) {
            return response()->json(['error' => 'Stamp point not found'], 404);
        } else {
            $user_id = auth()->user()->id;
    
            $stamp = User_stamp::where('stamp_id', $stamp_point->id)
                               ->where('user_id', $user_id)
                               ->first();
    
            if (!$stamp) {
                // If the record doesn't exist, create a new one
                User_stamp::create([
                    'user_id' => $user_id,
                    'stamp_id' => $stamp_point->id,
                    'collected_stamp' => 1, // Initial collected stamp value
                ]);
            } else {
                // If the record exists, update the collected_stamp value
                $stamp->update([
                    'collected_stamp' => $stamp->collected_stamp + 1,
                ]);
            }
    
            return response()->json(['message' => 'Yeah'], 200);
        }
    }
    
    
}
