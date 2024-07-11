<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Shops;
use App\Models\User_voucher;
use App\Models\User_stamp;
use App\Models\User;
use App\Models\Stamp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Stamp_points;
use App\Models\Credit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ClaimController extends Controller
{
    public function transactionProcess($total_required_stamp, $user_total_stamp, $new_total_stamp, $shopId) {
        if($total_required_stamp >= $user_total_stamp){
            $amount = 0.1 * $new_total_stamp;

            //get credit id from credit table using shop id
            $credit = Credit::where('shop_id', $shopId)->first();

            //update transaction table for merchant
            $transaction = Transaction::create([
                'credit_id' => $credit->id,
                'amount' => $amount,
                'transaction_type' => Transaction::TRANSACTION_TYPE_DEDUCT,
                'status' => 'completed',
                'payment_method' => 'credit',
                'description' => "Give $user_total_stamp Stamp point ", 
            ]);

            $transaction->processTransaction();
        }
    }
    public function vclaim($id, $userId)
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
                    'user_id' => $userId, 
                    'voucher_id' => $voucher_id,
                    'is_used' => 1
                ]);
                $user = User::find('id', $userId);
                $message = "$user->name get RM$voucher->discount discount";
                $response = [
                    'message' => $message,
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

    public function sclaim($id, $userId){

        $stamp = Stamp::findOrFail($id);
        if (!$stamp) {
            // Handle the case where the stamp does not exist
            return response()->json(['error' => 'Stamp not found'], 404);
        }

        $expired_date = Carbon::parse($stamp->expired_date);

        if (!$expired_date->isPast()) {
            
            // Stamp is not expired, save voucher ID to user_stamp table
            //update user stamp is_used to 1
            $user_stamp = User_stamp::find('user_id', $userId)->where('stanp_id', $id);

            $user_stamp->is_used = 1;
            $user_stamp->save();

            $user = User::find('id', $userId);
            $message = "$user->name have claimed $stamp->reward";
            $response = [
                'message' => $message,
                'stamp_id' => $id,
                'reward' => $stamp->reward
            ];

            // Return a success response
            return response()->json($response, 200);
        } else {
            // Voucher is expired, return an error response
            return response()->json(['error' => 'Voucher has expired'], 400);
        }
    }

    public function pointCollection(Request $request) {
        $userId = Auth::id();
        Log::info('User ID: ' . $userId);
    
        // Get shop id from the shop table using user id
        $shop = Shops::where('user_id', $userId)->first();
        if (!$shop) {
            Log::error('Shop not found for user ID: ' . $userId);
            return response()->json(['error' => 'Shop not found'], 404);
        }
        Log::info('Shop ID: ' . $shop->id);
    
        $fields = [
            'user_id' => 'required|integer',
            'total_stamp' => 'required|integer', //for checking how many merchant give point to requester
            'stamp_id' => 'required',
        ];
    
        // Validate the request
        $validator = Validator::make($request->all(), $fields);
    
        // Check if validation fails
        if ($validator->fails()) {
            Log::error('Validation failed: ' . json_encode($validator->errors()));
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        // Get total_required_stamp from stamp table using stamp_id
        $stamp = Stamp::where('id', $request->stamp_id)->first();
        if (!$stamp) {
            Log::error('Stamp not found for stamp ID: ' . $request->stamp_id);
            return response()->json(['error' => 'Stamp not found'], 404);
        }
        $total_required_stamp = $stamp->total_required_stamps;
        Log::info('Total required stamp: ' . $total_required_stamp);
    
        $user_stamp = User_stamp::where('user_id', $request->user_id)
            ->where('stamp_id', $request->stamp_id)
            ->first();
    
        if (!$user_stamp) {
            Log::info('User stamp not found, creating new record');
            $this->transactionProcess($total_required_stamp, $request->total_stamp, $request->total_stamp, $shop->id);
            $user_stamp = User_stamp::create([
                'user_id' => $request->user_id,
                'stamp_id' => $request->stamp_id,
                'collected_stamp' => $request->total_stamp
            ]);
    
            Log::info('Stamp point added successfully for user ID: ' . $request->user_id);
            return response()->json(['message' => 'Stamp point added successfully'], 200);
        } else {
            Log::info('User stamp found, updating record');
            $user_total_stamp = $user_stamp->collected_stamp + $request->total_stamp;
            $user_stamp->collected_stamp = $user_total_stamp;
            $this->transactionProcess($total_required_stamp, $user_total_stamp, $request->total_stamp, $shop->id);
            $user_stamp->save();
    
            Log::info('Stamp point added successfully for user ID: ' . $request->user_id);
            return response()->json(['message' => 'Stamp point added successfully'], 200);
        }
    
        Log::error("Stamp point exceeded max required $total_required_stamp for user ID: " . $request->user_id);
        return response()->json(['error' => "Stamp point exceeded max required $total_required_stamp"], 400);
    }
    

}
