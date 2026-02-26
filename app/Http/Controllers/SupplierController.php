<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::where('user_id', Auth::id())->get();
        return view('admin.supplier', compact('suppliers'));
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Load related stock records (paginated) for the supplier
        $stocks = \App\Models\SupplierStock::where('supplier_id', $id)->orderBy('date', 'desc')->paginate(10);

        return view('admin.viewprofile', compact('supplier', 'stocks'));
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.editsupplier', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'address' => 'required|string',
            'contact_no' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'bank_details' => 'required|string',
            'product_categories' => 'required|string',
        ]);

        $supplier->update($validatedData);

        return redirect()->route('supplier.show', $supplier->id)
                        ->with('success', 'Supplier updated successfully!');
    }


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
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        if ($supplier->user_id !== Auth::id()) {
            return redirect()->back()->withErrors('Unauthorized action.');
        }

        $supplier->delete();

        return redirect()->back()->with('supplier', 'Supplier deleted successfully!');
    }
}
