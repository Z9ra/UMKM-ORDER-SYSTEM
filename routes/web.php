<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

Route::get('/', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::get('/dashboard', [OrderController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
    ->middleware(['auth'])
    ->name('orders.updateStatus');

// Profile routes (dari Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
