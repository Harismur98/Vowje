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
use App\Http\Controllers\Credits;
use App\Http\Controllers\MerchentController;

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

    Route::get('/claim/voucher/{id}/{userId}', [ClaimController::class, 'vclaim']);
    Route::get('/claim/stamp/{id}/{userId}', [ClaimController::class, 'sclaim']);
    Route::post('/claim/stampPoint/', [ClaimController::class, 'pointCollection']);

    Route::apiResource('/shop', ShopController::class);

    Route::apiResource('/stamp', StampController::class);
    Route::post('/stamp/setstampstatus', [StampController::class, 'setStampStatus']);

    Route::apiResource('/voucher', VoucherController::class);
    Route::post('/stamp/setvouchertatus', [VoucherController::class, 'setVoucherStatus']);
    
    Route::apiResource('/home', HomeController::class);

    Route::apiResource('/user', UserController::class);

    Route::apiResource('/credit', Credits::class);

    Route::apiResource('/merchant', MerchentController::class);
    Route::get('/merchant/rewardviews/{id}', [MerchentController::class, 'rewardviews']);
    Route::post('/merchant/stampsummary', [MerchentController::class, 'stampsummary']);
    Route::post('/merchant/vouchersummary', [MerchentController::class, 'vouchersummary']);
    
    Route::post('/vchangepass', [VendorAuthController::class, 'changePassword']);

    Route::get('/storage/{path}', [ImageController::class, 'serve'])
    ->where('path', '.*');
});

