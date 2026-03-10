<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - {{ $supplier->company_name }}</title>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .supplier-navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .navbar-brand h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
            font-weight: 600;
        }

        .navbar-brand .supplier-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .navbar-actions {
            display: flex;
            gap: 15px;
        }

        .navbar-actions a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .navbar-actions a:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .navbar-actions .back-btn {
            background: #6c757d;
        }

        .navbar-actions .back-btn:hover {
            background: #5a6268;
        }

        .container {
            padding: 40px 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
            margin-top: 30px;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 25px;
            border: none;
        }

        .card-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .card-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .alert {
            border-radius: 6px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .password-input-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #667eea;
        }

        .form-control.with-toggle {
            padding-right: 45px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="supplier-navbar">
        <div class="navbar-content">
            <div class="navbar-brand">
                <div class="supplier-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h2>Supplier Portal</h2>
            </div>
            <div class="navbar-actions">
                <a href="{{ route('supplier.dashboard') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="{{ route('supplier.logout') }}">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-lock"></i> Change Password</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Error!</strong>
                        <ul style="margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('supplier.password.update') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   class="form-control with-toggle @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   placeholder="Enter your current password"
                                   required>
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('current_password')"></i>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   class="form-control with-toggle @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password" 
                                   placeholder="Enter your new password (minimum 6 characters)"
                                   required>
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password')"></i>
                        </div>
                        @error('new_password')
                            <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   class="form-control with-toggle @error('new_password_confirmation') is-invalid @enderror" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   placeholder="Confirm your new password"
                                   required>
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password_confirmation')"></i>
                        </div>
                        @error('new_password_confirmation')
                            <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.target;
            
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

    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
</body>
</html>
