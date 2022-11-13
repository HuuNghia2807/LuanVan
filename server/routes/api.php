<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProvinceController;

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

Route::group(['prefix' => 'customer'], function () {
    Route::get('/{id}', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/address/{id}', [CustomerController::class, 'getCustomerAddress']);
    Route::post('/register', [CustomerController::class, 'register'])->name('customer.register');
    Route::post('/login', [CustomerController::class, 'login'])->name('customer.login');
    Route::post('/delete', [CustomerController::class, 'destroy'])->name('customer.destroy');
    Route::put('/update', [CustomerController::class, 'update'])->name('customer.update');
    Route::put('/password', [CustomerController::class, 'changePass']);
    Route::put('/address', [CustomerController::class, 'updateAddress']);
    // Route::post('refresh-token', [AuthController::class, 'refresh'])->name('auth.refresh');
    // Route::get('me', [AuthController::class, 'userProfile'])->name('auth.userProfile');
    // Route::post('newpassword', [AuthController::class, 'changePassword'])->name('auth.changePassword');
    // Route::get('', [AuthController::class, 'getAllUser'])->name('auth.getAllUser');
});

Route::group(['prefix' => 'employee'], function () {
    Route::get('/all', [EmployeeController::class, 'index'])->name('employee.index');
    Route::post('/add', [EmployeeController::class, 'store'])->name('employee.store');
    Route::post('/login', [EmployeeController::class, 'login'])->name('employee.login');
});

Route::group(['prefix' => 'province'], function () {
    Route::get('', [ProvinceController::class, 'index'])->name('province.index');
});

Route::group(['prefix' => 'payment'], function () {
    Route::get('', [PaymentController::class, 'index'])->name('payment.index');
});

Route::group(['prefix' => 'discount'], function () {
    Route::get('/', [DiscountController::class, 'index'])->name('discount.index');
    Route::post('/add', [DiscountController::class, 'store'])->name('discount.store');
    Route::post('/product', [DiscountController::class, 'addToProduct'])->name('discount.addToProduct');
    Route::post('/remove', [DiscountController::class, 'removeToProduct'])->name('discount.removeToProduct');
});

Route::group(['prefix' => 'category'], function () {
    Route::get('', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/add', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
});

Route::group(['prefix' => 'product'], function () {
    Route::get('', [ProductController::class, 'index'])->name('product.index');
    Route::post('/add', [ProductController::class, 'store'])->name('product.store');
    Route::post('/delete', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::put('/update', [ProductController::class, 'update'])->name('product.update');
});

Route::group(['prefix' => 'size'], function () {
    Route::get('', [SizeController::class, 'index'])->name('size.index');
    Route::post('/add', [SizeController::class, 'store'])->name('size.store');
});

Route::group(['prefix' => 'cart'], function () {
    Route::get('', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'store']);
});

Route::group(['prefix' => 'order'], function () {
    Route::get('', [OrderController::class, 'index'])->name('order.index');
    Route::get('/status/{id}', [OrderController::class, 'getOrderStatus']);
    Route::get('/customer/{id}', [OrderController::class, 'getPersonalOrder']);
    Route::post('', [OrderController::class, 'store'])->name('order.store');
    Route::post('/status/update', [OrderController::class, 'OrderUpdateStatus']);
});
