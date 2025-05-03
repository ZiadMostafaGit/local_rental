<?php

use App\Http\Controllers\Api\Auth\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemImageController;
use App\Http\Controllers\LenderController;
use App\Http\Controllers\RentController;
use App\Models\Rent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('register', [CustomerController::class, 'register'])->name('register.form');
    Route::post('register', [CustomerController::class, 'store'])->name('register');

    Route::get('login', [CustomerController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerController::class, 'login'])->name('doLogin');
    Route::post('logout', [CustomerController::class, 'logout'])->name('logout');
});
Route::post('/items/{item}/images', [ItemImageController::class, 'store'])->name('item.images.store');
Route::get('/items/{item}/images', [ItemImageController::class, 'index'])->name('item.images.index');


Route::view('/example-auth','example-auth');

Route::get('/admin/dashboard', [DashboardController::class, 'index']);
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');

Route::post('/rents/request', [RentController::class, 'rentrequest'])->name('rent.request');
Route::get('/lender/requests', [LenderController::class, 'showRequests'])->name('lender.requests');

// Approve rental request
Route::post('/lender/requests/{id}/approve', [LenderController::class, 'approveRequest'])->name('lender.approve');

// Reject rental request
Route::post('/lender/reject-request/{id}', [LenderController::class, 'rejectRequest'])->name('lender.reject');

Route::get('/rent/create', [RentController::class, 'create'])->name('rent.form');
Route::post('/rent', [RentController::class, 'store'])->name('rent.store');

// صفحة الدفع المؤقتة (تشغل الجافاسكربت لبدء جلسة Stripe)
Route::get('/rent/payment', [RentController::class, 'payment'])->name('rent.payment');

// Session Stripe
Route::post('/rent/session', [RentController::class, 'session'])->name('rent.payment.session');

// نجاح الدفع
Route::get('/rent/callback/{id}', [RentController::class, 'callback'])->name('callback');
Route::get('/home', function () {
    return view('home');
})->name('home');
// فشل الدفع
Route::get('/rent/error', function () {
    return 'Payment Failed';
})->name('error');

Route::get('/gettotalitem',[DashboardController::class,'gettotalitem'])->name('gettotalitem');
Route::get('/gettotalcategory',[DashboardController::class,'gettotalcategory'])->name('gettotalcategory');
Route::get('/gettotallender',[DashboardController::class,'gettotallender'])->name('gettotallender');
Route::get('/gettotalcustomer',[DashboardController::class,'gettotalcustomer'])->name('gettotalcustomer');

Route::get('/admin/rents/chart', [DashboardController::class, 'getAllRentsChart'])->name('rents.chart');

Route::get('/admin/reviews/item/chart', [DashboardController::class, 'getReviewsByItemChart'])->name('reviews.item.chart');

Route::get('/register/lender', [LenderController::class, 'showRegisterForm'])->name('lender.register');

Route::post('/register/lender', [LenderController::class, 'store'])->name('lender.store');



Route::get('/lender-dashboard', [LenderController::class, 'dashboard'])->name('lender.dashboard');


require __DIR__.'/auth.php';
