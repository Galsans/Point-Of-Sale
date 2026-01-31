<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuOptionGroupController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\OptionGroupController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    // Route::get('/register', function () {
    //     return view('auth.register');
    // })->name('register');
    // Route::post('/register', [AuthController::class, 'register']);
});

// ORDER
Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

// AUTHENTICATED
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::resource('tables', TableController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('option-groups', OptionGroupController::class);
    Route::resource('options', OptionController::class);
    Route::resource('menu-option-groups', MenuOptionGroupController::class);
    Route::resource('taxes', PajakController::class)->only(['index', 'update']);


    // ✅ ORDER MANAGEMENT (Admin Only)
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // routes/web.php
    // Route::get('/order/menu', [MenuController::class, 'orderPage'])->name('order.menu');
    // Route::post('/order/menu', [MenuController::class, 'store'])->name('order.store');
    Route::get(
        '/option-groups/{id}/options',
        [App\Http\Controllers\OptionGroupController::class, 'options']
    );
});

Route::get('/order/table/{table_id}', [App\Http\Controllers\OrderController::class, 'orderByTable'])->name('order.by-table');

Route::get('/coba', function () {
    return view('order.menu');
});

// Route::resource('orders', OrderController::class)->only(['store', 'create']);


Route::get('test-form', function () {
    return view('coba');
});

// Route::get('/order/menu', [MenuController::class, 'orderPage'])
//     ->name('order.menu');

// Route::post('/order/menu', [MenuController::class, 'store'])
//     ->name('order.store');
// Route::post('/order/store', [OrderController::class, 'store'])
//     ->name('order.store')
//     ->middleware('throttle:10,1'); // Max 10 request per menit

// Route::prefix('order')->name('order.')->group(function () {
//     Route::get('/menu', [OrderController::class, 'menu'])->name('menu');
//     Route::post('/store', [OrderController::class, 'store'])->name('store')
//         ->middleware('throttle:10,1'); // Max 10 request per minute
//     Route::get('/confirmation/{orderCode}', [OrderController::class, 'confirmation'])->name('confirmation');
// });

Route::get('order/menu', [OrderController::class, 'menu'])->name('order.menu');
Route::post('order/store', [OrderController::class, 'store'])->name('order.store')
    ->middleware('throttle:10,1'); // Max 10 request per minute
Route::get('order/confirmation/{orderCode}', [OrderController::class, 'confirmation'])->name('order.confirmation');

// Route::get('/option-groups/search', [App\Http\Controllers\OptionGroupController::class, 'search']);
// Route::get('/categories/search', [App\Http\Controllers\CategoryController::class, 'search'])->name('categories.search');
