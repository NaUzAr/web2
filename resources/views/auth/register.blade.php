<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @include('partials.theme')

    <title>Daftar - Smart Agriculture</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-card {
            width: 100%;
            max-width: 450px;
            padding: 2.5rem;
            border-radius: 24px;
        }

        .register-icon {
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

        .text-muted-light {
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <div class="register-card">
        <div class="text-center mb-4">
            <div class="logo-icon">
                <i class="bi bi-person-plus-fill text-white"></i>
            </div>
            <h4 class="register-title">Buat Akun Baru</h4>
            <p class="register-subtitle">Daftar untuk mengakses Smart Agriculture System</p>
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

        <form action="{{ route('register.perform') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-person me-1"></i>Nama Lengkap</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-at me-1"></i>Username</label>
                <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                    placeholder="Masukkan username" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                    placeholder="Masukkan email" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="bi bi-lock me-1"></i>Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Min. 8 karakter" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label"><i class="bi bi-lock-fill me-1"></i>Ulangi Password</label>
                    <input type="password" class="form-control" name="password_confirmation"
                        placeholder="Ulangi password" required>
                </div>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-gradient">
                    <i class="bi bi-person-check me-2"></i>Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="text-center">
            <span class="text-muted-light small">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="link-green ms-1">Login disini</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>