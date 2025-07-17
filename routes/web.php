<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use UniSharp\LaravelFilemanager\Lfm;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web']], function () {
    Lfm::routes();
});

Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/test-post', [HomeController::class, 'testPost'])->name('testPost');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/get-cities', [AddressController::class, 'getCities'])->name('getCities');
Route::get('/get-districts', [AddressController::class, 'getDistricts'])->name('getDistricts');
Route::get('/get-wards', [AddressController::class, 'getWard'])->name('getWard');
Route::get('/address', [AddressController::class, 'getListAddress'])->name('address.store');
Route::post('/address', [AddressController::class, 'store'])->name('address.listAddress');
Route::get('/address/{id}', [AddressController::class, 'getAddress'])->name('address.getAddress');
Route::get('/get-default-address', [AddressController::class, 'getAddressDefault123'])->name('address.getAddressDefault');
Route::put('/address/{id}', [AddressController::class, 'update'])->name('address.update');
Route::put('/address/update-default/{id}', [AddressController::class, 'updateDefault'])->name('address.updateDefault');
Route::delete('/address/{id}', [AddressController::class, 'delete'])->name('address.delete');

require __DIR__.'/auth.php';
