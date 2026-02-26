<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierStock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierStockController extends Controller
{
    /**
     * Show the form for creating a new stock entry for a supplier.
     *
     * @param  int  $supplier_id
     * @return \Illuminate\View\View
     */
    public function create($supplier_id)
    {
        // Fetch the supplier by ID
        $supplier = Supplier::findOrFail($supplier_id);

        // Get all users (this could be used to assign the user who adds the stock)
        $users = User::all();

        return view('supplier_stock.create', compact('supplier', 'users'));
    }

    /**
     * Store a newly created stock record in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',  // Ensure the user exists
            'product' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id', // Ensure the supplier exists
        ]);

        // Create a new SupplierStock entry
        SupplierStock::create([
            'user_id' => $validatedData['user_id'],
            'product' => $validatedData['product'],
            'quantity' => $validatedData['quantity'],
            'description' => $validatedData['description'],
            'amount' => $validatedData['amount'],
            'date' => $validatedData['date'],
            'supplier_id' => $validatedData['supplier_id'],  // Supplier associated with the stock
        ]);

        // Redirect to the supplier page with a success message
        return redirect()->route('supplier.show', $validatedData['supplier_id'])
                         ->with('success', 'Stock added successfully!');
    }

    /**
     * Show the stock records for a given supplier.
     *
     * @param  int  $supplier_id
     * @return \Illuminate\View\View
     */
    public function show($supplier_id)
    {
        // Fetch the supplier by ID
        $supplier = Supplier::findOrFail($supplier_id);

        // Fetch the stock records related to the supplier
        $stocks = SupplierStock::where('supplier_id', $supplier_id)->paginate(10);  // Paginate if needed

        return view('supplier_stock.index', compact('supplier', 'stocks'));
    }

    /**
     * Show the form for editing an existing stock record.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Fetch the stock record by ID
        $stock = SupplierStock::findOrFail($id);

        // Get the supplier and users
        $supplier = $stock->supplier;
        $users = User::all();

        return view('supplier_stock.edit', compact('stock', 'supplier', 'users'));
    }

    /**
     * Update the specified stock record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Fetch the stock record by ID
        $stock = SupplierStock::findOrFail($id);

        // Validate the incoming data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure the user exists
            'product' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        // Update the stock record
        $stock->update($validatedData);

        // Redirect back to the supplier page with a success message
        return redirect()->route('supplier.show', $stock->supplier_id)
                         ->with('success', 'Stock updated successfully!');
    }

    /**
     * Remove the specified stock record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Fetch the stock record by ID
        $stock = SupplierStock::findOrFail($id);

        // Get the supplier_id before deleting, to redirect to the supplier page
        $supplier_id = $stock->supplier_id;

        // Delete the stock record
        $stock->delete();

        // Redirect back to the supplier page with a success message
        return redirect()->route('supplier.show', $supplier_id)
                         ->with('success', 'Stock deleted successfully!');
    }
}