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

        .warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 12px;
            font-size: 0.85rem;
            color: #92400e;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="logo">
            <h2>🌱 Swaratani IoT</h2>
        </div>
        <h3>Reset Password</h3>
        <p>Halo <strong>{{ $username }}</strong>,</p>
        <p>Kami menerima permintaan untuk reset password akun Anda. Klik tombol di bawah untuk membuat password baru:
        </p>

        <div style="text-align: center;">
            <a href="{{ url('/password/reset/' . $token . '?email=' . urlencode($email)) }}" class="btn">
                Reset Password
            </a>
        </div>

        <div class="warning">
            ⏰ Link ini berlaku selama <strong>60 menit</strong>. Jika Anda tidak meminta reset password, abaikan email
            ini.
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Swaratani IoT &bull; Tim Engineering Pertanian</p>
        </div>
    </div>
</body>

</html>