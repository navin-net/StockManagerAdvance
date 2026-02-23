<?php

use App\Http\Controllers\Shop\MainController;
use Illuminate\Support\Facades\Route;




Route::get('/', [MainController::class,'portfolio'])->name('shop.portfolio');


Route::post('/contact', [MainController::class, 'store'])
    ->name('contact.store');

Route::prefix('shop')->group(function () {

// Route::prefix('main')->group(function () {
    // Route::get('/', [MainController::class,'index'])->name('shop.index');
    Route::get('register',[MainController::class, 'register'])->name('shop.register');

});
