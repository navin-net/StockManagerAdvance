<?php

use App\Http\Controllers\Admin\PortfolioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AuthController,
    ProfileController,
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
    CustomerController,
    PosController,
    ShopController,
    UnitController
};
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/', [AuthController::class, 'dashboard'])->name('admin.dashboard');

    //Mengemnt Profile
    Route::get('/profile', [AuthController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AuthController::class, 'update'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload-avatar');
    Route::put('/profile/updateInformation', [ProfileController::class, 'updateInformation'])->name('profile.updateInformation');
    //Products
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('/products/getData', [ProductController::class, 'getData'])->name('products.getData');
    Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/subcategories/{category}', [ProductController::class, 'getSubCategories'])->name('products.subcategories');
    Route::delete('/products/images/{id}', [ProductController::class, 'removeImage'])->name('products.images.remove');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    // Route::get('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products/import', [ProductController::class, 'showImportForm'])
        ->name('products.import.form');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');


    Route::get('products/code-label', [ProductController::class, 'barcodelabel'])->name('products.barcodelabel');
    Route::get('products/adjustment', [ProductController::class, 'adjustment'])->name('products.adjustment');

    //SALES
    Route::resource('sales', SalesController::class)->except(['show']);
    Route::get('/sales/getData', [SalesController::class, 'getData'])->name('sales.getData');
    Route::get('/sales/getDataPos',[SalesController::class, 'getDataPos'])->name('sales.getDataPos');
    Route::get('/sales/pos', [SalesController::class, 'pos'])->name('sales.pos');

    Route::post('/sales/bulk-delete', [SalesController::class, 'bulkDelete'])->name('sales.bulkDelete');
    Route::get('/sales/export', [SalesController::class, 'export'])->name('sales.export');
    Route::get('/sales/detail/{id}', [SalesController::class, 'show'])->name('sales.show');
    Route::get('/sales/payments/{id}', [SalesController::class, 'payments'])->name('sales.payments');
    Route::post('/sales/payment/store', [SalesController::class, 'storePayment'])->name('sales.storePayment');
    Route::get('/sales/listPayments/{id}', [SalesController::class, 'listPayments'])->name('sales.listPayments');
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

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

