<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImgController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{category}', [CategoryController::class, 'show']);
    Route::put('/{category}', [CategoryController::class, 'update']);
    Route::delete('/{category}', [CategoryController::class, 'destroy']);
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::put('/{product}', [ProductController::class, 'update']);
    Route::delete('/{product}', [ProductController::class, 'destroy']);
    Route::get('/filter/{categoryName}', [ProductController::class, 'listProductsByCategory']);
});


Route::group(['prefix' => 'productImg'], function () {
    Route::get('/', [ProductImgController::class, 'index']);    
    Route::post('/', [ProductImgController::class, 'store']);
    Route::get('/{product}', [ProductImgController::class, 'show']);
    Route::get('/filter/{product}', [ProductImgController::class, 'listProductImage']);
    Route::put('/{product}', [ProductImgController::class, 'update']);
    Route::delete('/{product}', [ProductImgController::class, 'destroy']);
});

Route::group(['prefix' => 'order'], function () {
    Route::get('/', [ProductImgController::class, 'index']);    
    Route::post('/', [ProductImgController::class, 'store']);
    Route::get('/{product}', [ProductImgController::class, 'show']);
    Route::put('/{product}', [ProductImgController::class, 'update']);
    Route::delete('/{product}', [ProductImgController::class, 'destroy']);
});

// Route::post('/', [ProductController::class, 'store']);
Route::get('/product-images/{filename}', [ProductController::class, 'showImg']);
Route::get('/product-sub-images/{filename}', [ProductImgController::class, 'showImg']);