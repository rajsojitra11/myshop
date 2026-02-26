<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .login-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
        }

        .tab-btn {
            flex: 1;
            padding: 12px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-size: 15px;
            color: #64748b;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: #1e3a8a;
            border-bottom-color: #1e3a8a;
        }

        .tab-btn:hover {
            color: #1e3a8a;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .customer-login-info {
            background-color: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #1e3a8a;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side Image -->
        <div class="side-image">
            <img src="img/raj.png" alt="No Image" />
        </div>

        <!-- Login Form -->
        <div class="login-form">
            {{-- Error Message --}}
            @if ($errors->has('login_error') || $errors->has('email_mobile') || $errors->has('password'))
                <div
                    id="login-error"
                    style="background: #dc3545; color: #fff; padding: 12px; border-radius: 5px; margin-bottom: 10px;"
                >
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>

                <script>
                    // Auto hide after 4 seconds
                    setTimeout(function () {
                        let box = document.getElementById('login-error');
                        if (box) {
                            box.style.transition = "opacity 0.5s ease";
                            box.style.opacity = "0";
                            setTimeout(() => box.remove(), 500);
                        }
                    }, 4000);
                </script>
            @endif

            {{-- Success Message --}}
            @if (session('success'))
                <div
                    id="success-message"
                    style="background: #28a745; color: #fff; padding: 12px; border-radius: 5px; margin-bottom: 10px;"
                >
                    {{ session('success') }}
                </div>

                <script>
                    // Auto hide after 3 seconds
                    setTimeout(function () {
                        let box = document.getElementById('success-message');
                        if (box) {
                            box.style.transition = "opacity 0.5s ease";
                            box.style.opacity = "0";
                            setTimeout(() => box.remove(), 500);
                        }
                    }, 3000);
                </script>
            @endif

            <h2>Welcome to Myshop</h2>

            <!-- Login Tabs -->
            <div class="login-tabs">
                <button class="tab-btn active" data-tab="admin-login">Admin Login</button>
                <button class="tab-btn" data-tab="customer-login">Customer Login</button>
                <button class="tab-btn" data-tab="supplier-login">Supplier Login</button>
            </div>

            <!-- Admin Login Tab -->
            <div id="admin-login" class="tab-content active">
                <form action="{{ route('index') }}" method="POST">
                    @csrf

                    <div class="input-group">
                        <label for="username">Username or email</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                        />
                    </div>

                    <div class="input-group" style="position: relative;">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            style="padding-right: 35px;"
                        />

                        <!-- Eye Icon -->
                        <span
                            id="togglePassword"
                            style="position: absolute; right: 30px; top: 38px; cursor: pointer; color: #555;"
                        >
                            👁️
                        </span>
                    </div>

                    <script>
                        const togglePassword = document.getElementById("togglePassword");
                        const passwordInput = document.getElementById("password");

                        togglePassword.addEventListener("click", function () {
                            const type = passwordInput.type === "password" ? "text" : "password";
                            passwordInput.type = type;

                            // Toggle icon text
                            this.textContent = type === "password" ? "👁️" : "🙈";
                        });
                    </script>

                    <div class="remember-forgot">
                        <label for="remember">
                            <input type="checkbox" id="remember" name="remember" /> Remember Me
                        </label>
                        <a href="#">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn">Sign in</button>
                </form>

                {{-- <div class="signup-link">
                    Don't have an account? <a href="{{ route('register') }}">Sign up</a>
                </div> --}}
            </div>

            <!-- Customer Login Tab -->
            <div id="customer-login" class="tab-content">
                <div class="customer-login-info">
                    <strong>📧 Use your Email or Mobile Number to login</strong><br>
                    Enter the email or mobile number from your invoice, and use your mobile number as password.
                </div>

                <form action="{{ route('customer.login') }}" method="POST">
                    @csrf

                    <div class="input-group">
                        <label for="email_mobile">Email or Mobile Number</label>
                        <input
                            type="text"
                            id="email_mobile"
                            name="email_mobile"
                            value="{{ old('email_mobile') }}"
                            placeholder="e.g., example@email.com or 9876543210"
                            required
                        />
                    </div>

                    <div class="input-group" style="position: relative;">
                        <label for="customer_password">Password</label>
                        <input
                            type="password"
                            id="customer_password"
                            name="password"
                            placeholder="Enter your mobile number as password"
                            required
                            style="padding-right: 35px;"
                        />

                        <!-- Eye Icon -->
                        <span
                            id="toggleCustomerPassword"
                            style="position: absolute; right: 30px; top: 38px; cursor: pointer; color: #555;"
                        >
                            👁️
                        </span>
                    </div>

                    <script>
                        const toggleCustomerPassword = document.getElementById("toggleCustomerPassword");
                        const customerPasswordInput = document.getElementById("customer_password");

                        toggleCustomerPassword.addEventListener("click", function () {
                            const type = customerPasswordInput.type === "password" ? "text" : "password";
                            customerPasswordInput.type = type;

                            // Toggle icon text
                            this.textContent = type === "password" ? "👁️" : "🙈";
                        });
                    </script>

                    <button type="submit" class="btn" style="margin-top: 10px;">View My Invoices</button>
                </form>

                <div class="signup-link">
                    <small>Don't have any invoice? <a href="{{ route('index') }}">Go back to Admin Login</a></small>
                </div>
            </div>

            <!-- Supplier Login Tab -->
            <div id="supplier-login" class="tab-content">
                <div class="customer-login-info">
                    <strong>📧 Use your Email to login</strong><br>
                    Enter your supplier email and use your Supplier ID as password.
                </div>

                <form action="{{ route('supplier.login') }}" method="POST">
                    @csrf

                    <div class="input-group">
                        <label for="supplier_email">Email Address</label>
                        <input
                            type="email"
                            id="supplier_email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="e.g., supplier@example.com"
                            required
                        />
                    </div>

                    <div class="input-group" style="position: relative;">
                        <label for="supplier_password">Password</label>
                        <input
                            type="password"
                            id="supplier_password"
                            name="password"
                            placeholder="Enter your Supplier ID as password"
                            required
                            style="padding-right: 35px;"
                        />

                        <!-- Eye Icon -->
                        <span
                            id="toggleSupplierPassword"
                            style="position: absolute; right: 30px; top: 38px; cursor: pointer; color: #555;"
                        >
                            👁️
                        </span>
                    </div>

                    <script>
                        const toggleSupplierPassword = document.getElementById("toggleSupplierPassword");
                        const supplierPasswordInput = document.getElementById("supplier_password");

                        toggleSupplierPassword.addEventListener("click", function () {
                            const type = supplierPasswordInput.type === "password" ? "text" : "password";
                            supplierPasswordInput.type = type;

                            // Toggle icon text
                            this.textContent = type === "password" ? "👁️" : "🙈";
                        });
                    </script>

                    <button type="submit" class="btn" style="margin-top: 10px;">View Profile</button>
                </form>

                <div class="signup-link">
                    <small>Don't have a supplier account? <a href="{{ route('index') }}">Go back to Admin Login</a></small>
                </div>
            </div>

            <script>
                // Tab switching functionality
                const tabButtons = document.querySelectorAll('.tab-btn');
                const tabContents = document.querySelectorAll('.tab-content');

                tabButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const tabName = button.getAttribute('data-tab');

                        // Remove active class from all tabs and contents
                        tabButtons.forEach(btn => btn.classList.remove('active'));
                        tabContents.forEach(content => content.classList.remove('active'));

                        // Add active class to clicked tab and corresponding content
                        button.classList.add('active');
                        document.getElementById(tabName).classList.add('active');
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>

