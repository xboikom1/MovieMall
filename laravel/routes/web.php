<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SouvenirController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/movies', [App\Http\Controllers\MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/souvenirs', [SouvenirController::class, 'index'])->name('souvenirs.index');
Route::get('/souvenirs/{slug}', [App\Http\Controllers\SouvenirController::class, 'show'])->name('souvenirs.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('profile.address.destroy');
});

require __DIR__.'/auth.php';
