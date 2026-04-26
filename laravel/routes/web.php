<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SouvenirController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/sync', [CartController::class, 'sync'])->name('cart.sync');
Route::post('/cart/details', [CartController::class, 'details'])->name('cart.details');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/movies', [App\Http\Controllers\MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/souvenirs', [SouvenirController::class, 'index'])->name('souvenirs.index');
Route::get('/souvenirs/{slug}', [App\Http\Controllers\SouvenirController::class, 'show'])->name('souvenirs.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/submit', [CheckoutController::class, 'submit'])->name('checkout.submit');
Route::get('/confirmation', function () { return view('confirmation'); })->name('confirmation');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('profile.address.destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login']);
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/product/edit/{type}/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'edit'])
            ->whereNumber('id')
            ->name('product.edit');
        Route::put('/product/edit/{type}/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'update'])
            ->whereNumber('id')
            ->name('product.update');
        Route::delete('/product/{type}/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'destroy'])
            ->whereNumber('id')
            ->name('product.destroy');
        Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');
    });
});

require __DIR__.'/auth.php';
