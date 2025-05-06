<?php

use App\Http\Controllers\Api\Auth\CustomerController;
use App\Http\Controllers\Api\Auth\LenderController;
use App\Http\Controllers\Api\ChatController as ApiChatController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ItemImageController;
use App\Http\Controllers\Api\RentController;
use App\Http\Controllers\Api\ReviewController;
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


Route::get('/items/{itemId}/reviewers', [ReviewController::class, 'customersWhoReviewedItem']);


Route::post('/register-customer', [CustomerController::class, 'register']);
Route::post('/login-customer', [CustomerController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [CustomerController::class, 'logout']);
});


Route::get('/items/{id}', [ItemController::class, 'show']);
Route::get('/items', [ItemController::class, 'index']);


Route::middleware('auth:customer')->prefix('rent')->group(function () {
    Route::post('/request', [RentController::class, 'rentRequest']);
    Route::get('/pending-requests', [RentController::class, 'pendingRequests']);
    Route::delete('/cancel-request/{id}', [RentController::class, 'cancelRequest']);
    Route::post('/', [RentController::class, 'store']);
    Route::post('/session', [RentController::class, 'session']);
    Route::get('/callback/{id}', [RentController::class, 'callback'])->name('api.rent.callback');
    Route::get('/error', [RentController::class, 'error'])->name('api.rent.error');
    Route::get('/customer/profile', [CustomerController::class, 'profile']);
    Route::post('/review', [ReviewController::class, 'store']);
    Route::get('/customer/history', [RentController::class, 'rentHistory']);
});

Route::post('/register-lender', [LenderController::class, 'register']);
Route::post('/login-lender', [LenderController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LenderController::class, 'logout']);
});

Route::middleware('auth:lender')->group(function () {
    Route::get('/requests', [LenderController::class, 'showRequests']);
    Route::post('/requests/{id}/approve', [LenderController::class, 'approveRequest']);
    Route::post('/requests/{id}/reject', [LenderController::class, 'rejectRequest']);
    Route::post('/items/add', [ItemController::class, 'storeitem']);
    Route::put('items/{id}', [ItemController::class, 'update']);
    Route::delete('items/{id}', [ItemController::class, 'destroy']);
    Route::get('/lender/profile', [LenderController::class, 'profile']);
});



Route::middleware('auth:customer,lender')->group(function () {
    Route::get('/conversations/{id}', [ApiChatController::class, 'show']);
    Route::post('/conversations/create', [ApiChatController::class, 'createConversation']);
    Route::post('/conversations/{id}/messages', [ApiChatController::class, 'sendMessage']);
    Route::get('/conversations/{id}/messages', [ApiChatController::class, 'getMessages']);
});
