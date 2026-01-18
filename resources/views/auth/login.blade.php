<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Smart Agriculture</title>
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

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
        }

        .login-title {
            color: #fff;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 12px;
        }

        .link-green {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .link-green:hover {
            color: var(--primary);
        }

        .text-muted-light {
            color: rgba(255, 255, 255, 0.5);
        }

        .divider {
            border-top: 1px solid var(--glass-border);
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <div class="login-card">
        <div class="text-center mb-4">
            <div class="login-icon">
                <i class="bi bi-tree-fill text-white"></i>
            </div>
            <h4 class="login-title">Selamat Datang!</h4>
            <p class="login-subtitle">Login ke Smart Agriculture System</p>
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

        <form action="{{ route('login.perform') }}{{ request('pwa') ? '?pwa=1' : '' }}" method="POST">
            @csrf
            @if(request('pwa'))
                <input type="hidden" name="pwa" value="1">
            @endif

            <div class="mb-3">
                <label for="username" class="form-label">
                    <i class="bi bi-person me-1"></i>Username
                </label>
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}"
                    placeholder="Masukkan username" required autofocus>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-1"></i>Password
                </label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Masukkan password" required>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-gradient">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </div>
        </form>

        <div class="text-center">
            <p class="text-muted-light small mb-2">Belum punya akun?</p>
            <a href="{{ route('register') }}" class="link-green">Buat Akun Baru</a>

            @if(!request('pwa') && !session('is_pwa'))
                <div class="mt-4 pt-3 divider">
                    <a href="{{ route('home') }}" class="text-muted-light small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.pwa-scripts')
</body>

</html>