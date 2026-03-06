<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f1f5f9;
            padding: 2rem;
        }

        .card {
            max-width: 480px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo h2 {
            color: #0e5f8a;
            margin: 0;
        }

        h3 {
            color: #0f172a;
        }

        p {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #0e5f8a, #0284c7);
            color: white !important;
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 1rem 0;
        }

        .footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .sub-text {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 2rem;
            word-break: break-all;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="logo">
            <h2>🌱 Swaratani IoT</h2>
        </div>
        <h3>Konfirmasi Pendaftaran</h3>
        <p>Halo,</p>
        <p>Terima kasih telah mendaftar di Swaratani IoT. Silakan klik tombol di bawah ini untuk memverifikasi alamat
            email Anda dan mengaktifkan akun Anda:</p>

        <div style="text-align: center;">
            <a href="{{ $url }}" class="btn">
                Verifikasi Email
            </a>
        </div>

        <p>Jika Anda tidak mendaftar akun di Swaratani IoT, Anda bisa mengabaikan email ini.</p>

        <div class="sub-text">
            *Jika Anda kesulitan mengklik tombol "Verifikasi Email", copy dan paste URL berikut ke browser web Anda:
            <br>
            <a href="{{ $url }}" style="color: #0284c7;">{{ $url }}</a>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Swaratani IoT &bull; Tim Engineering Pertanian</p>
        </div>
    </div>
</body>

</html>