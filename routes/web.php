<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RevenueController;
// Welcome Route
Route::get('/', function () {
    return view('welcome');
});
// Authentication Routes
Route::middleware(['web'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Registration Routes
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});
// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories Management
    Route::resource('categories', CategoryController::class);

    // Foods Management
    Route::resource('foods', FoodController::class);

    // Orders Management
Route::resource('orders', OrderController::class);

// Cập nhật trạng thái đơn hàng
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
     ->name('orders.update-status');

// Cập nhật trạng thái thanh toán đơn hàng
Route::patch('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])
     ->name('orders.update-payment-status');

// Cập nhật thông tin đơn hàng
Route::put('/orders/{order}', [OrderController::class, 'update'])
     ->name('orders.update');

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/revenue/report', [RevenueController::class, 'report']);

});
// Home Route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');
Route::get('/revenue/report', [RevenueController::class, 'report'])->name('revenue.report');
