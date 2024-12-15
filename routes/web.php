<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


    Route::get('/', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');


    Route::get('/login', [AuthController::class, 'index']);
    Route::get('/register', [AuthController::class, 'registerUser']);

    Route::get('/orders/{orderId}', [OrderController::class, 'showOrder']);




    Route::post('/cart', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart/count', [CartController::class, 'getCartCount']);
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.get');
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');


    Route::delete('/cart', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::put('/cart', [CartController::class, 'updateCartItem'])->name('cart.update');

    Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::post('/process-payment', [OrderController::class, 'processPayment'])->name('process-payment');
    Route::post('/payment/callback', [OrderController::class, 'paymentCallback']);
    Route::get('/orders/{id}', [OrderController::class, 'showOrder'])->name('orders.show');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

