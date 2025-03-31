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

        $request->validate([
            'to_name' => 'required|string',
            'to_address' => 'required|string',
            'to_email' => 'required|email',
            'mobile_no' => 'required|string',
            'bill_no' => 'required|string|unique:invoices,bill_no',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'order_id' => 'required|string|unique:invoices,order_id',
            'account_number' => 'required|string',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'shipping' => 'nullable|numeric',
            'total' => 'required|numeric',
            'paymentMethod' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer',
            'products.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {

            $customer = Customer::create([
                'name' => $request->to_name,
                'address' => $request->to_address,
                'email' => $request->to_email,
                'mobile_no' => $request->mobile_no,
            ]);


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

            foreach ($request->products as $product) {
                InvoiceProduct::create([
                    'invoice_id'  => $invoice->id,
                    'product_id'  => $product['product_id'],
                    'quantity'    => $product['quantity'],
                    'price'       => $product['price'],
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Invoice created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }
}
