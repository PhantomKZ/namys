<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\TypeController as AdminTypeController;
use App\Http\Controllers\Admin\ColorController as AdminColorController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [SiteController::class, 'index'])->name('home');
Route::post('/subscribe', [SiteController::class, 'subscribe'])->name('subscribe');
Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('index');
    Route::get('/novelty', [CatalogController::class, 'novelty'])->name('novelty');
    Route::get('/limited', [CatalogController::class, 'limited'])->name('limited');
});
Route::prefix('product')->name('product.')->group(function () {
    Route::get('{id}', [ProductController::class, 'show'])->name('show');
    Route::post('{id}/add-to-favorites', [ProductController::class, 'addToFavorites'])->name('addToFavorites');
    Route::post('{id}/remove-from-favorites', [ProductController::class, 'removeFromFavorites'])->name('removeFromFavorites');
});
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::get('/orders', [ProfileController::class, 'orders'])->name('orders');
});
Route::prefix('collection')->name('collection.')->group(function () {
    Route::get('/', [CollectionController::class, 'index'])->name('index');
    Route::get('/{id}', [CollectionController::class, 'show'])->name('show');
});
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/add-all', [CartController::class, 'addAll'])->name('addAll');
    Route::delete('/remove', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('profile');

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [AdminCategoryController::class, 'index'])->name('index');
            Route::get('/create', [AdminCategoryController::class, 'create'])->name('create');
            Route::post('/', [AdminCategoryController::class, 'store'])->name('store');
            Route::get('/{slug}', [AdminCategoryController::class, 'show'])->name('show');
            Route::get('/{slug}/edit', [AdminCategoryController::class, 'edit'])->name('edit');
            Route::put('/{slug}', [AdminCategoryController::class, 'update'])->name('update');
            Route::delete('/{slug}', [AdminCategoryController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', [AdminBrandController::class, 'index'])->name('index');
            Route::get('/create', [AdminBrandController::class, 'create'])->name('create');
            Route::post('/', [AdminBrandController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminBrandController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminBrandController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminBrandController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminBrandController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('types')->name('types.')->group(function () {
            Route::get('/', [AdminTypeController::class, 'index'])->name('index');
            Route::get('/create', [AdminTypeController::class, 'create'])->name('create');
            Route::post('/', [AdminTypeController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminTypeController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminTypeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminTypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminTypeController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('colors')->name('colors.')->group(function () {
            Route::get('/', [AdminColorController::class, 'index'])->name('index');
            Route::get('/create', [AdminColorController::class, 'create'])->name('create');
            Route::post('/', [AdminColorController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminColorController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminColorController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminColorController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminColorController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('materials')->name('materials.')->group(function () {
            Route::get('/', [AdminMaterialController::class, 'index'])->name('index');
            Route::get('/create', [AdminMaterialController::class, 'create'])->name('create');
            Route::post('/', [AdminMaterialController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminMaterialController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminMaterialController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminMaterialController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminMaterialController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [AdminProductController::class, 'index'])->name('index');
            Route::get('/create', [AdminProductController::class, 'create'])->name('create');
            Route::post('/', [AdminProductController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminProductController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('collections')->name('collections.')->group(function () {
            Route::get('/', [AdminCollectionController::class, 'index'])->name('index');
            Route::get('/create', [AdminCollectionController::class, 'create'])->name('create');
            Route::post('/', [AdminCollectionController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminCollectionController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminCollectionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminCollectionController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminCollectionController::class, 'destroy'])->name('destroy');
        });
    });

Auth::routes(['register' => false]);
Route::post('/register', [RegisterController::class, 'register'])->name('register');

