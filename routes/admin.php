<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AuthController, BillerController, BrandController, CategoriesController, CustomerController, GroupsController, PortfolioController, PosController, ProductController, ProfileController, PurchasesController, QualitysController, ReportsController, SalesController, ShopController, SubCategoryController, UnitController, UserController, WarehouseController};
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/', [AuthController::class, 'dashboard'])->name('admin.dashboard');

    //Management Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload-avatar');
    Route::put('/profile/updateInformation', [ProfileController::class, 'updateInformation'])->name('profile.updateInformation');
    //Products
    Route::resource('products', ProductController::class)->except(['show']);
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/getData', [ProductController::class, 'getData'])
            ->name('getData');
        Route::get('/show/{id}', [ProductController::class, 'show'])
            ->name('show');
        Route::get('/subcategories/{category}', [ProductController::class, 'getSubCategories'])
            ->name('subcategories');
        Route::delete('/images/{id}', [ProductController::class, 'removeImage'])
            ->name('images.remove');
        Route::get('/export', [ProductController::class, 'export'])
            ->name('export');
        Route::get('/import', [ProductController::class, 'showImportForm'])
            ->name('import.form');
        Route::post('/import', [ProductController::class, 'import'])
            ->name('import');
        Route::get('/code-label', [ProductController::class, 'barcodelabel'])
            ->name('barcodelabel');
        Route::get('/adjustment', [ProductController::class, 'adjustment'])
            ->name('adjustment');
    });

    //SALES
    Route::resource('sales', SalesController::class)->except(['show']);
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/getData', [SalesController::class, 'getData'])->name('getData');
        Route::get('/getDataPos', [SalesController::class, 'getDataPos'])->name('getDataPos');
        Route::get('/pos', [SalesController::class, 'pos'])->name('pos');
        Route::post('/bulk-delete', [SalesController::class, 'bulkDelete'])->name('bulkDelete');
        Route::get('/export', [SalesController::class, 'export'])->name('export');
        Route::get('/detail/{id}', [SalesController::class, 'show'])->name('show');
        Route::get('/payments/{id}', [SalesController::class, 'payments'])->name('payments');
        Route::post('/payment/store', [SalesController::class, 'storePayment'])->name('storePayment');
        Route::get('/listPayments/{id}', [SalesController::class, 'listPayments'])->name('listPayments');
    });
    //Purchases
    Route::resource('purchases', PurchasesController::class)->except(['show']);
    Route::prefix('purchases')->group(function () {
        Route::post('/bulk-delete', [PurchasesController::class, 'bulkDelete'])->name('purchases.bulkDelete');
        Route::get('/export', [PurchasesController::class, 'export'])->name('purchases.export');
        Route::get('/show', [PurchasesController::class, 'show'])->name('purchases.show');
        Route::get('/getData', [PurchasesController::class, 'getData'])->name('purchases.getData');
    });
    // Megement Permission
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('billers', BillerController::class)->except(methods: ['show']);
    Route::prefix('billers')->group(function () {
        Route::get('/{id}/users', [BillerController::class, 'listUsers'])->name('billers.users');
        Route::get('/{id}/users/add', [BillerController::class, 'addUser'])
            ->name('billers.users.add');
        Route::post('/{id}/users/store', [BillerController::class, 'storeUser'])
            ->name('billers.users.store');
        Route::get('/{id}/users/edit', [BillerController::class, 'editUser'])
            ->name('billers.users.edit');
        Route::put('/{id}/users/update', [BillerController::class, 'updateUser'])
            ->name('billers.users.update');
        Route::delete('/users/{id}/delete', [BillerController::class, 'deleteUser'])->name('billers.users.delete');
    });

    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::prefix('customers')->group(function () {
        Route::get('/{id}/users', [CustomerController::class, 'listUsers'])->name('customers.users');
        Route::get('/{id}/users/add', [CustomerController::class, 'addUser'])
            ->name('customers.users.add');
        Route::post('/{id}/users/store', [CustomerController::class, 'storeUser'])
            ->name('customers.users.store');
        Route::get('/{id}/users/edit', [CustomerController::class, 'editUser'])
            ->name('customers.users.edit');
        Route::delete('/users/{id}/delete', [CustomerController::class, 'deleteUser'])->name('customers.users.delete');
    });
    // Setting system
    Route::prefix('system_settings')->group(function () {
        Route::resource('/groups', GroupsController::class)->except(['show']);
        Route::post('groups/bulkDelete', [GroupsController::class, 'bulkDelete'])->name('groups.bulkDelete');
        Route::resource('/brands', BrandController::class)->except(['show']);
        Route::post('brands/bulkDelete', [BrandController::class, 'bulkDelete'])->name('brands.bulkDelete');
        Route::resource('/categories', CategoriesController::class)->except(['show']);
        Route::resource('/sub_category', SubCategoryController::class)->except(['show']);
        Route::resource('/units', UnitController::class)->except(['show']);
        Route::post('units/bulk_delete', [UnitController::class, 'bulkDelete'])->name('units.bulkDelete');
        Route::resource('/warehouse', WarehouseController::class)->except(['show']);
        Route::resource('/qualitys', QualitysController::class)->except(['show']);
    });

    Route::prefix('reports')->group(function(){
        Route::get('/',[ReportsController::class,'index'])->name('reports');
        Route::get('/daily-sales',[ReportsController::class,'daily_sales'])->name('reports.daily-sales');
        Route::get('/monthly-sales',[ReportsController::class,'monthly_sales'])->name('reports.monthly-sales');

    });

    Route::prefix('shop')->group(function () {
        Route::get('settings', [ShopController::class, 'index'])->name('settings');
        Route::post('settings', [ShopController::class, 'update'])->name('settings.update');
        Route::get('banners', [ShopController::class, 'banners'])->name('banners');
        Route::post('/banners/update', [ShopController::class, 'bannersUpdate'])
            ->name('banners.update');
        Route::resource('portfolio', PortfolioController::class)->except(['show']);
    });

    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('open-register', [PosController::class, 'openRegister'])
            ->name('open-register');
        Route::post('open-register', [PosController::class, 'storeOpenRegister'])
            ->name('open-register.store');
        Route::post('close-register', [PosController::class, 'closeRegister'])
            ->name('close-register');
        Route::middleware('register.open')->group(function () {
            Route::get('/', [PosController::class, 'index'])
                ->name('index');
        });
        Route::post('/store', [PosController::class, 'store'])->name('store');
        Route::get('/receipt/{sale}',  [PosController::class, 'receipt'])->name('receipt');
        Route::get('/customer-display', function () {
            return view('customer-display');
        });
    });

});

