<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

Route::post('registers', [RegisterController::class, 'register'])->name('registers');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('register', [UserController::class, 'register'])->name('register');
Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
Route::get('product', [UserController::class, 'product'])->name('product');
Route::get('supplier', [UserController::class, 'supplier'])->name('supplier');
Route::get('customer', [UserController::class, 'customer'])->name('customer');
Route::get('invoice', [UserController::class, 'invoice'])->name('invoice');
Route::get('setting', [UserController::class, 'setting'])->name('setting');
Route::get('viewprofile', [UserController::class, 'viewprofile'])->name('viewprofile');


Route::post('invoice', [InvoiceController::class, 'store'])->name('invoice');

Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::post('/products', [ProductController::class, 'store'])->name('product.store');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('product.update');
});
