<?php

use App\Http\Controllers\SpaLoginController;
use App\Module\Customer\Controllers\OrderController;
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

Route::post('/login', [SpaLoginController::class, 'authenticate']);
Route::post('/logout', [SpaLoginController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/new-order', [OrderController::class, 'newOrder']);
    Route::get('/order-status-list', [OrderController::class, 'getOrderStatusList']);
    Route::get('/view-order/{id}', [OrderController::class, 'viewOrder']);
    Route::post('/cancel-order/{id}', [OrderController::class, 'cancelOrder']);
});
