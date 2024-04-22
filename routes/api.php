<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\StampController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\VendorAuthController;
use App\Http\Controllers\UserController;
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

Route::post('/vregister', [VendorAuthController::class, 'register']);
Route::post('/vlogin', [VendorAuthController::class, 'login']);

//Home
Route::post('/addVoucher', [AuthController::class, 'addVoucher']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function (){

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/claim/voucher/{id}', [ClaimController::class, 'vclaim']);
    Route::get('/claim/stampPoint/{id}', [ClaimController::class, 'pointCollection']);

    Route::apiResource('/shop', ShopController::class);

    Route::apiResource('/stamp', StampController::class);

    Route::apiResource('/voucher', VoucherController::class);
    
    Route::apiResource('/home', HomeController::class);

    Route::apiResource('/user', UserController::class);
});

