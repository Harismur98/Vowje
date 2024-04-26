<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stamp;
use Illuminate\Support\Facades\Auth;
use App\Models\User_stamp;
use Illuminate\Support\Facades\Validator;

class StampController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        // Get stamps only for the specified user ID
        $stamps = Stamp::leftJoin('user_stamps', function ($join) use ($userId) {
            $join->on('stamps.id', '=', 'user_stamps.stamp_id')
                 ->where('user_stamps.user_id', '=', $userId);
        })
        ->with(['user_stamps' => function ($query) use ($userId) {
            $query->where('user_id', $userId)->select('stamp_id', 'collected_stamp');
        }, 'shop'])
        ->get();

        // Transform the data to include collected_stamp for each stamp
        // $stampData = $stamps->map(function ($stamp) {
        //     $stampArray = $stamp->toArray();
        //     $stampArray['collected_stamp'] = $stamp->user_stamps->pluck('collected_stamp')->toArray();
        //     return $stampArray;
        // });

        return response()->json(['data' => $stamps], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reward' => 'required|string',
            'total_required_stamps' => 'required|integer',
            'shop_id' => 'required|integer',
            'expired_date' => 'required|date',
            't&c' => 'required|string', 
            'max_stamp_used' => 'required|integer',
            'total_required_stamps' => 'required|integer',
        ]);
    
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $stamp = Stamp::create($validator->validated());
    
        $response = [
            'message' => 'Successfully create Stamp',
        ];
    
        return response($response, 201);
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
}
