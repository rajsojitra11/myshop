@extends('admin.index')
@section('title', 'Profile')
@section('page-title', 'Update Profile')
@section('page', 'Setting')

@section('content')

<div class="card card-primary card-outline">
    <div class="card-body box-profile">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        {{-- General Validation Error --}}
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                Please fix the errors below.
            </div>
        @endif --}}

        <!-- Profile Image -->
        <div class="text-center mb-3">

            <img id="previewImage"
                 class="profile-user-img img-fluid img-circle elevation-2"
                 style="width:120px;height:120px;object-fit:cover;"
                 src="{{ Auth::user()->profile_image 
                        ? asset('storage/profile_images/'.Auth::user()->profile_image) 
                        : asset('dist/img/user1-128x128.jpg') }}"
                 alt="User profile picture">

        </div>

        <form action="{{ route('profile.update') }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf

            <!-- Upload Button -->
            <div class="text-center mb-4">
                <label class="btn btn-sm btn-info">
                    Change Profile Image
                    <input type="file"
                           name="profile_image"
                           class="d-none"
                           accept="image/*"
                           onchange="previewFile(event)">
                </label>
                @error('profile_image')
                    <div class="text-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <ul class="list-group list-group-unbordered mb-3">

                <!-- Name -->
                <li class="list-group-item">
                    <b>Name</b>
                    <input type="text" 
                           name="name"
                           value="{{ old('name', Auth::user()->name) }}"
                           class="form-control mt-2 @error('name') is-invalid @enderror">

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </li>

                <!-- Email -->
                <li class="list-group-item">
                    <b>Email</b>
                    <input type="email" 
                           name="email"
                           value="{{ old('email', Auth::user()->email) }}"
                           class="form-control mt-2 @error('email') is-invalid @enderror">

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </li>

                <!-- Old Password -->
                <li class="list-group-item">
                    <b>Old Password</b>
                    <input type="password" 
                           name="old_password"
                           class="form-control mt-2 @error('old_password') is-invalid @enderror">

                    @error('old_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </li>

                <!-- New Password -->
                <li class="list-group-item">
                    <b>New Password</b>
                    <input type="password" 
                           name="new_password"
                           class="form-control mt-2 @error('new_password') is-invalid @enderror">

                    @error('new_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </li>

            </ul>

            <!-- Update Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </div>

        </form>

    </div>
</div>

<!-- Image Preview Script -->
<script>
function previewFile(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('previewImage').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection