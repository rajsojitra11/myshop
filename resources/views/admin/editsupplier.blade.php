@extends('admin.index')
@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier Details')
@section('page', 'Supplier')

@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Edit Supplier: {{ $supplier->company_name }}</h3>
    </div>
    <div class="card-body box-profile">
        <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Supplier ID</th>
                        <td><input type="text" class="form-control @error('supplier_id') is-invalid @enderror" name="supplier_id" value="{{ old('supplier_id', $supplier->supplier_id) }}" readonly></td>
                        <th>Company Name</th>
                        <td><input type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name', $supplier->company_name) }}" required></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $supplier->email) }}" required></td>
                        <th>Address</th>
                        <td><input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $supplier->address) }}" required></td>
                    </tr>
                    <tr>
                        <th>Contact No.</th>
                        <td><input type="text" class="form-control @error('contact_no') is-invalid @enderror" name="contact_no" value="{{ old('contact_no', $supplier->contact_no) }}" required></td>
                        <th>Country</th>
                        <td><input type="text" class="form-control @error('country') is-invalid @enderror" name="country" value="{{ old('country', $supplier->country) }}" required></td>
                    </tr>
                    <tr>
                        <th>Bank Details</th>
                        <td><input type="text" class="form-control @error('bank_details') is-invalid @enderror" name="bank_details" value="{{ old('bank_details', $supplier->bank_details) }}" required></td>
                        <th>Product Categories</th>
                        <td><input type="text" class="form-control @error('product_categories') is-invalid @enderror" name="product_categories" value="{{ old('product_categories', $supplier->product_categories) }}" required></td>
                    </tr>
                </tbody>
            </table>

            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('supplier.show', $supplier->id) }}" class="btn btn-secondary btn-md">Cancel</a>
                <button type="submit" class="btn btn-primary btn-md">Update Supplier</button>
            </div>
        </form>
    </div>
</div>

@endsection
