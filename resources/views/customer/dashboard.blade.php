@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-invoice"></i> My Invoices</h2>
                <a href="{{ route('customer.logout') }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($invoices->count() > 0)
                <div class="mb-3 d-flex justify-content-end">
                    <div class="input-group search-group" style="max-width: 350px;">
                        <span class="input-group-text search-icon"><i class="fas fa-search"></i></span>
                        <input type="text" id="invoiceSearch" class="form-control border-start-0 search-input" aria-label="Search invoices">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="invoiceTable">
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
    .search-group {
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.10);
        border-radius: 6px;
        background: #fff;
    }
    .search-icon {
        background: #667eea;
        color: #fff;
        border-radius: 6px 0 0 6px;
        border-right: 0;
        font-size: 1.1rem;
    }
    .search-input {
        border-radius: 0 6px 6px 0;
        border-left: 0;
        box-shadow: none;
        background: #f8f9fa;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('invoiceSearch');
        const table = document.getElementById('invoiceTable');
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const invoiceNumber = row.children[0].innerText.toLowerCase();
                const billNo = row.children[1].innerText.toLowerCase();
                const date = row.children[2].innerText.toLowerCase();
                if (
                    invoiceNumber.includes(filter) ||
                    billNo.includes(filter) ||
                    date.includes(filter)
                ) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
