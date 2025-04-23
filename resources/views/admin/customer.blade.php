@extends('admin.index')
@section('title', 'customer')
@section('page-title', 'customer')
@section('page', 'customer')

<link rel="stylesheet" href="{{ asset('css/supplier.css') }}">

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
          </tr>
      </thead>
      <tbody>
          @foreach($invoices as $invoice)
              <tr>
                  <td>{{ $invoice->to_name }}</td>
                  <td>{{ $invoice->mobile_no }}</td>
                  <td>{{ $invoice->bill_no }}</td>
                  <td>{{ $invoice->payment_method }}</td>
                  <td>{{ number_format($invoice->total, 2) }}</td>
              </tr>
          @endforeach
      </tbody>
  </table>
</div>
@endsection