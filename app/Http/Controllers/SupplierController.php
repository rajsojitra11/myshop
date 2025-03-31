<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|unique:suppliers,supplier_id',
            'company_name' => 'required',
            'email' => 'required|email|unique:suppliers,email',
            'address' => 'required',
            'contact_no' => 'required',
            'country' => 'required',
            'bank_details' => 'required',
            'product_categories' => 'required',
        ]);

        Supplier::create([
            'user_id' => Auth::id(),
            'supplier_id' => $request->supplier_id,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'country' => $request->country,
            'bank_details' => $request->bank_details,
            'product_categories' => $request->product_categories,
        ]);

        return redirect()->back()->with('supplier', 'Supplier added successfully!');
    }
}
