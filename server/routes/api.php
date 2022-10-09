<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'category'], function () {
    Route::get('', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/add', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
});

Route::group(['prefix' => 'product'], function () {
    Route::get('', [ProductController::class, 'index'])->name('product.index');
    Route::post('/delete', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::post('/add', [ProductController::class, 'store'])->name('product.store');
    Route::post('/{id}', [ProductController::class, 'update'])->name('product.update');
});

Route::group(['prefix' => 'size'], function () {
    Route::get('', [SizeController::class, 'index'])->name('size.index');
    Route::post('/add', [SizeController::class, 'store'])->name('size.store');
});
