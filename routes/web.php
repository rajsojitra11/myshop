<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;




Route::get('login', [UserController::class, 'login'])->name('login');
Route::get('register', [UserController::class, 'register'])->name('register');
Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
Route::get('product', [UserController::class, 'product'])->name('product');
Route::get('supplier', [UserController::class, 'supplier'])->name('supplier');
Route::get('customer', [UserController::class, 'customer'])->name('customer');
Route::get('invoice', [UserController::class, 'invoice'])->name('invoice');
Route::get('setting', [UserController::class, 'setting'])->name('setting');
Route::get('viewprofile', [UserController::class, 'viewprofile'])->name('viewprofile');
Route::post('registers', [RegisterController::class, 'register']);
