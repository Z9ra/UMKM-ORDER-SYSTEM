<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/export-excel', [OrderController::class, 'exportExcel'])
    ->middleware(['auth'])
    ->name('orders.exportExcel');

Route::get('/export-pdf', [OrderController::class, 'exportPdf'])
    ->middleware(['auth'])
    ->name('orders.exportPdf');

// Order routes
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::get('/input-order', [OrderController::class, 'create'])
    ->middleware(['auth'])
    ->name('orders.create');

Route::get('/dashboard', [OrderController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
    ->middleware(['auth'])
    ->name('orders.updateStatus');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
