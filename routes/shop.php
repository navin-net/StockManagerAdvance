<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\{MainController, ShopController};
use App\Http\Controllers\Shop\Auth\ForgotPasswordController;





Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.send');

Route::get('/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp.form');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');


Route::get('/portfolio', [MainController::class, 'portfolio'])->name('shop.portfolio');


Route::post('/contact', [MainController::class, 'store'])
    ->name('contact.store');


Route::get('/', [ShopController::class, 'index'])->name('shop.index');

// Shop Routes Group
Route::prefix('shop')->name('shop.')->group(function () {

    Route::get('/', [ShopController::class, 'index'])->name('home');

    Route::get('/products', [ShopController::class, 'products'])->name('products');
    Route::get('/products/{code}', [ShopController::class, 'show']);
    Route::get('/cart', [ShopController::class, 'cart'])->name('cart');
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::get('/about-us',[ShopController::class, 'about_us'])->name('about_us');
    Route::get('/contact-us', [ShopController::class, 'contact_us'])->name('contact_us');
    Route::get('/wishlist',[ShopController::class, 'wishlist'])->named('wishlist');



});
