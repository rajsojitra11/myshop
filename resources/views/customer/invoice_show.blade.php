@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="mb-4">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Invoices
        </a>
        <a href="{{ route('customer.logout') }}" class="btn btn-danger float-end">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="invoice p-4 mb-3 border rounded shadow-sm" style="background-color: white;">
        <!-- Title -->
        <div class="row">
            <div class="col-12">
                <h4 class="text-center"><i class="fas fa-globe"></i> My Shop</h4>
                <span class="float-end">Date: {{ $invoice->created_at->format('d/m/Y') }}</span>
                <hr>
            </div>
        </div>

        <!-- Customer & Invoice Info -->
        <div class="row invoice-info">
            <div class="col-md-6">
                <h5><strong>Customer Information</strong></h5>
                <p><strong>Name:</strong> {{ $invoice->to_name }}</p>
                <p><strong>Address:</strong> {{ $invoice->to_address }}</p>
                <p><strong>Email:</strong> {{ $invoice->to_email }}</p>
                <p><strong>Mobile:</strong> {{ $invoice->mobile_no }}</p>
            </div>
            <div class="col-md-6">
                <h5><strong>Invoice Details</strong></h5>
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
                    <thead class="table-light">
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
                            <td>{{ $product->serial ?? '-' }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description ?? '-' }}</td>
                            <td class="text-center">{{ $product->qty }}</td>
                            <td class="text-end">₹ {{ number_format($product->rate, 2) }}</td>
                            <td class="text-end">₹ {{ number_format($product->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="row mt-4">
            <div class="col-md-6">
                <h5><strong>Payment Method</strong></h5>
                <p>{{ $invoice->payment_method }}</p>
            </div>
            <div class="col-md-6">
                <h5><strong>Amount Summary</strong></h5>
                <table class="table table-sm">
                    <tr>
                        <th>Subtotal:</th>
                        <td class="text-end"><strong>₹ {{ number_format($invoice->subtotal, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tax:</th>
                        <td class="text-end"><strong>₹ {{ number_format($invoice->tax, 2) }}</strong></td>
                    </tr>
                    @if($invoice->shipping)
                    <tr>
                        <th>Shipping:</th>
                        <td class="text-end"><strong>₹ {{ number_format($invoice->shipping, 2) }}</strong></td>
                    </tr>
                    @endif
                    <tr class="table-active">
                        <th>Total:</th>
                        <td class="text-end"><strong style="font-size: 1.1rem; color: #667eea;">₹ {{ number_format($invoice->total, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Print Button -->
        <div class="row mt-4">
            <div class="col-12 text-end">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }

    .invoice {
        border-color: #dee2e6;
    }

    .btn-primary {
        background-color: #667eea;
        border-color: #667eea;
    }

    .btn-primary:hover {
        background-color: #764ba2;
        border-color: #764ba2;
    }

    @media print {
        .btn, .btn-group, .container > .btn {
            display: none !important;
        }

        body {
            background-color: white;
        }

        .invoice {
            box-shadow: none;
        }
    }
</style>
@endsection
