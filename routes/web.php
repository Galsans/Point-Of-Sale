<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuOptionGroupController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\OptionGroupController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\PriceOfferController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;


Route::get('/', [LandingController::class, 'page']);

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ORDER (PUBLIC - tanpa auth)
// ⚠️ Urutan penting! Route spesifik harus di atas route dengan parameter
Route::get('order/cart', [CartController::class, 'cart'])->name('order.cart');
Route::get('order/menu', [OrderController::class, 'menu'])->name('order.menu');
Route::get('order/confirmation/{orderCode}', [OrderController::class, 'confirmation'])->name('order.confirmation');
Route::post('order/store', [OrderController::class, 'store'])->name('order.store')->middleware('throttle:10,1');
Route::post('order/upload-bukti/{orderCode}', [OrderController::class, 'uploadBukti'])->name('order.upload-bukti');
Route::get('/order/table/{table_id}', [OrderController::class, 'orderByTable'])->name('order.by-table');

Route::post('/order/add-package', [OrderController::class, 'addPackage'])
    ->name('order.add-package');

// AUTHENTICATED
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Route::get('/dashboard', function () {
    //     return view('dashboard.index');
    // })->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

    Route::resource('tables', TableController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('option-groups', OptionGroupController::class);
    Route::resource('options', OptionController::class);
    Route::resource('menu-option-groups', MenuOptionGroupController::class);
    Route::resource('taxes', PajakController::class)->only(['index', 'update']);

    // ORDER MANAGEMENT (Admin Only)
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    Route::get('/option-groups/{id}/options', [OptionGroupController::class, 'options']);


    // ── Price Offers ─────────────────────────────────────────────────────────
    Route::get('price-offers/trashed',              [PriceOfferController::class, 'trashed'])->name('price-offers.trashed');
    Route::patch('price-offers/{id}/restore',       [PriceOfferController::class, 'restore'])->name('price-offers.restore');
    Route::delete('price-offers/{id}/force-delete', [PriceOfferController::class, 'forceDelete'])->name('price-offers.force-delete');
    Route::patch('price-offers/{priceOffer}/toggle-status', [PriceOfferController::class, 'toggleStatus'])->name('price-offers.toggle-status');
    Route::post('price-offers/reorder',             [PriceOfferController::class, 'reorder'])->name('price-offers.reorder');

    Route::resource('price-offers', PriceOfferController::class);
    Route::patch('price-offers/{priceOffer}/toggle', [PriceOfferController::class, 'toggleActive'])
        ->name('price-offers.toggle');
});
