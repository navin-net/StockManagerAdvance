<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Models\Banner;
// use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\{
    AuthController,
    BaseController,
    ProductController,
    ProfileController
};
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::get('/testing',function(){
    return view('testing');
})->name('testing');


Route::get('/slider',function(){
    return view('frontend.index');
});





Route::delete('/product/image/{id}', [ProductController::class, 'deleteImage'])->name('product.image.delete');



Route::get('/product-alerts', [BaseController::class, 'getAlerts']);

// Route::get('/switch-language/{language}', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/lang/{lang}', [LanguageController::class, 'switch']);


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:3,1');




require __DIR__ . '/admin.php';
