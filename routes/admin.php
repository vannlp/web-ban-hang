<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\OrderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth.admin', 'role:admin']) 
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/test-api', [DashboardController::class, 'testApi'])->name('dashboard.post');
        
        // Users
        Route::get('/user/listUser', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/datatable', [UserController::class, 'datatable'])->name('user.datatable');
        
        // Product
        Route::get('/product', [ProductController::class, 'index'])->name('product.index');
        Route::get('/product/add', [ProductController::class, 'add'])->name('product.add');
        Route::get('/product/datatable', [ProductController::class, 'datatable'])->name('product.datatable');
        Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        
        // Category
        Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/category/datatable', [CategoryController::class, 'datatable'])->name('category.datatable');
        Route::put('/category/update-status/{id}', [CategoryController::class, 'updateStatus'])->name('category.update-status');
        
        // Order
        Route::get('/order', [OrderController::class, 'viewOrderAdmin'])->name('order.index');
    });

    
// các api dùng chung
Route::post('/user', [UserController::class, 'store'])->name('user.store')->middleware(['auth.admin', 'role:admin']) ;
Route::delete('/user/{id}', [UserController::class, 'delete'])->name('user.delete')->middleware(['auth.admin', 'role:admin']) ;
Route::get('/user/{id}', [UserController::class, 'getUser'])->name('user.getUser');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update')->middleware(['auth.admin', 'role:admin']) ;

Route::post('/product', [ProductController::class, 'store'])->name('product.store')->middleware(['auth.admin', 'role:admin']);
Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update')->middleware(['auth.admin', 'role:admin']);
Route::delete('/product/{id}', [ProductController::class, 'delete'])->name('product.delete')->middleware(['auth.admin', 'role:admin']);

Route::get('/category', [CategoryController::class, 'getCategories'])->name('category.getCategories');
Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/{id}', [CategoryController::class, 'getOne'])->name('category.getOne');
Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/{id}', [CategoryController::class, 'delete'])->name('category.delete')->middleware(['auth.admin', 'role:admin']) ;

Route::post('/update-order-status', [OrderController::class, 'updateStatus'])->name('order.updateOrderStatus');
