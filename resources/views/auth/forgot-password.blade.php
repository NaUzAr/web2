<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Swaratani</title>
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

        .forgot-card {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .forgot-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem;
            color: white;
        }

        .forgot-title {
            color: var(--text-main, #333);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .forgot-subtitle {
            color: var(--text-secondary, #666);
            font-size: 0.9rem;
        }

        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 12px;
        }

        .alert-success-custom {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
            border-radius: 12px;
        }

        .link-green {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
        }

        .link-green:hover {
            color: var(--primary);
        }

        .forgot-card .form-control {
            font-size: 16px;
            min-height: 48px;
            padding: 0.75rem 1rem;
            border-radius: 12px;
        }

        .forgot-card .btn-gradient {
            min-height: 48px;
            font-size: 1rem;
            border-radius: 50px;
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem;
                align-items: flex-start;
                padding-top: 2rem;
            }

            .forgot-card {
                padding: 1.75rem 1.5rem;
                border-radius: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <div class="forgot-card">
        <div class="text-center mb-4">
            <div class="forgot-icon">
                <i class="bi bi-key"></i>
            </div>
            <h4 class="forgot-title">Lupa Password?</h4>
            <p class="forgot-subtitle">Masukkan email Anda untuk menerima link reset password</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success-custom mb-4">
                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger-custom mb-4">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope me-1"></i>Email
                </label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    placeholder="Masukkan email Anda" required autofocus>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-gradient">
                    <i class="bi bi-send me-2"></i>Kirim Link Reset
                </button>
            </div>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="link-green">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
            </a>
        </div>
    </div>
</body>

</html>