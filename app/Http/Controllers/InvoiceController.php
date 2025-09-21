<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Show list of customers (invoices summary)
     */
    public function showCustomers()
    {
        $invoices = Invoice::select('id', 'to_name', 'mobile_no', 'bill_no', 'payment_method', 'total')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.customer', compact('invoices'));
    }

    /**
     * Show full invoice with products
     */
    public function show($id)
    {
        $invoice = Invoice::with('products')->findOrFail($id);

        return view('admin.invoice_show', compact('invoice'));
    }

    /**
     * Show invoice creation form
     */
    public function create()
    {
        $user = Auth::user();
        $products = Product::where('uid', $user->id)->get();

        return view('admin.invoice', compact('products'));
    }

    /**
     * Store a new invoice with its products
     */
    public function store(Request $request)
    {
        $request->validate([
            'to_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'bill_no' => 'required|string|max:50',
            'invoice_number' => 'required|string|max:50',
            'paymentMethod' => 'required|string|max:50',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string',
            'products.*.qty' => 'required|numeric',
            'products.*.rate' => 'required|numeric',
            'products.*.amount' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {

            // Save Invoice
            $invoice = Invoice::create([
                'user_id' => Auth::id(),
                'to_name' => $request->to_name,
                'to_address' => $request->to_address,
                'to_email' => $request->to_email,
                'mobile_no' => $request->mobile_no,
                'bill_no' => $request->bill_no,
                'invoice_number' => $request->invoice_number,
                'order_id' => $request->order_id,
                'account_number' => $request->account_number,
                'payment_method' => $request->paymentMethod,
                'subtotal' => (float) $request->subtotal,
                'tax' => (float) $request->tax,
                'shipping' => (float) ($request->shipping ?? 0),
                'total' => (float) $request->total,
            ]);

            // Save Invoice Products
            foreach ($request->products as $product) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'serial' => $product['serial'] ?? '',
                    'name' => $product['name'],
                    'description' => $product['description'] ?? '',
                    'qty' => (float) $product['qty'],
                    'rate' => (float) $product['rate'],
                    'amount' => (float) $product['amount'],
                ]);
            }

            Log::info("Invoice {$invoice->id} created by user " . Auth::id());
        });

        return redirect()->route('invoice.create')->with('success', 'Invoice saved successfully.');
    }
}
