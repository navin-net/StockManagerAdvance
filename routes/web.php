<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
// use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\{
    AuthController
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
})->name('/'); // Add a name

Route::get('/sa',function(){
    echo "sa";
});




Route::get('/product-alerts', [AuthController::class, 'getAlerts']);

Route::get('/switch-language/{language}', [LanguageController::class, 'switch'])->name('language.switch');


    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); 
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:3,1')
        ->name('login');




require __DIR__ . '/admin.php';