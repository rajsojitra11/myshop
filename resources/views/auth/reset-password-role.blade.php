<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - {{ ucfirst($type) }} | Myshop</title>
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

        .reset-icon {
            text-align: center;
            margin-bottom: 10px;
        }
        .reset-icon .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            margin-bottom: 10px;
        }
        .reset-icon .icon-circle svg {
            width: 32px;
            height: 32px;
            fill: #fff;
        }

        .role-badge {
            display: inline-block;
            background: linear-gradient(135deg,
                {{ $type === 'customer' ? '#0891b2, #06b6d4' : '#7c3aed, #8b5cf6' }}
            );
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .reset-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .password-wrapper {
            position: relative;
        }
        .password-wrapper .toggle-pw {
            position: absolute;
            right: 30px;
            top: 38px;
            cursor: pointer;
            color: #555;
            user-select: none;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 6px;
            background: #e2e8f0;
        }
        .password-strength .bar {
            height: 100%;
            border-radius: 2px;
            transition: width 0.3s, background-color 0.3s;
            width: 0%;
        }

        .strength-text {
            font-size: 12px;
            margin-top: 4px;
            color: #64748b;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #dcfce7;
            color: #166534;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
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

            <div class="reset-icon">
                <span class="role-badge">{{ ucfirst($type) }}</span>
                <div class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                </div>
            </div>

            <h2 style="margin-bottom: 10px;">Reset Password</h2>
            <p class="reset-subtitle">
                OTP verified <span class="verified-badge">✓ Verified</span><br>
                Create a strong new password for your account.
            </p>

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

            <form action="{{ route($type . '.password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="input-group password-wrapper">
                    <label for="new_password">New Password</label>
                    <input
                        type="password"
                        id="new_password"
                        name="password"
                        placeholder="Minimum 6 characters"
                        required
                        minlength="6"
                        style="padding-right: 35px;"
                    />
                    <span class="toggle-pw" id="toggleNewPw">👁️</span>

                    <div class="password-strength">
                        <div class="bar" id="strength-bar"></div>
                    </div>
                    <div class="strength-text" id="strength-text"></div>
                </div>

                <div class="input-group password-wrapper">
                    <label for="confirm_password">Confirm Password</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="password_confirmation"
                        placeholder="Re-enter your new password"
                        required
                        minlength="6"
                        style="padding-right: 35px;"
                    />
                    <span class="toggle-pw" id="toggleConfirmPw">👁️</span>

                    <div class="strength-text" id="match-text" style="color: #ef4444;"></div>
                </div>

                <button type="submit" class="btn" style="margin-top: 10px;">
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('toggleNewPw').addEventListener('click', function () {
            const input = document.getElementById('new_password');
            input.type = input.type === 'password' ? 'text' : 'password';
            this.textContent = input.type === 'password' ? '👁️' : '🙈';
        });

        document.getElementById('toggleConfirmPw').addEventListener('click', function () {
            const input = document.getElementById('confirm_password');
            input.type = input.type === 'password' ? 'text' : 'password';
            this.textContent = input.type === 'password' ? '👁️' : '🙈';
        });

        // Password strength indicator
        const newPwInput = document.getElementById('new_password');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        newPwInput.addEventListener('input', function () {
            const val = this.value;
            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
            if (/\d/.test(val)) score++;
            if (/[^a-zA-Z0-9]/.test(val)) score++;

            const levels = [
                { width: '0%',   color: '#e2e8f0', text: '' },
                { width: '20%',  color: '#ef4444', text: 'Very Weak' },
                { width: '40%',  color: '#f97316', text: 'Weak' },
                { width: '60%',  color: '#eab308', text: 'Fair' },
                { width: '80%',  color: '#22c55e', text: 'Strong' },
                { width: '100%', color: '#16a34a', text: 'Very Strong' },
            ];

            const level = levels[score];
            strengthBar.style.width = level.width;
            strengthBar.style.backgroundColor = level.color;
            strengthText.textContent = level.text;
            strengthText.style.color = level.color;
        });

        // Password match check
        const confirmPwInput = document.getElementById('confirm_password');
        const matchText = document.getElementById('match-text');

        confirmPwInput.addEventListener('input', function () {
            if (this.value && this.value !== newPwInput.value) {
                matchText.textContent = 'Passwords do not match';
                matchText.style.color = '#ef4444';
            } else if (this.value && this.value === newPwInput.value) {
                matchText.textContent = '✓ Passwords match';
                matchText.style.color = '#22c55e';
            } else {
                matchText.textContent = '';
            }
        });
    </script>
</body>
</html>
