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


Route::get('/portfolio', [MainController::class,'portfolio'])->name('shop.portfolio');


Route::post('/contact', [MainController::class, 'store'])
    ->name('contact.store');


Route::get('/', [ShopController::class,'index'])->name('shop.index');
Route::prefix('shop')->group(function () {

// Route::prefix('main')->group(function () {
    Route::get('register',[MainController::class, 'register'])->name('shop.register');

});
