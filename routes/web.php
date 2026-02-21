<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\InvoiceController;

// ─── Public / Auth Routes ────────────────────────────────────────────────────

Route::get('/', [LoginController::class, 'showLoginForm'])->name('index');
Route::post('/', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('registers', [RegisterController::class, 'register'])->name('registers');

// ─── Protected Routes (requires login) ───────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Dashboard & User Pages
    Route::get('register', [UserController::class, 'register'])->name('register');
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('product', [UserController::class, 'product'])->name('product');
    Route::get('setting', [UserController::class, 'setting'])->name('setting');

    // Profile
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Products CRUD
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::post('/products', [ProductController::class, 'store'])->name('product.store');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

    // Invoices
    Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::post('/invoice', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/customer', [InvoiceController::class, 'showCustomers'])->name('customer');

    // Suppliers
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/supplier/{id}', [SupplierController::class, 'show'])->name('supplier.show');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expense');
    Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');

    // Income
    Route::get('/income', [IncomeController::class, 'index'])->name('income.index');
    Route::post('/income', [IncomeController::class, 'store'])->name('income.store');
    Route::get('/income/filter', [IncomeController::class, 'filter'])->name('income.filter');
});
