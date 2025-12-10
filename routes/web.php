<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\XenditWebhookController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardUserController;

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
    return view('home');
})->name('home');



Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showlog'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    // Register
    Route::get('/register', [AuthController::class, 'showregis'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


Route::middleware(['session.expired', 'auth'])->group(function () {
    Route::get('/settings', [AuthController::class, 'editAccount'])->name('account.edit');
    Route::post('/settings', [AuthController::class, 'updateAccount'])->name('account.update'); // POST
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['session.expired', 'auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [DashboardUserController::class, 'index'])
        ->name('dashboard.user');
    Route::get('/user/orders', [PesananController::class, 'userOrders'])
        ->name('order.user');
    Route::get('/user/items', [ItemController::class, 'userIndex'])->name('item.user');
    Route::get('/user/items/search', [ItemController::class, 'userSearch'])->name('item.user.search');
});

Route::middleware(['session.expired', 'auth', 'role:admin'])->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
    // users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
    Route::get('/users/show/{id}', [UserController::class, 'show'])->name('users.show');
    // Item
    Route::get('/laundry', [ItemController::class, 'index'])->name('item.index');
    Route::get('/laundry/search', [ItemController::class, 'search'])->name('item.search');
    Route::get('/laundry/create', [ItemController::class, 'create'])->name('item.create');
    Route::post('/laundry/store', [ItemController::class, 'store'])->name('item.store');
    Route::get('/laundry/edit/{id}', [ItemController::class, 'edit'])->name('item.edit');
    Route::post('/laundry/update/{id}', [ItemController::class, 'update'])->name('item.update');
    Route::delete('/laundry/delete/{id}', [ItemController::class, 'destroy'])->name('item.delete');
    Route::get('/laundry/show/{id}', [ItemController::class, 'show'])->name('item.show');
    // Order
    Route::get('/order', [PesananController::class, 'index'])->name('order.index');
    Route::get('/order/{id}/edit', [PesananController::class, 'edit'])->name('order.edit');
    Route::post('/order/{id}/update', [PesananController::class, 'update'])->name('order.update');

    Route::get('/order/export/pdf', [PesananController::class, 'exportPDF'])->name('order.export.pdf');
    Route::get('/order/export/excel', [PesananController::class, 'exportExcel'])->name('order.export.excel');


});


Route::middleware(['session.expired', 'auth'])->group(function () {
    Route::get('/order/new', [PesananController::class, 'create'])->name('order.create');
    Route::post('/order/store', [PesananController::class, 'store'])->name('order.store');
    Route::get('/orders/proses/{id}', [PesananController::class, 'proses'])->name('order.proses');
    Route::get('/order/{id}/payment', [PesananController::class, 'payment'])->name('order.payment');
    Route::get('/order/search', [PesananController::class, 'search'])->name('order.search');
    Route::get('/order/{id}', [PesananController::class, 'show'])->name('order.show');
    Route::get('/order/{pesanan}/checkout', [PaymentController::class, 'checkout'])->name('order.checkout');
    Route::post('/order/{pesanan}/checkout', [PaymentController::class, 'processPayment'])->name('order.processPayment');
    Route::get('/order/{pesanan}/success', [PaymentController::class, 'success'])->name('order.success');
    Route::get('/order/{pesanan}/failed', [PaymentController::class, 'failed'])->name('order.failed');

});

Route::post('/xendit/webhook', [XenditWebhookController::class, 'handle'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

