<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify OTP - {{ ucfirst($type) }} | Myshop</title>
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

        .otp-icon {
            text-align: center;
            margin-bottom: 10px;
        }
        .otp-icon .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg,
                {{ $type === 'customer' ? '#0891b2, #06b6d4' : '#7c3aed, #8b5cf6' }}
            );
            margin-bottom: 10px;
        }
        .otp-icon .icon-circle svg {
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

        .otp-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        .otp-subtitle strong {
            color: #1e3a8a;
        }

        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 25px;
        }
        .otp-inputs input {
            width: 48px;
            height: 52px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            border: 2px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
            color: #1e3a8a;
        }
        .otp-inputs input:focus {
            border-color: {{ $type === 'customer' ? '#06b6d4' : '#8b5cf6' }};
            box-shadow: 0 0 0 3px {{ $type === 'customer' ? 'rgba(6, 182, 212, 0.15)' : 'rgba(139, 92, 246, 0.15)' }};
        }

        .resend-section {
            text-align: center;
            margin-top: 20px;
        }
        .resend-section span {
            color: #64748b;
            font-size: 13px;
        }
        .resend-btn {
            background: none;
            border: none;
            color: #1e3a8a;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
            text-decoration: underline;
            transition: color 0.3s;
        }
        .resend-btn:hover { color: #2563eb; }
        .resend-btn:disabled {
            color: #94a3b8;
            cursor: not-allowed;
            text-decoration: none;
        }

        .timer {
            color: {{ $type === 'customer' ? '#0891b2' : '#7c3aed' }};
            font-weight: 600;
            font-size: 13px;
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

            <a href="{{ route($type . '.password.forgot') }}" class="back-link">
                ← Back
            </a>

            <div class="otp-icon">
                <span class="role-badge">{{ ucfirst($type) }}</span>
                <div class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                </div>
            </div>

            <h2 style="margin-bottom: 10px;">Verify OTP</h2>
            <p class="otp-subtitle">
                We've sent a 6-digit OTP to<br>
                <strong>{{ $email }}</strong>
            </p>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

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

            <form action="{{ route($type . '.password.verify-otp') }}" method="POST" id="otp-form">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="otp" id="otp-hidden" value="">

                <div class="otp-inputs">
                    <input type="text" maxlength="1" class="otp-box" data-index="0" inputmode="numeric" autofocus />
                    <input type="text" maxlength="1" class="otp-box" data-index="1" inputmode="numeric" />
                    <input type="text" maxlength="1" class="otp-box" data-index="2" inputmode="numeric" />
                    <input type="text" maxlength="1" class="otp-box" data-index="3" inputmode="numeric" />
                    <input type="text" maxlength="1" class="otp-box" data-index="4" inputmode="numeric" />
                    <input type="text" maxlength="1" class="otp-box" data-index="5" inputmode="numeric" />
                </div>

                <button type="submit" class="btn">Verify OTP</button>
            </form>

            <div class="resend-section">
                <span>Didn't receive the code? </span>
                <form action="{{ route($type . '.password.resend-otp') }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="resend-btn" id="resend-btn" disabled>
                        Resend OTP
                    </button>
                </form>
                <br>
                <span class="timer" id="timer-text">Resend available in <span id="countdown">60</span>s</span>
            </div>
        </div>
    </div>

    <script>
        // OTP input handling
        const otpBoxes = document.querySelectorAll('.otp-box');
        const otpHidden = document.getElementById('otp-hidden');
        const otpForm = document.getElementById('otp-form');

        otpBoxes.forEach((box, index) => {
            box.addEventListener('input', (e) => {
                const val = e.target.value;
                if (!/^\d$/.test(val)) { e.target.value = ''; return; }
                if (val && index < 5) otpBoxes[index + 1].focus();
                collectOtp();
            });

            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !box.value && index > 0) {
                    otpBoxes[index - 1].focus();
                    otpBoxes[index - 1].value = '';
                    collectOtp();
                }
            });

            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                if (/^\d{6}$/.test(pasteData)) {
                    for (let i = 0; i < 6; i++) otpBoxes[i].value = pasteData[i];
                    otpBoxes[5].focus();
                    collectOtp();
                }
            });
        });

        function collectOtp() {
            let otp = '';
            otpBoxes.forEach(box => otp += box.value);
            otpHidden.value = otp;
        }

        otpForm.addEventListener('submit', function(e) {
            collectOtp();
            if (otpHidden.value.length !== 6) {
                e.preventDefault();
                alert('Please enter the complete 6-digit OTP.');
            }
        });

        // Countdown timer
        let timeLeft = 60;
        const countdownEl = document.getElementById('countdown');
        const timerText = document.getElementById('timer-text');
        const resendBtn = document.getElementById('resend-btn');

        const timer = setInterval(() => {
            timeLeft--;
            countdownEl.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                timerText.style.display = 'none';
                resendBtn.disabled = false;
            }
        }, 1000);
    </script>
</body>
</html>
