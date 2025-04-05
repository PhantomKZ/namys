<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/subscribe', [HomeController::class, 'subscribe'])->name('subscribe');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [AdminCategoryController::class, 'index'])->name('index');
            Route::get('/create', [AdminCategoryController::class, 'create'])->name('create');
            Route::post('/', [AdminCategoryController::class, 'store'])->name('store');
            Route::get('/{slug}', [AdminCategoryController::class, 'show'])->name('show');
            Route::get('/{slug}/edit', [AdminCategoryController::class, 'edit'])->name('edit');
            Route::put('/{slug}', [AdminCategoryController::class, 'update'])->name('update');
            Route::delete('/{slug}', [AdminCategoryController::class, 'destroy'])->name('destroy');
        });
    });

Auth::routes(['register' => false]);
Route::post('/register', [RegisterController::class, 'register'])->name('register');

