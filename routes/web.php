<?php
use Illuminate\Support\Facades\{Mail, Route};
use App\Http\Controllers\{
    LanguageController,
    CookieController,
};
use App\Http\Controllers\Admin\{
    AuthController,
    BaseController,
    ProductController,
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


// Route::get('/',function(){
//     return view('admin-v2.dashbord.index');
// });

Route::get('/users/import', [BaseController::class, 'showImportForm'])
    ->name('users.import.form');
Route::post('/users/import', [BaseController::class, 'import'])->name('users.import');


Route::delete('/product/image/{id}', [ProductController::class, 'deleteImage'])->name('product.image.delete');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:3,1');



Route::post('/cookie/accept', [CookieController::class, 'accept'])
    ->name('cookie.accept');
Route::get('/lang/{lang}', [LanguageController::class, 'switch']);
Route::get('/product-alerts', [BaseController::class, 'getAlerts']);


Route::get('/test-mail', function () {
    Mail::raw('Test Email From Laravel', function ($message) {
        $message->to('naaven60@gmail.com')
                ->subject('Laravel Mail Test');
    });

    return 'Mail Sent!';
});





require __DIR__ . '/admin.php';
require __DIR__ . '/shop.php';
