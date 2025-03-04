<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //
    public function store(Request $request)
    {
        DB::beginTransaction(); // Start transaction

        try {
            // Create Customer
            $customer = Customer::create([
                'name' => $request->to_name,
                'address' => $request->to_address,
                'email' => $request->to_email,
                'mobile_no' => $request->mobile_no,
            ]);

            // Create Invoice
            $invoice = Invoice::create([
                'customer_id'    => $customer->id,
                'bill_no'        => $request->bill_no,
                'invoice_number' => $request->invoice_number,
                'order_id'       => $request->order_id,
                'account_number' => $request->account_number,
                'subtotal'       => $request->subtotal,
                'tax'            => $request->tax,
                'shipping'       => $request->shipping,
                'total'          => $request->total,
                'payment_method' => $request->paymentMethod,
            ]);

            // Insert Products
            foreach ($request->products as $product) {
                InvoiceProduct::create([
                    'invoice_id'  => $invoice->id,
                    'serial'      => $product['serial'],
                    'name'        => $product['name'],
                    'description' => $product['description'],
                    'qty'         => $product['qty'],
                    'rate'        => $product['rate'],
                    'amount'      => $product['amount'],
                ]);
            }

            DB::commit(); // Commit transaction

            return redirect()->back()->with('success', 'Invoice created successfully!');
        } catch (\Exception $e) {
            DB::rollback(); // Rollback on failure
            return back()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }
}
