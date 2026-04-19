<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password - Myshop</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <style>
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #1e3a8a;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            transition: color 0.3s;
        }
        .back-link:hover { color: #2563eb; }

        .forgot-icon {
            text-align: center;
            margin-bottom: 10px;
        }
        .forgot-icon .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            margin-bottom: 10px;
        }
        .forgot-icon .icon-circle svg {
            width: 32px;
            height: 32px;
            fill: #fff;
        }

        .forgot-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #22c55e;
            font-size: 14px;
        }
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #ef4444;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container" style="max-width: 480px;">
        <div class="login-form" style="padding-left: 0; width: 100%;">

            <a href="{{ route('login') }}" class="back-link">
                ← Back to Login
            </a>

            <div class="forgot-icon">
                <div class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                </div>
            </div>

            <h2 style="margin-bottom: 10px;">Forgot Password?</h2>
            <p class="forgot-subtitle">
                No worries! Enter your registered email address and we'll send you an OTP to reset your password.
            </p>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert-success" id="success-msg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert-error" id="error-msg">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                <script>
                    setTimeout(function () {
                        let box = document.getElementById('error-msg');
                        if (box) {
                            box.style.transition = "opacity 0.5s ease";
                            box.style.opacity = "0";
                            setTimeout(() => box.remove(), 500);
                        }
                    }, 5000);
                </script>
            @endif

            <form action="{{ route('password.send-otp') }}" method="POST">
                @csrf

                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your registered email"
                        required
                    />
                </div>

                <button type="submit" class="btn" id="send-otp-btn">
                    Send OTP
                </button>
            </form>

            <div class="signup-link">
                <small>Remember your password? <a href="{{ route('login') }}">Sign in</a></small>
            </div>
        </div>
    </div>
</body>
</html>
