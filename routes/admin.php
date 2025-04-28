<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth.admin']) 
    ->group(function () {
        Route::get('/', fn () => Inertia::render('Admin/Home'))->name('dashboard');
        Route::post('/test-api', [DashboardController::class, 'testApi'])->name('dashboard.post');
    });
