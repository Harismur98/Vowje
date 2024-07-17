<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stamp;
use Illuminate\Support\Facades\Auth;
use App\Models\User_stamp;
use Illuminate\Support\Facades\Validator;
use App\Models\Shops;
use App\Models\Twoin1Stamp;
class StampController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        // Get stamps only for the specified user ID and is_active = 1
        $stamps = Stamp::leftJoin('user_stamps', function ($join) use ($userId) {
            $join->on('stamps.id', '=', 'user_stamps.stamp_id')
                 ->where('user_stamps.user_id', '=', $userId)
                 ->where('user_stamps.is_used', '=', 0);
        })
        ->where('is_active', 1)
        ->select('stamps.*', 'user_stamps.stamp_id', 'user_stamps.collected_stamp')
        ->with(['shop'])
        ->get();
    

        return response()->json(['data' => $stamps], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $userId = Auth::id();
        // Get shop id from the shop table using user id
        $shop = Shops::where('user_id', $userId)->first();

        $validator = Validator::make($request->all(), [
            'reward' => 'required|string',
            'total_required_stamps' => 'required|integer',
            'expired_date' => 'required|date',
            'terms_and_condition' => 'required|string', 
            'max_stamp_used' => 'required|integer',
            'second_total_required_stamps' => 'integer',
            'second_rewards' => 'string',
        ]);
    
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        if($request->second_rewards != null){
            $stamp = Stamp::create([
                'reward' => $request->reward,
                'total_required_stamps' => $request->total_required_stamps,
                'expired_date' => $request->expired_date,
                't&c' => $request->terms_and_condition,
                'max_stamp_used' => $request->max_stamp_used,
                'shop_id' => $shop->id
            ]);

            $stamp2 = Stamp::create([
                'reward' => $request->second_rewards,
                'total_required_stamps' => $request->second_total_required_stamps,
                'expired_date' => $request->expired_date,
                't&c' => $request->terms_and_condition,
                'max_stamp_used' => $request->max_stamp_used,
                'shop_id' => $shop->id
            ]);

            $twoinone = Twoin1Stamp::create([
                'stamp_id' => $stamp->id,
                'second_stamp_id' => $stamp2->id,
                'is_2in1stamp' => 1
            ]);

            $response = [
                'message' => 'Successfully create 2 Stamp',
            ];
        
            return response($response, 201);

        }
        else{
            $stamp = Stamp::create([
                'reward' => $request->reward,
                'total_required_stamps' => $request->total_required_stamps,
                'expired_date' => $request->expired_date,
                't&c' => $request->terms_and_condition,
                'max_stamp_used' => $request->max_stamp_used,
                'shop_id' => $shop->id
            ]);

            $response = [
                'message' => 'Successfully create Stamp',
            ];
        
            return response($response, 201);
        } 
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stamp = Stamp::findOrFail($id);
        return response()->json(['data' => $stamp], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'reward' => 'required|string',
            'total_used' => 'required|integer',
            'expired_date' => 'required|date',
            't&c' => 'required|string', 
            'max_stamp_used' => 'required|integer',
            'total_required_stamps' => 'required|integer',
        ]);
    
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
    

        $stamp = Stamp::findOrFail($id);

        $userId = auth()->user()->id;
        $shopUserId = $voucher->shop->user_id;

        if ($userId !== $shopUserId) {
            return response()->json(['error' => 'You are not authorized to update this voucher.'], 403);
        }

        $stamp->update($request->all());
    
        $response = [
            'message' => 'Successfully Update Stamp',
        ];
    
        return response($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stamp = Stamp::findOrFail($id);
        $stamp->delete();

        return response()->json(['message' => 'Stamp deleted successfully'], 200);
    }

    public function setStampStatus(Request $request){
        $stamp = Stamp::findOrFail($request->id);

        //update voucher is active
        $stamp->is_active = $request->is_active;
        $stamp->save();
    }
}
