<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#dc2626">
    <meta name="description" content="Swaratani IoT - Sistem monitoring pertanian cerdas berbasis IoT">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Swaratani">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-72.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/icons/icon-96.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <title>Swaratani IoT - Dashboard</title>

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
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Floating Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatUp 15s infinite linear;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) rotate(720deg);
                opacity: 0;
            }
        }

        /* Main Content */
        .main-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            z-index: 1;
        }

        /* Header Section */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 20px 40px rgba(220, 38, 38, 0.3);
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

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--text-main) 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 400;
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            justify-items: center;
        }

        /* Menu Card */
        .menu-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
            text-decoration: none;
            color: var(--text-main);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .menu-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: var(--primary);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1), 0 0 40px rgba(var(--primary), 0.15);
            color: var(--text-main);
        }

        .menu-card:hover::before {
            opacity: 1;
        }

        .menu-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .menu-card:hover .card-arrow {
            transform: translateX(5px);
            opacity: 1;
            color: var(--primary);
        }

        /* Card Icon */
        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.4s ease;
            position: relative;
            z-index: 1;
            color: white;
        }

        .card-icon.monitoring {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }

        .card-icon.admin {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
        }

        .card-icon.login {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        }

        .card-icon.register {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
        }

        /* Card Content */
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            color: var(--text-main);
        }

        .card-desc {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .card-arrow {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        /* User Badge */
        .user-badge {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            border: 1px solid var(--glass-border);
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .user-role {
            font-size: 0.7rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .logout-btn {
            background: rgba(239, 68, 68, 0.1);
            border: none;
            color: #ef4444;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: white;
        }

        /* Footer */
        .page-footer {
            text-align: center;
            padding: 1.5rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
            position: relative;
            z-index: 1;
        }

        /* Live Indicator */
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.75rem;
            }

            .menu-grid {
                grid-template-columns: 1fr;
            }

            .user-badge {
                top: 1rem;
                right: 1rem;
                padding: 0.4rem 0.75rem;
            }

            .user-info {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <!-- Floating Particles -->
    <div class="particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 1s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 3s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 2.5s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 4.5s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 1.5s;"></div>
    </div>

    @auth
        <!-- User Badge -->
        <div class="user-badge">
            <div class="user-avatar">
                <i class="bi bi-person-fill text-white"></i>
            </div>
            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'User' }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    @endauth

    <!-- Main Content -->
    <div class="main-wrapper">
        <div class="container">
            <!-- Header -->
            <div class="page-header">
                <div class="brand-logo">
                    <i class="bi bi-tree-fill"></i>
                </div>
                <div class="live-badge">
                    <span class="live-dot"></span>
                    System Online
                </div>
                <h1 class="page-title">Swaratani IoT</h1>
                <p class="page-subtitle">Pilih menu untuk memulai</p>
            </div>

            <!-- Menu Grid -->
            <div class="menu-grid">
                @auth
                    <!-- Monitoring -->
                    <a href="{{ route('monitoring.index') }}" class="menu-card">
                        <div class="card-icon monitoring">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h3 class="card-title">Monitoring Dashboard</h3>
                        <p class="card-desc">Pantau kondisi device dan sensor secara real-time dengan visualisasi data yang
                            lengkap.</p>
                        <div class="card-arrow">
                            <span>Buka Dashboard</span>
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>

                    @if(Auth::user()->role === 'admin')
                        <!-- Admin Panel -->
                        <a href="{{ route('admin.devices.index') }}" class="menu-card">
                            <div class="card-icon admin">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <h3 class="card-title">Admin Panel</h3>
                            <p class="card-desc">Kelola device, konfigurasi sistem, dan lihat statistik penggunaan.</p>
                            <div class="card-arrow">
                                <span>Kelola Sistem</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </a>
                    @endif
                @else
                    <!-- Login -->
                    <a href="{{ route('login') }}" class="menu-card">
                        <div class="card-icon login">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                        <h3 class="card-title">Login</h3>
                        <p class="card-desc">Masuk ke sistem untuk mengakses dashboard monitoring dan kontrol device.</p>
                        <div class="card-arrow">
                            <span>Masuk Sekarang</span>
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>

                    <!-- Register -->
                    <a href="{{ route('register') }}" class="menu-card">
                        <div class="card-icon register">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <h3 class="card-title">Daftar Akun</h3>
                        <p class="card-desc">Buat akun baru untuk mulai menggunakan sistem monitoring IoT.</p>
                        <div class="card-arrow">
                            <span>Buat Akun</span>
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer">
        <p>© 2025 Swaratani IoT &bull; Tim Engineering Pertanian</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => { });
        }
    </script>
</body>

</html>