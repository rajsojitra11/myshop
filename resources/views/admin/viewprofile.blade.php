@extends('admin.index')
@section('title','View Supplier')
@section('page-title', 'Supplier Detail')
@section('page', 'Supplier')

@section('content')
  <!-- Profile Form -->
<div class="card card-primary card-outline">
    <div class="card-body box-profile">
      <form action="#" method="POST">
        <table class="table table-bordered text-center">
          <tbody>
            <!-- Profile Image & Name Row -->
            <tr>
              <td colspan="4">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="../../dist/img/user4-128x128.jpg"
                       alt="User profile picture">
                  <h3 class="profile-username mt-2">Nina Mcintire</h3>
                </div>
              </td>
            </tr>
            
            <tr>
              <th>Supplier ID</th>
              <td><input type="text" class="form-control" name="name" value="admin"></td>
              <th>Company Name</th>
              <td><input type="text" class="form-control" name="cname" value="admin"></td>
            </tr>
            <tr>
              <th>Email</th>
              <td><input type="email" class="form-control" name="email" value="admin@gmail.com"></td>
              <th>Address</th>
              <td><input type="text" class="form-control" name="address" value="admin@gmail.com"></td>
            </tr>
            <tr>
              <th>Contact No.</th>
              <td><input type="text" class="form-control" name="mobile"></td>
              <th>Country</th>
              <td><input type="text" class="form-control" name="country"></td>
            </tr>
            <tr>
              <th>Bank Details</th>
              <td><input type="text" class="form-control" name="bankdetails"></td>
              <th>Product Categories</th>
              <td><input type="text" class="form-control" name="productcategories"></td>
            </tr>
          </tbody>
        </table>
  
        <a href="{{Route('supplier')}}" class="btn btn-primary btn-block"><i class=""></i>Close</a>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  

       
@endsection
