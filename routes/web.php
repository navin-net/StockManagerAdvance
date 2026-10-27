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
use App\Http\Controllers\Api\Shop\CartController;



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
Route::get('/cartas', [CartController::class, 'index']);

// Route::get('/',function(){
//     return view('admin-v2.dashbord.index');
// });
Route::get('/checkout', function () {
    return view('login');
});


Route::middleware(['auth', 'admin', 'can:manage-users'])->group(function () {
    Route::get('/users/import', [BaseController::class, 'showImportForm'])->name('users.import.form');
    Route::post('/users/import', [BaseController::class, 'import'])->name('users.import');
});
Route::delete('/product/image/{id}', [ProductController::class, 'deleteImage'])
    ->middleware(['auth', 'admin'])->name('product.image.delete');
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:3,1');



Route::post('/cookie/accept', [CookieController::class, 'accept'])
    ->name('cookie.accept');
Route::get('/lang/{lang}', [LanguageController::class, 'switch']);
Route::get('/product-alerts', [BaseController::class, 'getAlerts'])->middleware(['auth', 'admin']);

Route::get('/customer-display', fn () => view('admin.pos.customer-display'))
     ->middleware('auth')->name('customer.display');


require __DIR__ . '/admin.php';
//require __DIR__ . '/shop.php';
