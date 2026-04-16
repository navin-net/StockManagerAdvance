<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController, MainController};
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Shop\{CartController, CheckoutController};


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/products/search', [MainController::class, 'search']);
Route::get('/getShops',[MainController::class, 'index']);
Route::get('/getdata',[MainController::class, 'getdata']);
Route::get('/getProducts',[MainController::class, 'getProducts']);
Route::get('brands/{id}', [MainController::class, 'show']);
Route::get('/getCategories', [MainController::class, 'getCategories']);
Route::get('/getBrands', [MainController::class, 'getBrands']);
Route::get('/slides', [MainController::class, 'slides']);
Route::get('/getTrending',[MainController::class, 'getTrending']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);


Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/items', [CartController::class, 'store']);
    Route::put('/items/{cartItem}', [CartController::class, 'update']);
    Route::delete('/items/{cartItem}', [CartController::class, 'destroy']);
    Route::delete('/', [CartController::class, 'clear']);
});


Route::prefix('shop')->middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/checkout', [CheckoutController::class, 'checkout']);


    Route::post('/logout', [AuthController::class, 'logout']);
});



