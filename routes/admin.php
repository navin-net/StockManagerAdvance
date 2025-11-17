<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AuthController,
    SalesController,
    PurchasesController,
    BillerController,
    BrandController,
    CategoriesController,
    UserController,
    GroupsController,
    ProductController,
    SubCategoryController,
    WarehouseController,
    QualitysController,
    UnitController
};

Route::prefix('admin')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware('auth')->get('/', [AuthController::class, 'dashboard'])->name('dashboard');
    //Mengemnt Profile
    Route::get('/profile/{id}', [AuthController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [AuthController::class, 'update'])->name('profile.update');
    //Products
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('/products/getData', [ProductController::class, 'getData'])->name('products.getData');
    Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/subcategories', [ProductController::class, 'getSubCategories'])->name('products.subcategories');
    Route::delete('/products/images/{id}', [ProductController::class, 'removeImage'])->name('products.images.remove');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    //SALES
    Route::resource('sales', SalesController::class)->except(['show']);
    Route::get('/sales/getData', [SalesController::class, 'getData'])->name('sales.getData');
    Route::post('/sales/bulk-delete', [SalesController::class, 'bulkDelete'])->name('sales.bulkDelete');
    Route::get('/sales/export', [SalesController::class, 'export'])->name('sales.export');
    Route::get('/sales/detail/{id}', [SalesController::class, 'show'])->name('sales.show');
    //Purchases
    Route::resource('purchases', PurchasesController::class)->except(['show']);
    Route::post('purchases/bulk-delete', [PurchasesController::class, 'bulkDelete'])->name('purchases.bulkDelete');
    Route::get('purchases/export', [PurchasesController::class, 'export'])->name('purchases.export');
    Route::get('/purchases/getData', [PurchasesController::class, 'getData'])->name('purchases.getData');
    Route::get('/purchases/show', [PurchasesController::class, 'show'])->name('purchases.show');
    // Megement Permission
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('billers', BillerController::class)->except(['show']);
    Route::get('/billers/{id}/users', [BillerController::class, 'listUsers'])->name('billers.users');
    Route::get('/billers/{id}/users/add', [BillerController::class, 'addUser'])
        ->name('billers.users.add');
    Route::post('/billers/{id}/users/store', [BillerController::class, 'storeUser'])
        ->name('billers.users.store');
    Route::get('/billers/{id}/users/edit', [BillerController::class, 'editUser'])
        ->name('billers.users.edit');
    Route::delete('/billers/users/{id}/delete', [BillerController::class, 'deleteUser'])->name('billers.users.delete');
    // Setting system
    Route::prefix('system_settings')->group(function () {
        Route::resource('/groups',GroupsController::class)->except(['show']);
        Route::resource('/brands',BrandController::class)->except(['show']);
        Route::resource('/categories',CategoriesController::class)->except(['show']);
        Route::resource('/sub_category',SubCategoryController::class)->except(['show']);        
        Route::resource('/units',UnitController::class)->except(['show']);
        Route::resource('/warehouse',WarehouseController::class)->except(['show']);
        Route::resource('/qualitys', QualitysController::class)->except(['show']);
    });


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

