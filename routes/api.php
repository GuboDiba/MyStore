<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('v1.register');
    Route::post('login', [AuthController::class, 'login'])->name('v1.login');
    

    // Authenticatted users
    Route::middleware('auth:api')->group(function () {
        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/{id}', [ProductController::class, 'show']);
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);
        
        // Order routes - protected by auth middleware
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
        Route::post('orders', [OrderController::class, 'store']);
        Route::put('orders/{id}', [OrderController::class, 'update']);
        Route::delete('orders/{id}', [OrderController::class, 'destroy']);

        // Cart
        Route::post('/cart', [CartController::class, 'addToCart']); 
        Route::get('/cart', [CartController::class, 'getCart']);
        Route::delete('/cart', [CartController::class, 'clearCart']); 
        Route::put('/cart', [CartController::class, 'updateCartItem']);
        Route::delete('/cart/{product_id}', [CartController::class, 'removeFromCart']);

    });

});
