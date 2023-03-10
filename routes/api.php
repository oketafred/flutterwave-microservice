<?php

use App\Http\Controllers\BillerApiController;
use App\Http\Controllers\PaymentWebhookApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentApiController;

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

// The route that the button calls to initialize payment
Route::post('/pay', [PaymentApiController::class, 'initialize'])
    ->name('pay');
Route::post('/webhook/flutterwave', [PaymentWebhookApiController::class, 'webhook'])
    ->name('webhook');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
