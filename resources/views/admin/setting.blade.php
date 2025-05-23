@extends('admin.index')
@section('title', 'Profile')
@section('page-title', 'Update profile')
@section('page', 'Setting')

@section('content')

<!-- Profile Form -->
<div class="card card-primary card-outline">
  <div class="card-body box-profile">
      <div class="text-center">
          <img class="profile-user-img img-fluid img-circle"
               src="../../dist/img/user1-128x128.jpg"
               alt="User profile picture">
      </div>

      <h3 class="profile-username text-center"></h3>

      <form action="{{ route('profile.update') }}" method="POST">
          @csrf
          <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
              <b>Name</b>
              <input type="text" class="form-control float-right" name="name" value="{{ Auth::user()->name }}">
          </li>
              <li class="list-group-item">
                  <b>Email</b>
                  <input type="email" class="form-control float-right" name="email" value="{{ Auth::user()->email }}">
              </li>
              <li class="list-group-item">
                  <b>Old password</b>
                  <input type="password" class="form-control float-right" name="old_password">
              </li>
              <li class="list-group-item">
                <b>New password</b>
                <input type="password" class="form-control float-right" name="new_password">
            </li>
          </ul>

          <button type="submit" class="btn btn-primary btn-block"><b>Update</b></button>
      </form>
  </div>
  <!-- /.card-body -->
</div>

<!-- SweetAlert2 for Success Message -->
@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        });
    </script>
@endif


@if(session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(() => {
                Swal.fire({
                    title: "Warning!",
                    text: "{{ session('error') }}",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            }, 300); // Small delay to ensure session data is loaded
        });
    </script>
@endif
@endsection
