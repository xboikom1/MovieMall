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
Route::match(['GET', 'POST'], '/cart/details', [CartController::class, 'details'])->name('cart.details');
Route::post('/cart/edit-begin', [CartController::class, 'editBegin'])->name('cart.edit-begin');
Route::post('/cart/edit-end', [CartController::class, 'editEnd'])->name('cart.edit-end');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/movies', [App\Http\Controllers\MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/movies/{id}/booked-seats', [MovieController::class, 'bookedSeats'])->whereNumber('id')->name('movies.booked-seats');
Route::get('/souvenirs', [SouvenirController::class, 'index'])->name('souvenirs.index');
Route::get('/souvenirs/{slug}', [App\Http\Controllers\SouvenirController::class, 'show'])->name('souvenirs.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/item/remove', [CartController::class, 'removeItem'])->name('cart.item.remove');
Route::post('/cart/item/quantity', [CartController::class, 'updateQuantity'])->name('cart.item.quantity');
Route::post('/cart/item/add', [CartController::class, 'addItem'])->name('cart.item.add');
Route::post('/cart/item/edit-begin', [CartController::class, 'editBeginRedirect'])->name('cart.item.edit-begin');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/submit', [CheckoutController::class, 'submit'])->name('checkout.submit');
Route::get('/confirmation', function () { return view('confirmation'); })->name('confirmation');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('profile.address.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'avatarUpdate'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'avatarDelete'])->name('profile.avatar.delete');
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
});

Route::prefix('xj7qr2')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->middleware('throttle:5,1');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/product/new', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'create'])->name('product.new');
        Route::post('/product/new', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'store'])->name('product.store');

        Route::get('/product/edit/{type}/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'edit'])
            ->whereNumber('id')
            ->name('product.edit');
        Route::put('/product/edit/{type}/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'update'])
            ->whereNumber('id')
            ->name('product.update');
        Route::delete('/product/{type}/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'destroy'])
            ->whereNumber('id')
            ->name('product.destroy');

        Route::post('/product/image/{type}/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'addImage'])
            ->whereNumber('id')
            ->name('product.image.add');
        Route::delete('/product/image/{type}/{imageId}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'removeImage'])
            ->whereNumber('imageId')
            ->name('product.image.remove');
        Route::put('/product/image/{type}/{imageId}/primary', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'setPrimaryImage'])
            ->whereNumber('imageId')
            ->name('product.image.primary');
        Route::get('/orders', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'orders'])->name('orders');
        Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');
    });
});

require __DIR__.'/auth.php';
