<?php

use App\Http\Controllers\Api\Auth\CustomerController;
use App\Http\Controllers\Api\ItemImageController;
use App\Http\Controllers\Api\RentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/items/{item}/images', [ItemImageController::class, 'store']);
Route::get('/items/{item}/images', [ItemImageController::class, 'index']);


Route::post('/register-customer', [CustomerController::class, 'register']);
Route::post('/login-customer', [CustomerController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [CustomerController::class, 'logout']);
});


Route::middleware('auth:customer')->prefix('rent')->group(function () {
    Route::post('/', [RentController::class, 'store']);
    Route::post('/session', [RentController::class, 'session']);
    Route::get('/callback/{id}', [RentController::class, 'callback'])->name('api.rent.callback');
    Route::get('/error', [RentController::class, 'error'])->name('api.rent.error');
});

