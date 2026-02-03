<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Device - Swaratani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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

        .add-card {
            width: 100%;
            max-width: 480px;
            padding: 2.5rem;
            border-radius: 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
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
            color: #1f2937;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            color: #64748b;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-label {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--glass-border);
            color: #1f2937;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            transition: all 0.3s ease;
            font-family: monospace;
            font-size: 1.1rem;
            letter-spacing: 2px;
            text-align: center;
        }

        .form-control:focus {
            background: #fff;
            border-color: var(--primary-color);
            color: #1f2937;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }

        .form-control::placeholder {
            color: #94a3b8;
            letter-spacing: 1px;
        }

        .form-control-name {
            font-family: 'Inter', sans-serif;
            letter-spacing: normal;
            text-align: left;
        }

        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #dc2626;
            border-radius: 12px;
        }

        .alert-info-custom {
            background: rgba(14, 165, 233, 0.1);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: #0284c7;
            border-radius: 12px;
        }

        .form-text-light {
            color: #64748b;
        }

        .btn-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: #475569;
            padding: 0.85rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-glass:hover {
            background: rgba(0, 0, 0, 0.05);
            color: #1f2937;
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