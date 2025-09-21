@extends('admin.index')
@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page', 'Customers')

@section('content')
<div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Mobile No.</th>
                <th>Bill No.</th>
                <th>Payment Method</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->to_name }}</td>
                    <td>{{ $invoice->mobile_no }}</td>
                    <td>{{ $invoice->bill_no }}</td>
                    <td>{{ $invoice->payment_method }}</td>
                    <td>₹ {{ number_format($invoice->total, 2) }}</td>
                    <td>
                        <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
