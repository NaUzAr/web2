<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#dc2626">
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <title>Email Terverifikasi - Swaratani IoT</title>

    @include('partials.theme')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .verified-box {
            text-align: center;
            padding: 2rem;
        }

        .verified-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }

        .verified-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .verified-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .verified-desc {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.5rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
            color: white;
        }

        .btn-outline-green {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.7rem 1.5rem;
            border-radius: 12px;
            background: transparent;
            border: 2px solid #10b981;
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-outline-green:hover {
            transform: translateY(-2px);
            background: #10b981;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
            color: white;
        }

        .btn-blue {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.7rem 1.5rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            border: none;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.35);
            color: white;
        }

        /* ========= Mobile Responsive ========= */
        @media (max-width: 576px) {
            .verified-box {
                padding: 1.5rem;
            }

            .verified-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 1rem;
            }

            .verified-icon i {
                font-size: 2rem;
            }

            .verified-title {
                font-size: 1.25rem;
            }

            .verified-desc {
                font-size: 0.85rem;
            }

            .btn-login {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <div class="verified-box">
        <div class="verified-icon">
            <i class="bi bi-check-lg"></i>
        </div>
        <h1 class="verified-title">Email Anda Sudah Diverifikasi!</h1>
        <p class="verified-desc">Akun Anda telah aktif. Silakan pilih di platform mana Anda ingin melanjutkan.</p>
        
        <div class="d-flex flex-column gap-3 mx-auto mt-4" style="max-width: 250px;">
            <button onclick="openSwarataniApp(event)" class="btn-blue w-100">
                <i class="bi bi-phone"></i>
                Buka Aplikasi Android
            </button>
            <a href="{{ route('login') }}" class="btn-outline-green w-100">
                <i class="bi bi-globe"></i>
                Lanjut di Web
            </a>
        </div>
    </div>

    <script>
        function openSwarataniApp(e) {
            e.preventDefault();
            
            var packageName = "id.swaratani.swaratani_mobile";
            var playStoreLink = "https://play.google.com/store/apps/details?id=" + packageName;
            
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
            
            if (/android/i.test(userAgent)) {
                var intentUrl = "intent://#Intent;scheme=swaratani;package=" + packageName + ";S.browser_fallback_url=" + encodeURIComponent(playStoreLink) + ";end";
                window.location.href = intentUrl;
            } else {
                window.location.href = playStoreLink;
            }
        }
    </script>
</body>

</html>