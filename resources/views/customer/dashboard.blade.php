@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-invoice"></i> My Invoices</h2>
                <div class="btn-group" role="group">
                    <a href="{{ route('customer.password.form') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-lock"></i> Change Password
                    </a>
                    <a href="{{ route('customer.logout') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Invoice #</th>
                                <th>Bill #</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                    <td>{{ $invoice->bill_no }}</td>
                                    <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                                    <td><strong>₹ {{ number_format($invoice->total, 2) }}</strong></td>
                                    <td>
                                        <span class="badge bg-success">Completed</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.invoice', $invoice->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No invoices found.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }

    .table {
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table-dark {
        background-color: #667eea !important;
    }

    .btn-primary {
        background-color: #667eea;
        border-color: #667eea;
    }

    .btn-primary:hover {
        background-color: #764ba2;
        border-color: #764ba2;
    }
</style>
@endsection
