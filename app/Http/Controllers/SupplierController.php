<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SupplierController extends Controller
{
    /**
     * Show supplier login form
     */
    public function showLoginForm()
    {
        if (Session::has('supplier_email')) {
            return redirect()->route('supplier.dashboard');
        }
        return redirect()->route('index');
    }

    /**
     * Handle supplier login - verify email and password
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:1',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email',
            'password.required' => 'Password is required',
        ]);

        // Find supplier by email
        $supplier = Supplier::where('email', $request->email)->first();

        if (!$supplier) {
            return back()->withErrors(['email' => 'No supplier found with this email'])->withInput();
        }

        // Check if supplier has a custom hashed password
        if ($supplier->password) {
            // Verify against hashed password
            if (!\Illuminate\Support\Facades\Hash::check($request->password, $supplier->password)) {
                return back()->withErrors(['password' => 'Invalid password'])->withInput();
            }
        } else {
            // Fallback: use supplier_id as password (original behavior)
            if ($request->password !== $supplier->supplier_id) {
                return back()->withErrors(['password' => 'Invalid password'])->withInput();
            }
        }

        // Store supplier session
        Session::put('supplier_email', $supplier->email);
        Session::put('supplier_id', $supplier->supplier_id);
        Session::put('supplier_name', $supplier->company_name);
        Session::put('supplier_db_id', $supplier->id);

        return redirect()->route('supplier.dashboard')->with('success', 'Login successful!');
    }

    /**
     * Show supplier dashboard with their profile
     */
    public function dashboard(Request $request)
    {
        if (!Session::has('supplier_email')) {
            return redirect()->route('index');
        }

        $supplierId = Session::get('supplier_db_id');
        $supplier = Supplier::findOrFail($supplierId);
        
        $query = \App\Models\SupplierStock::where('supplier_id', $supplierId);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhere('date', 'like', "%{$search}%");
            });
        }

        $stocks = $query->orderBy('date', 'desc')->paginate(10)->appends(['search' => $request->search]);

        return view('supplier.dashboard', compact('supplier', 'stocks'));
    }

    /**
     * Logout supplier
     */
    public function logout()
    {
        Session::forget('supplier_email');
        Session::forget('supplier_id');
        Session::forget('supplier_name');
        Session::forget('supplier_db_id');
        
        return redirect()->route('index')->with('success', 'Logged out successfully!');
    }

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
