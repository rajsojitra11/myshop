<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.dashboard');
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
        return view('admin.setting');
    }
    public function viewprofile()
    {
        return view('admin.viewprofile');
    }
}
