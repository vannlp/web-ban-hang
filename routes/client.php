<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Client\AuthClientController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/san-pham/{slug}', [PageController::class, 'productDetail'])->name('client.product.detail');
Route::get('/client/login', [AuthClientController::class, 'login'])->name('client.login');
Route::post('/client/handle-login', [AuthClientController::class, 'handleLogin'])->name('client.handleLogin');
Route::get('/client/profile', [AuthClientController::class, 'profile'])->name('client.profile');
Route::get('/client/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('client.logout');
Route::get('/cart', [CartController::class, 'cartPage'])->name('client.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('client.addToCart');
// Route::get('/register')->name('client.register');

Route::get('/client/category', [CategoryController::class, 'getClient'])->name('client.category.getCategories');