<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CoopController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk menampilkan halaman filter dan list
    Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');

    // Route untuk mendownload Excel berdasarkan filter
    Route::get('/products/download-excel', [ProductController::class, 'downloadExcel'])->name('products.downloadExcel');

    // Route untuk menambahkan dari create or edit product
    Route::post('product/store-coop', [ProductController::class, 'storeCoop'])->name('product.storeCoop');
    Route::post('product/store-category', [ProductController::class, 'storeCategory'])->name('product.storeCategory');
    Route::post('product/store-brand', [ProductController::class, 'storeBrand'])->name('product.storeBrand');
    Route::post('product/store-color', [ProductController::class, 'storeColor'])->name('product.storeColor');
    Route::post('product/store-size', [ProductController::class, 'storeSize'])->name('product.storeSize');
    Route::post('product/store-supplier', [ProductController::class, 'storeSupplier'])->name('product.storeSupplier');


    Route::resource('products', ProductController::class);
    Route::resource('coops', CoopController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('suppliers', SupplierController::class);





    // Settings (Hanya untuk admin)
    Route::middleware('admin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::resource('users', UserController::class);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
