@extends('admin.index')
@section('title', 'Invoice Details')
@section('page-title', 'Invoice Details')
@section('page', 'Invoice')

@section('content')
<div class="container mt-4">
    <div class="invoice p-3 mb-3 border">
        <!-- Title -->
        <div class="row">
            <div class="col-12">
                <h4 class="text-center"><i class="fas fa-globe"></i> My Shop</h4>
                <span class="float-right">Date: {{ $invoice->created_at->format('d/m/Y') }}</span>
                <hr>
            </div>
        </div>

        <!-- Customer & Invoice Info -->
        <div class="row invoice-info">
            <div class="col-md-6">
                <h5>Customer Information</h5>
                <p><strong>Name:</strong> {{ $invoice->to_name }}</p>
                <p><strong>Address:</strong> {{ $invoice->to_address }}</p>
                <p><strong>Email:</strong> {{ $invoice->to_email }}</p>
                <p><strong>Mobile:</strong> {{ $invoice->mobile_no }}</p>
            </div>
            <div class="col-md-6">
                <h5>Invoice Details</h5>
                <p><strong>Bill No:</strong> {{ $invoice->bill_no }}</p>
                <p><strong>Invoice No:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Order ID:</strong> {{ $invoice->order_id }}</p>
                <p><strong>Account:</strong> {{ $invoice->account_number }}</p>
                <p><strong>Payment Method:</strong> {{ $invoice->payment_method }}</p>
            </div>
        </div>

        <!-- Product Table -->
        <div class="row mt-4">
            <div class="col-12 table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Rate</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->products as $product)
                        <tr>
                            <td>{{ $product->serial }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->qty }}</td>
                            <td>₹ {{ number_format($product->rate, 2) }}</td>
                            <td>₹ {{ number_format($product->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="row mt-4">
            <div class="col-6">
                <h5>Payment Method</h5>
                <p>{{ $invoice->payment_method }}</p>
            </div>
            <div class="col-6">
                <h5>Amount Summary</h5>
                <table class="table">
                    <tr>
                        <th>Subtotal:</th>
                        <td>₹ {{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Tax:</th>
                        <td>₹ {{ number_format($invoice->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Shipping:</th>
                        <td>₹ {{ number_format($invoice->shipping, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td>₹ {{ number_format($invoice->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Print Button -->
        <div class="row mt-4">
            <div class="col-12 text-right">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
