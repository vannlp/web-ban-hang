<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Client\AuthClientController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\PageController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/san-pham/{slug}', [PageController::class, 'productDetail'])->name('client.product.detail');
Route::get('/client/login', [AuthClientController::class, 'login'])->name('client.login');
Route::post('/client/handle-login', [AuthClientController::class, 'handleLogin'])->name('client.handleLogin');
Route::get('/client/profile', [AuthClientController::class, 'profile'])->name('client.profile');
Route::get('/client/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('client.logout');
Route::get('/cart', [CartController::class, 'cartPage'])->name('client.cart');
Route::get('/get-cart', [CartController::class, 'getCart'])->name('client.getCart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('client.addToCart');
Route::delete('/delete-cart-detail/{id}', [CartController::class, 'deleteCartDetail'])->name('client.deleteCartDetail');
Route::put('/update-cart-detail', [CartController::class, 'updateCartDetail'])->name('client.updateCartDetail');
// Route::get('/register')->name('client.register');
Route::get('/checkout', [PageController::class, 'orderPage'])->name('client.checkout');
Route::post('/checkout', [OrderController::class, 'handleCheckout'])->name('client.checkout');
Route::get('/checkout-success', [PageController::class, 'checkoutSuccess'])->name('client.checkoutSuccess');
Route::get('/order-datatable', [OrderController::class, 'clientDataTable'])->name('client.clientDataTable');
Route::get('/order/{id}', [OrderController::class, 'orderDetail'])->name('client.getOrder');

Route::get('/danh-muc/{slug}', [PageController::class, 'categoryPage'])->name('client.category');
Route::get('/tim-kiem', [PageController::class, 'searchPage'])->name('client.searchPage');

Route::get('/client/category', [CategoryController::class, 'getClient'])->name('client.category.getCategories');