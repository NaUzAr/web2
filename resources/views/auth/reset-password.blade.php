<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Swaratani</title>
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

        .reset-card {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .reset-icon {
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

        .reset-title {
            color: var(--text-main, #333);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .reset-subtitle {
            color: var(--text-secondary, #666);
            font-size: 0.9rem;
        }

        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 12px;
        }

        .reset-card .form-control {
            font-size: 16px;
            min-height: 48px;
            padding: 0.75rem 1rem;
            border-radius: 12px;
        }

        .reset-card .btn-gradient {
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

            .reset-card {
                padding: 1.75rem 1.5rem;
                border-radius: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <div class="reset-card">
        <div class="text-center mb-4">
            <div class="reset-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h4 class="reset-title">Buat Password Baru</h4>
            <p class="reset-subtitle">Masukkan password baru untuk akun Anda</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger-custom mb-4">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-1"></i>Password Baru
                </label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Minimal 6 karakter" required autofocus>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">
                    <i class="bi bi-lock-fill me-1"></i>Konfirmasi Password
                </label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Ulangi password baru" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-gradient">
                    <i class="bi bi-check-lg me-2"></i>Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</body>

</html>