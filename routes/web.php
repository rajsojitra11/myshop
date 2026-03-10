<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierStockController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;

// ─── Public / Auth Routes ────────────────────────────────────────────────────
Route::get('welcome', function () {
    return view('welcome');
})->name('welcome');
Route::get('/', [LoginController::class, 'showLoginForm'])->name('index');
Route::post('/', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('registers', [RegisterController::class, 'register'])->name('registers');

// ─── Customer Routes (Public) ────────────────────────────────────────────────

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('login', [CustomerController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerController::class, 'login']);
    Route::get('dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('invoice/{id}', [CustomerController::class, 'showInvoice'])->name('invoice');
    Route::get('change-password', [CustomerController::class, 'showChangePasswordForm'])->name('password.form');
    Route::post('change-password', [CustomerController::class, 'updatePassword'])->name('password.update');
    Route::get('logout', [CustomerController::class, 'logout'])->name('logout');
});

// ─── Supplier Routes (Public) ────────────────────────────────────────────────

Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::get('login', [SupplierController::class, 'showLoginForm'])->name('login');
    Route::post('login', [SupplierController::class, 'login']);
    Route::get('dashboard', [SupplierController::class, 'dashboard'])->name('dashboard');
    Route::get('change-password', [SupplierController::class, 'showChangePasswordForm'])->name('password.form');
    Route::post('change-password', [SupplierController::class, 'updatePassword'])->name('password.update');
    Route::get('logout', [SupplierController::class, 'logout'])->name('logout');
});

// ─── Protected Routes (requires login) ───────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Dashboard & User Pages
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
    Route::get('/invoice/{id}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoice.pdf');
    Route::post('/invoice/whatsapp', [InvoiceController::class, 'sendWhatsApp'])->name('invoice.whatsapp');
    Route::post('/invoice', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/customer', [InvoiceController::class, 'showCustomers'])->name('customer');

    // Suppliers
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/supplier/{id}', [SupplierController::class, 'show'])->name('supplier.show');
    Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    // Supplier Stock Routes
    Route::get('/supplier/{supplier_id}/stocks/create', [SupplierStockController::class, 'create'])->name('supplier-stock.create');
    Route::post('/supplier/stocks', [SupplierStockController::class, 'store'])->name('supplier-stock.store');
    Route::get('/supplier/{supplier_id}/stocks', [SupplierStockController::class, 'show'])->name('supplier-stock.show');
    Route::get('/supplier/stock/{id}/edit', [SupplierStockController::class, 'edit'])->name('supplier-stock.edit');
    Route::put('/supplier/stock/{id}', [SupplierStockController::class, 'update'])->name('supplier-stock.update');
    Route::delete('/supplier/stock/{id}', [SupplierStockController::class, 'destroy'])->name('supplier-stock.destroy');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expense');
    Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');

    // Income
    Route::get('/income', [IncomeController::class, 'index'])->name('income.index');
    Route::post('/income', [IncomeController::class, 'store'])->name('income.store');
    Route::get('/income/filter', [IncomeController::class, 'filter'])->name('income.filter');
});
