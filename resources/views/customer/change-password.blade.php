@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-lock"></i> Change Password
                    </h3>
                </div>
                <div class="card-body p-5">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('customer.password.update') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-key"></i> Current Password
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       placeholder="Enter your current password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-lock-open"></i> New Password
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password" 
                                       placeholder="Enter your new password (minimum 6 characters)"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label">
                                <i class="fas fa-lock-open"></i> Confirm New Password
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation" 
                                       placeholder="Confirm your new password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="togglePassword('new_password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border-radius: 10px 10px 0 0 !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        border: none;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid #ddd;
        padding: 12px 15px;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .input-group .btn-outline-secondary {
        border-radius: 0 6px 6px 0;
    }

    .alert {
        border-radius: 6px;
    }
</style>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = event.target.closest('button');
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
