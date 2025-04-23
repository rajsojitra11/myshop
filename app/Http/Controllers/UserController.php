<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Expense;
use App\Models\Supplier;
use App\Models\product;
use App\Models\income;
use App\Models\invoice;



class UserController extends Controller
{
    //
    public function dashboard()
    {
        if (!session('user')) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $totalExpense = Expense::where('user_id', $user->id)->sum('amount');
        $totalIncome = Income::where('user_id', $user->id)->sum('amount');
        $supplierCount = Supplier::count();
        $customer = invoice::count();
        $productCount = Product::count();

        return view('admin.dashboard', compact('totalExpense', 'totalIncome', 'supplierCount', 'customer', 'productCount'));
    }
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
    public function customer()
    {
        return view('admin.customer');
    }
    public function invoice()
    {
        return view('admin.invoice');
    }
    public function setting()
    {
        if (!session('user')) {
            return redirect()->route('login');
        }
        return view('admin.setting');
    }
    public function viewprofile()
    {
        return view('admin.viewprofile');
    }
}
