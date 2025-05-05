<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Admin\ItemImageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
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

Route::get('/customer/register', [CustomerController::class, 'showRegistrationForm'])->name('customer.register.form');
Route::post('/customer/register', [CustomerController::class, 'register'])->name('customer.register');
// عرض صفحة تسجيل الدخول
Route::get('/customer/login', [CustomerController::class, 'showLoginForm'])->name('customer.login');

// تنفيذ تسجيل الدخول
Route::post('/customer/login', [CustomerController::class, 'login'])->name('customer.doLogin');







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

Route::get('/customer-chart-score', [DashboardController::class, 'showReport'])->name('customer.chart.score');

Route::get('/top-rented-items', [DashboardController::class, 'getTopRentedItems']);


Route::get('/register/lender', [LenderController::class, 'showRegisterForm'])->name('lender.register');

Route::post('/register/lender', [LenderController::class, 'store'])->name('lender.store');



Route::get('/lender-dashboard', [LenderController::class, 'dashboard'])->name('lender.dashboard');


Route::get('/set-user/{type}/{id}', function ($type, $id) {
    if ($type === 'customer') {
        session(['customer_id' => $id]);
    } elseif ($type === 'lender') {
        session(['lender_id' => $id]);
    }
    return "Session set for $type with ID $id";
});

Route::get('/chat/{conversationId}', [ChatController::class, 'show'])->name('chat.show');
Route::post('/conversations/create', [ChatController::class, 'createConversation'])->name('conversations.create');
Route::post('/conversations/{conversationId}/messages', [ChatController::class, 'sendMessage']);
Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'getMessages']);



Route::prefix('items')->group(function () {
    Route::get('/index', [AdminItemController::class, 'index'])->name('items.index');
    Route::get('/create', [AdminItemController::class, 'create'])->name('items.create');
    Route::post('/', [AdminItemController::class, 'store'])->name('items.store');
    Route::get('/{item}', [AdminItemController::class, 'show'])->name('items.show');
    Route::get('/{item}/edit', [AdminItemController::class, 'edit'])->name('items.edit');
    Route::put('/{item}', [AdminItemController::class, 'update'])->name('items.update');
    Route::delete('/{item}', [AdminItemController::class, 'destroy'])->name('items.destroy');
    Route::post('/items/{item}/add-category', [AdminItemController::class, 'addCategory'])->name('items.addCategory');
    Route::get('items/{item}/images', [ItemImageController::class, 'index'])->name('item.images.index');
Route::post('items/{item}/images', [ItemImageController::class, 'store'])->name('item.images.store');

});


Route::prefix('category')->group(function () {
    Route::get('/index', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/{item}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('/{item}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/{item}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/{item}', [CategoryController::class, 'destroy'])->name('category.destroy');
});
require __DIR__.'/auth.php';
