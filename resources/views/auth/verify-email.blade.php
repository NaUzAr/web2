<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - {{ env('APP_NAME', 'Swaratani') }}</title>
    @include('partials.pwa-head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @include('partials.theme')

    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .verify-card {
            width: 100%;
            max-width: 480px;
            padding: 2.5rem;
            border-radius: 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .verify-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .verify-title {
            color: var(--text-main);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .verify-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .email-highlight {
            color: var(--primary);
            font-weight: 600;
        }

        .alert-success-custom {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }

        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }

        .btn-resend {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-resend:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(var(--primary-rgb), 0.3);
            color: white;
        }

        .divider {
            border-top: 1px solid var(--glass-border);
            margin: 1.5rem 0;
        }

        .back-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .tips-box {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
            text-align: left;
        }

        .tips-box h6 {
            color: #f59e0b;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .tips-box ul {
            margin: 0;
            padding-left: 1.25rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .tips-box li {
            margin-bottom: 0.25rem;
        }

        /* ========= Mobile Responsive ========= */
        @media (max-width: 576px) {
            body {
                padding: 1rem;
                align-items: flex-start;
                padding-top: 2rem;
            }

            .verify-card {
                padding: 1.75rem 1.5rem;
                border-radius: 20px;
            }

            .verify-icon {
                width: 70px;
                height: 70px;
                font-size: 2.25rem;
                margin-bottom: 1rem;
            }

            .verify-title {
                font-size: 1.25rem;
            }

            .verify-subtitle {
                font-size: 0.85rem;
            }

            .btn-resend {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 400px) {
            body {
                padding: 0.75rem;
                padding-top: 1.5rem;
            }

            .verify-card {
                padding: 1.5rem 1.25rem;
            }
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <div class="verify-card">
        <div class="verify-icon">
            <i class="bi bi-envelope-check text-white"></i>
        </div>

        <h4 class="verify-title">Cek Email Kamu!</h4>

        @php
            $email = session('pending_verification_email') ?? (Auth::check() ? Auth::user()->email : 'email kamu');
        @endphp

        <p class="verify-subtitle">
            Silakan verifikasi email kamu untuk mengaktifkan akun.
            <br>Klik tombol di bawah untuk mengirim link verifikasi ke <span
                class="email-highlight">{{ $email }}</span>.
        </p>

        @if (session('status'))
            <div class="alert-success-custom">
                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert-warning-custom"
                style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: #f59e0b; border-radius: 12px; padding: 0.75rem 1rem; margin-bottom: 1rem;">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-danger-custom">
                <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('verification.resend') }}" method="POST" id="resendForm">
            @csrf
            <button type="submit" class="btn btn-resend" id="resendBtn">
                <i class="bi bi-envelope-paper me-2" id="resendIcon"></i>
                <span id="btnText">Kirim Email Verifikasi</span>
            </button>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const btn = document.getElementById('resendBtn');
                const btnText = document.getElementById('btnText');
                const icon = document.getElementById('resendIcon');
                const form = document.getElementById('resendForm');
                const COOLDOWN_KEY = 'emailResendCooldown';
                const COOLDOWN_SECONDS = 60;

                function startCooldown() {
                    const endTime = Date.now() + (COOLDOWN_SECONDS * 1000);
                    localStorage.setItem(COOLDOWN_KEY, endTime);
                    updateButton();
                }

                function updateButton() {
                    const endTime = localStorage.getItem(COOLDOWN_KEY);
                    if (endTime && Date.now() < parseInt(endTime)) {
                        const remaining = Math.ceil((parseInt(endTime) - Date.now()) / 1000);
                        btn.disabled = true;
                        btn.style.opacity = '0.6';
                        btn.style.cursor = 'not-allowed';
                        btnText.textContent = `Tunggu ${remaining} detik`;
                        icon.className = 'bi bi-hourglass-split';
                        setTimeout(updateButton, 1000);
                    } else {
                        localStorage.removeItem(COOLDOWN_KEY);
                        btn.disabled = false;
                        btn.style.opacity = '1';
                        btn.style.cursor = 'pointer';
                        btnText.textContent = 'Kirim Email Verifikasi';
                        icon.className = 'bi bi-envelope-paper me-2';
                    }
                }

                form.addEventListener('submit', function () {
                    startCooldown();
                });

                // Check on page load
                updateButton();
            });
        </script>

        <div class="tips-box">
            <h6><i class="bi bi-lightbulb me-1"></i>Tips:</h6>
            <ul>
                <li>Cek folder <strong>Spam/Junk</strong> jika tidak ada di inbox</li>
                <li>Pastikan email <strong>{{ $email }}</strong> sudah benar</li>
                <li>Link verifikasi berlaku selama 60 menit</li>
            </ul>
        </div>

        <div class="divider"></div>

        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>