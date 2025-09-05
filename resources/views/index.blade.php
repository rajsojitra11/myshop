<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css" />
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
            @if ($errors->has('login_error'))
                <div
                    id="login-error"
                    style="background: #dc3545; color: #fff; padding: 12px; border-radius: 5px; margin-bottom: 10px;"
                >
                    {{ $errors->first('login_error') }}
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

            <div class="signup-link">
                Don't have an account? <a href="{{ route('register') }}">Sign up</a>
            </div>
        </div>
    </div>
</body>
</html>
