<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Admin\ItemImageController;
use App\Http\Controllers\Admin\LenderController as AdminLenderController;
use App\Http\Controllers\Admin\RentController as AdminRentController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
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









Route::get('/admin/dashboard', [DashboardController::class, 'index']);


Route::get('/home', function () {
    return view('home');
})->name('home');
// فشل الدفع
Route::get('/rent/error', function () {
    return 'Payment Failed';
})->name('error');

Route::get('/gettotalitem', [DashboardController::class, 'gettotalitem'])->name('gettotalitem');
Route::get('/gettotalcategory', [DashboardController::class, 'gettotalcategory'])->name('gettotalcategory');
Route::get('/gettotallender', [DashboardController::class, 'gettotallender'])->name('gettotallender');
Route::get('/gettotalcustomer', [DashboardController::class, 'gettotalcustomer'])->name('gettotalcustomer');

Route::get('/admin/rents/chart', [DashboardController::class, 'getAllRentsChart'])->name('rents.chart');

Route::get('/customer-chart-score', [DashboardController::class, 'showReport'])->name('customer.chart.score');
Route::get('/lender-chart-score', [DashboardController::class, 'showLenderReport'])->name('lender.chart.score');

Route::get('/top-rented-items', [DashboardController::class, 'getTopRentedItems']);








Route::get('/set-user/{type}/{id}', function ($type, $id) {
    if ($type === 'customer') {
        session(['customer_id' => $id]);
    } elseif ($type === 'lender') {
        session(['lender_id' => $id]);
    }
    return "Session set for $type with ID $id";
});




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
    Route::get('/index', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::get('/lenders', [AdminLenderController::class, 'index'])->name('lenders.index');
Route::get('pending', [AdminLenderController::class, 'pendingItems'])->name('admin.items.pending');           // عرض العناصر المعلقة
Route::post('{id}/approve', [AdminLenderController::class, 'approve'])->name('admin.items.approve');   // الموافقة على عنصر
Route::post('{id}/reject', [AdminLenderController::class, 'reject'])->name('admin.items.reject');
Route::get('/lenders/create', [AdminLenderController::class, 'create'])->name('lenders.create');
Route::post('/lenders/store', [AdminLenderController::class, 'store'])->name('lenders.store');
Route::get('/lenders/{id}/edit', [AdminLenderController::class, 'edit'])->name('lenders.edit');
Route::put('/lenders/{id}', [AdminLenderController::class, 'update'])->name('lenders.update');
Route::delete('/lenders/{id}', [AdminLenderController::class, 'destroy'])->name('lenders.destroy');
Route::get('/lenders/send-mail/{id}', [AdminLenderController::class, 'send_mail'])->name('lender.send.mail');
Route::post('/send-mail/{id}', [AdminLenderController::class, 'mail'])->name('lender.mail');



Route::prefix('rental')->group(function () {

Route::get('/', [AdminRentController::class, 'index'])->name('rental.index');

Route::get('/create', [AdminRentController::class, 'create'])->name('rental.create');

Route::post('/store', [AdminRentController::class, 'store'])->name('rental.store');

Route::get('/{id}/edit', [AdminRentController::class, 'edit'])->name('rental.edit');

Route::put('/update/{id}', [AdminRentController::class, 'update'])->name('rental.update');

Route::delete('/delete/{id}', [AdminRentController::class, 'destroy'])->name('rental.destroy');
});


Route::prefix('reviews')->group(function () {
Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');

Route::get('/create', [ReviewController::class, 'create'])->name('reviews.create');

Route::post('/store', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');

Route::put('/update/{id}', [ReviewController::class, 'update'])->name('reviews.update');

Route::delete('/delete/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::prefix('customers')->group(function () {

Route::get('/', [AdminCustomerController::class, 'index'])->name('customers.index');

Route::get('/create', [AdminCustomerController::class, 'create'])->name('customers.create');

Route::post('/store', [AdminCustomerController::class, 'store'])->name('customers.store');

Route::get('/{id}/edit', [AdminCustomerController::class, 'edit'])->name('customers.edit');

Route::put('/update/{id}', [AdminCustomerController::class, 'update'])->name('customers.update');

Route::delete('/destroy/{id}', [AdminCustomerController::class, 'destroy'])->name('customers.destroy');

Route::get('/mail/{id}', [AdminCustomerController::class, 'send_mail'])->name('customer.send.mail');
Route::post('/send-mail/{id}', [AdminCustomerController::class, 'mail'])->name('customer.mail');
});

require __DIR__ . '/auth.php';
