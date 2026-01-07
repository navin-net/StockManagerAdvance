<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Models\Banner;
// use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\{
    AuthController,
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
    return view('index');
})->name('/');




// Route::get('/pos', function () {
//     return view('testing1');
// })->name('pos');



Route::get('/banner/{id}', function ($id) {
    return Banner::findByIdAndName($id);
});

Route::put('/banner/{id}', function (Request $request, $id) {
    $updatedBanner = Banner::updateByIdAndName($id,  $request->all());

    return response()->json([
        'message' => 'Banner updated successfully!',
        'data' => $updatedBanner
    ]);
});



Route::get('/pos',[ProfileController::class, 'pos']);

Route::get('/product-alerts', [AuthController::class, 'getAlerts']);

// Route::get('/switch-language/{language}', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/lang/{lang}', [LanguageController::class, 'switch']);


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:3,1');




require __DIR__ . '/admin.php';
