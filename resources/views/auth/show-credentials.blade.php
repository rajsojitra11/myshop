<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Credentials - Myshop</title>
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

        .success-icon {
            text-align: center;
            margin-bottom: 10px;
        }
        .success-icon .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            margin-bottom: 10px;
            animation: scaleIn 0.5s ease;
        }
        .success-icon .icon-circle svg {
            width: 40px;
            height: 40px;
            fill: #fff;
        }

        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        .credentials-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .role-badge {
            display: inline-block;
            background: linear-gradient(135deg,
                {{ session('credential_type') === 'customer' ? '#0891b2, #06b6d4' : '#7c3aed, #8b5cf6' }}
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

        .credentials-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 25px;
        }

        .credential-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .credential-row:last-child {
            border-bottom: none;
        }

        .credential-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .credential-value {
            font-size: 15px;
            color: #1e293b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .password-hidden {
            letter-spacing: 3px;
            font-size: 18px;
        }

        .toggle-reveal {
            background: none;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 4px 10px;
            cursor: pointer;
            font-size: 12px;
            color: #64748b;
            transition: all 0.3s;
        }
        .toggle-reveal:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .copy-btn {
            background: none;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 4px 10px;
            cursor: pointer;
            font-size: 12px;
            color: #64748b;
            transition: all 0.3s;
        }
        .copy-btn:hover {
            border-color: #22c55e;
            color: #22c55e;
        }
        .copy-btn.copied {
            border-color: #22c55e;
            color: #22c55e;
            background: #dcfce7;
        }

        .warning-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="login-container" style="max-width: 480px;">
        <div class="login-form" style="padding-left: 0; width: 100%;">

            <a href="{{ route('login') }}" class="back-link">
                ← Back to Login
            </a>

            <div class="success-icon">
                <span class="role-badge">{{ ucfirst(session('credential_type', 'user')) }}</span>
                <div class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                </div>
            </div>

            <h2 style="margin-bottom: 10px;">Identity Verified!</h2>
            <p class="credentials-subtitle">
                Your identity has been verified successfully.<br>
                Here are your login credentials:
            </p>

            <div class="credentials-card">
                <div class="credential-row">
                    <span class="credential-label">Name</span>
                    <span class="credential-value">{{ session('user_name') }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">Email</span>
                    <span class="credential-value">{{ session('user_email') }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">{{ session('password_label') }}</span>
                    <span class="credential-value">
                        <span id="password-display" class="password-hidden">••••••••</span>
                        <button class="toggle-reveal" id="toggle-pw" onclick="togglePassword()">Show</button>
                        <button class="copy-btn" id="copy-btn" onclick="copyPassword()">Copy</button>
                    </span>
                </div>
            </div>

            <div class="warning-box">
                ⚠️ Please note down your credentials. This page will not be accessible once you leave.
            </div>

            <a href="{{ route('login') }}" class="btn" style="display: block; text-align: center; text-decoration: none;">
                Go to Login
            </a>
        </div>
    </div>

    <script>
        const actualPassword = @json(session('user_password'));
        let isRevealed = false;

        function togglePassword() {
            const display = document.getElementById('password-display');
            const btn = document.getElementById('toggle-pw');

            if (isRevealed) {
                display.textContent = '••••••••';
                display.classList.add('password-hidden');
                btn.textContent = 'Show';
            } else {
                display.textContent = actualPassword;
                display.classList.remove('password-hidden');
                btn.textContent = 'Hide';
            }
            isRevealed = !isRevealed;
        }

        function copyPassword() {
            navigator.clipboard.writeText(actualPassword).then(() => {
                const btn = document.getElementById('copy-btn');
                btn.textContent = '✓ Copied';
                btn.classList.add('copied');
                setTimeout(() => {
                    btn.textContent = 'Copy';
                    btn.classList.remove('copied');
                }, 2000);
            });
        }
    </script>
</body>
</html>
