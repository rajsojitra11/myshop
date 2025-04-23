<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function showCustomers()
    {
        $invoices = Invoice::select('to_name', 'mobile_no', 'bill_no', 'payment_method', 'total')->get();
        return view('admin.customer', compact('invoices'));
    }
    public function create()
    {
        return view('admin.invoice'); // Replace with your actual Blade file path
    }

    public function store(Request $request)
    {
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
            'subtotal' => $request->subtotal,
            'tax' => $request->tax,
            'shipping' => $request->shipping ?? 0,
            'total' => $request->total,
        ]);

        // Save Invoice Products
        foreach ($request->products as $product) {
            InvoiceProduct::create([
                'invoice_id' => $invoice->id,
                'serial' => $product['serial'] ?? '',
                'name' => $product['name'],
                'description' => $product['description'] ?? '',
                'qty' => $product['qty'],
                'rate' => $product['rate'],
                'amount' => $product['amount'],
            ]);
        }

        return redirect()->back()->with('success', 'Invoice saved successfully.');
    }
}
