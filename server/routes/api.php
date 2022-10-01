<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

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
    Route::post('', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/{id}',[CategoryController::class,'update'])->name('category.update');
    Route::delete('/{id}',[CategoryController::class,'destroy'])->name('category.destroy');
});

Route::group(['prefix' => 'product'],function(){
    Route::post('', [ProductController::class, 'store'])->name('product.store');
    Route::post('/{id}',[ProductController::class,'update'])->name('product.update');
    Route::delete('/{id}',[ProductController::class,'destroy'])->name('product.destroy');
});
