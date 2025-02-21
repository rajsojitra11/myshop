@extends('admin.index')
@section('title','Profile')
@section('page-title', 'Update profile')
@section('page', 'Setting')

@section('content')
    
   <!-- Profile Form -->
<div class="card card-primary card-outline">
  <div class="card-body box-profile">
      <div class="text-center">
          <img class="profile-user-img img-fluid img-circle"
               src="../../dist/img/user4-128x128.jpg"
               alt="User profile picture">
      </div>

      <h3 class="profile-username text-center">Nina Mcintire</h3>

      <form action="#" method="POST">
          <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
              <b>Name</b>
              <input type="name" class="form-control float-right" name="following" value="admin">
          </li>
              <li class="list-group-item">
                  <b>Email</b>
                  <input type="email" class="form-control float-right" name="following" value="admin@gmail.com">
              </li>
              <li class="list-group-item">
                  <b>Old password</b>
                  <input type="text" class="form-control float-right" name="friends" >
              </li>
              <li class="list-group-item">
                <b>New password</b>
                <input type="text" class="form-control float-right" name="friends" >
            </li>
          </ul>

          <button type="submit" class="btn btn-primary btn-block"><b>Update</b></button>
      </form>
  </div>
  <!-- /.card-body -->
</div>

       
@endsection
