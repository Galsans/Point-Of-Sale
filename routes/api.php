<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/categories/search', [CategoryController::class, 'search']);

// Route::get('/orders/{order}/detail', [OrderController::class, 'show']);

Route::get('/menus/{menu}/options', [MenuController::class, 'getMenuOptions']);
