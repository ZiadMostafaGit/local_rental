<?php

use App\Http\Controllers\Api\Auth\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemImageController;
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


Route::get('/rent/create', [RentController::class, 'create'])->name('rent.form');
Route::post('/rent', [RentController::class, 'store'])->name('rent.store');

// صفحة الدفع المؤقتة (تشغل الجافاسكربت لبدء جلسة Stripe)
Route::get('/rent/payment', [RentController::class, 'payment'])->name('rent.payment');

// Session Stripe
Route::post('/rent/session', [RentController::class, 'session'])->name('rent.payment.session');

// نجاح الدفع
Route::get('/rent/callback/{id}', [RentController::class, 'callback'])->name('callback');
Route::get('/home', function () {
    return 'Payment Success';
})->name('home');
// فشل الدفع
Route::get('/rent/error', function () {
    return 'Payment Failed';
})->name('error');


require __DIR__.'/auth.php';
