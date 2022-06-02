<?php

use App\Module\Customer\Controllers\OrderController;
use App\Module\MobileAuth\Controllers\SpaLoginPincodeController;
use App\Module\PasswordAuth\Controllers\SpaLoginPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/password-login', [SpaLoginPasswordController::class, 'authenticate']);
Route::post('/password-logout', [SpaLoginPasswordController::class, 'logout']);

Route::post('/login-phone', [SpaLoginPincodeController::class, 'loginPhone']);
Route::post('/login-pincode', [SpaLoginPincodeController::class, 'loginPincode']);
Route::post('/logout', [SpaLoginPincodeController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/new-order', [OrderController::class, 'newOrder']);
    Route::get('/order-status-list', [OrderController::class, 'getOrderStatusList']);
    Route::get('/view-order/{id}', [OrderController::class, 'viewOrder']);
    Route::post('/cancel-order/{id}', [OrderController::class, 'cancelOrder']);
    Route::get('/load-order/{id}', [OrderController::class, 'loadOrder']);
    Route::post('/update-order/{id}', [OrderController::class, 'updateOrder']);
});
