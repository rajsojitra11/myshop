<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Expense;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Income;
use App\Models\Invoice;



class UserController extends Controller
{
    //
    public function dashboard()
    {
        if (!session('user')) {
            return redirect()->route('index');
        }

        $user = Auth::user();

        $totalExpense = Expense::where('user_id', $user->id)->sum('amount');
        $totalIncome = Income::where('user_id', $user->id)->sum('amount');
        $supplierCount = Supplier::count();
        $customer = Invoice::count();
        $productCount = Product::count();

        return view('admin.dashboard', compact('totalExpense', 'totalIncome', 'supplierCount', 'customer', 'productCount'));
    }

    // public function invoice()
    // {
    //     $user = Auth::user();
    //     Log::info("User accessing products: " . $user->id);

    //     $products = Product::where('uid', $user->id)->get();

    //     return view('admin.invoice', compact('products'));
    // }
    public function register()
    {
        return view('register');
    }
    public function product()
    {
        return view('admin.product');
    }
    public function supplier()
    {
        return view('admin.supplier');
    }

    public function setting()
    {
        if (!session('user')) {
            return redirect()->route('index');
        }
        return view('admin.setting');
    }
    public function viewprofile()
    {
        return view('admin.viewprofile');
    }
}
