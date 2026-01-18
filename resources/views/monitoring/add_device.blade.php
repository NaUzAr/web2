<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Device - Smart Agriculture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-red: #ef4444;
            --dark-red: #991b1b;
            --light-red: #fca5a5;
            --accent-orange: #f97316;
            --primary-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #f97316 100%);
            --nature-gradient: linear-gradient(135deg, #450a0a 0%, #7f1d1d 50%, #991b1b 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--nature-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 20% 80%, rgba(239, 68, 68, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(249, 115, 22, 0.3) 0%, transparent 50%);
        }

        .add-card {
            width: 100%;
            max-width: 480px;
            padding: 2.5rem;
            border-radius: 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .card-icon {
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

        .card-title {
            color: #fff;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            color: rgba(255, 255, 255, 0.6);
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-label {
            color: #fca5a5;
            font-weight: 600;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            color: #fff;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            transition: all 0.3s ease;
            font-family: monospace;
            font-size: 1.1rem;
            letter-spacing: 2px;
            text-align: center;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ef4444;
            color: #fff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 1px;
        }

        .form-control-name {
            font-family: 'Inter', sans-serif;
            letter-spacing: normal;
            text-align: left;
        }

        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: #fff;
            padding: 0.85rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
            color: #fff;
        }

        .btn-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: #fff;
            padding: 0.85rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 12px;
        }

        .alert-info-custom {
            background: rgba(14, 165, 233, 0.15);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: var(--light-sky);
            border-radius: 12px;
        }

        .form-text-light {
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <div class="add-card">
        <div class="card-icon">
            <i class="bi bi-key-fill text-white"></i>
        </div>
        <h4 class="card-title">Tambah Device</h4>
        <p class="card-subtitle">Masukkan token device untuk mulai monitoring</p>

        @if ($errors->any())
            <div class="alert alert-danger-custom mb-4">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('monitoring.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-key me-1"></i>Token Device</label>
                <input type="text" class="form-control" name="token" value="{{ old('token') }}"
                    placeholder="XXXXXXXXXXXXXXXX" maxlength="16" required autofocus>
                <div class="form-text form-text-light">
                    Token terdiri dari 16 karakter. Dapatkan dari admin.
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label"><i class="bi bi-tag me-1"></i>Nama Custom (Opsional)</label>
                <input type="text" class="form-control form-control-name" name="custom_name"
                    value="{{ old('custom_name') }}" placeholder="Contoh: Sensor Kebun Saya">
                <div class="form-text form-text-light">
                    Beri nama untuk memudahkan identifikasi device.
                </div>
            </div>

            <div class="alert alert-info-custom mb-4">
                <small><i class="bi bi-info-circle me-1"></i>
                    Device akan tersimpan di akun Anda dan bisa dilihat kapan saja sampai Anda menghapusnya.
                </small>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-gradient">
                    <i class="bi bi-plus-circle me-2"></i>Tambahkan Device
                </button>
                <a href="{{ route('monitoring.index') }}" class="btn btn-glass text-center">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>

</body>

</html>