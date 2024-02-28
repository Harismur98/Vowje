<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ClaimController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Home
Route::post('/addVoucher', [AuthController::class, 'addVoucher']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function (){

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/claim/voucher/{id}', [ClaimController::class, 'vclaim']);

    Route::apiResource('/shop', ShopController::class);

    Route::apiResource('/voucher', VoucherController::class);
    
    Route::apiResource('home', HomeController::class);
});

