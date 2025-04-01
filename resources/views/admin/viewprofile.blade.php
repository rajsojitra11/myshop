@extends('admin.index')
@section('title','View Supplier')
@section('page-title', 'Supplier Details')
@section('page', 'Supplier')

@section('content')
  <!-- Profile Form -->
  <div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <form action="#" method="POST">
            @csrf
            <table class="table table-bordered text-center">
                <tbody>
                    <!-- Profile Image & Name Row -->
                    <tr>
                        <td colspan="4">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     src="../../dist/img/avatar5.jpg"
                                     alt="User profile picture">
                                <h3 class="profile-username mt-2">{{ $supplier->company_name }}</h3>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Supplier ID</th>
                        <td><input type="text" class="form-control" name="supplier_id" value="{{ $supplier->supplier_id }}" readonly></td>
                        <th>Company Name</th>
                        <td><input type="text" class="form-control" name="company_name" value="{{ $supplier->company_name }}" readonly></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><input type="email" class="form-control" name="email" value="{{ $supplier->email }}" readonly></td>
                        <th>Address</th>
                        <td><input type="text" class="form-control" name="address" value="{{ $supplier->address }}" readonly></td>
                    </tr>
                    <tr>
                        <th>Contact No.</th>
                        <td><input type="text" class="form-control" name="contact_no" value="{{ $supplier->contact_no }}" readonly></td>
                        <th>Country</th>
                        <td><input type="text" class="form-control" name="country" value="{{ $supplier->country }}" readonly></td>
                    </tr>
                    <tr>
                        <th>Bank Details</th>
                        <td><input type="text" class="form-control" name="bank_details" value="{{ $supplier->bank_details }}" readonly></td>
                        <th>Product Categories</th>
                        <td><input type="text" class="form-control" name="product_categories" value="{{ $supplier->product_categories }}" readonly></td>
                    </tr>
                </tbody>
            </table>
  
            <a href="{{ route('supplier') }}" class="btn btn-primary btn-block">Close</a>
        </form>
    </div>
  </div>
@endsection
