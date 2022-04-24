<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('authenticated')->group(function () {
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'create'])->name('create');
        Route::get('/{product:slug}', [ProductController::class, 'show'])->name('get');
        Route::put('/{product:slug}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product:slug}', [ProductController::class, 'destroy'])->name('delete');
    });
});
