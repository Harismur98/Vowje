<?php

namespace App\Http\Controllers;
use App\Models\Shops;
use App\Models\Credit;
use App\Models\Transaction;
use App\Models\Voucher;
use App\Models\Stamp;
use App\Models\User_voucher;
use App\Models\User_stamp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MerchentController extends Controller
{
    public function index()
    {

        $userId = Auth::id();

        $user = User::where('id', $userId)->first();
        $userName = $user->name;
        $unique_code = $user->unique_code;
        $shop = Shops::where('user_id', $userId)->first();
        $credit = Credit::where('shop_id', $shop->id)->first();
        //get transaction for current month
        $month = date('m');
        $year = date('Y');
        $transaction = Transaction::where('credit_id', $credit->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)->get();

        //get total amount from transaction
        $total = 0;
        foreach ($transaction as $item) {
            $total += $item->amount;
        }
        //round to 2 decimal
        $total = round($total, 2);
        $response = [
            'username' => $userName,
            'unique_code' => $unique_code,
            'credit' => $credit,
            'transaction' => $transaction,
            'total' => $total,
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

    public function rewardviews($id) {
        $userId = Auth::id();
        Log::info('User ID: ' . $userId);
    
        $shop = Shops::where('user_id', $userId)->first();
        Log::info('Shop: ' . ($shop ? $shop->id : 'Not found'));
    
        if (!$shop) {
            return response()->json(['error' => 'Shop not found'], 404);
        }
    
        $credit = Credit::where('shop_id', $shop->id)->first();
        Log::info('Credit: ' . ($credit ? $credit->id : 'Not found'));
    
        if (!$credit) {
            return response()->json(['error' => 'Credit not found'], 404);
        }
    
        $stamps = Stamp::where('shop_id', $shop->id)->get();
        Log::info('Stamps count: ' . $stamps->count());
    
        $vouchers = Voucher::where('shop_id', $shop->id)->get();
        Log::info('Vouchers count: ' . $vouchers->count());
    
        $stamp_ids = $stamps->pluck('id')->toArray();
        $user_stamps = User_stamp::whereIn('stamp_id', $stamp_ids)->get();
        Log::info('User Stamps count: ' . $user_stamps->count());
    
        $voucher_ids = $vouchers->pluck('id')->toArray();
        $user_vouchers = User_voucher::whereIn('voucher_id', $voucher_ids)->get();
        Log::info('User Vouchers count: ' . $user_vouchers->count());
    
        $response = [
            'shop' => $shop,
            'credit' => $credit->credit,
            'stamp' => $stamps,
            'stamp_user' => $user_stamps,
            'voucher' => $vouchers,
            'voucher_user' => $user_vouchers,           
        ];
    
        return response()->json(['data' => $response], 200);
    }
    

    public function stampsummary(Request $request){

        $userId = Auth::id();

        $shop = Shops::where('user_id', $userId)->first();

        $stamp = Stamp::where('shop_id', $shop->id)->first();

        // using the $request data get user stamp base on month
        $month = $request->month;

        // get this year
        $year = date('Y');

        //get user stamp using stmap id and month
        
        $userStamp = User_stamp::where('stamp_id', $stamp->id)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)->get();

        $response = [
            'userStamp' => $userStamp
        ];

        return response()->json(['data' => $response], 200);
    }

    //create function for voucher summary

    public function vouchersummary(Request $request){
        $userId = Auth::id();
        $shop = Shops::where('user_id', $userId)->first();
        $voucher = Voucher::where('shop_id', $shop->id)->first();

        $month = $request->month;

        // get this year
        $year = date('Y');
        $userVoucher = User_voucher::where('voucher_id', $voucher->id)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)->get();
        $response = [
            'userVoucher' => $userVoucher
        ];
        return response()->json(['data' => $response], 200);
    }
}
