<?php
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')
    ->name('admin.')
    // ->middleware(['auth', 'is_admin']) // is_admin middleware tự viết
    ->group(function () {
        Route::get('/', fn () => Inertia::render('Admin/Home'))->name('dashboard');
    });
