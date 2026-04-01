<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring - Swaratani</title>
    @include('partials.pwa-head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">


    @include('partials.theme')

    <style>
        /* Page Title */
        .page-title {
            color: var(--text-main);
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        /* Device Cards */
        .device-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.4s ease;
        }

        .device-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .device-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            margin-right: 1rem;
        }

        .device-name {
            color: var(--text-main);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .device-type-badge {
            background: rgba(14, 95, 138, 0.1);
            color: var(--primary);
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
        }

        .device-location {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .divider {
            height: 1px;
            background: var(--glass-border);
            margin: 1rem 0;
        }

        .sensor-count {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }


        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            border: 2px dashed rgba(14, 95, 138, 0.3);
            border-radius: 24px;
            margin-top: 2rem;
        }

        .empty-state > i {
            font-size: 3.5rem;
            color: var(--primary);
            opacity: 0.5;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .empty-state h5 {
            color: var(--text-main);
            margin-top: 1.5rem;
            font-weight: 700;
        }

        .empty-state p {
            color: var(--text-secondary);
            max-width: 400px;
            margin: 0.5rem auto 1.5rem auto;
        }

        /* Device Type Badge */
        .device-type {
            display: inline-block;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(168, 85, 247, 0.2) 100%);
            color: #a5b4fc;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            border: 1px solid rgba(99, 102, 241, 0.3);
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }

        /* View Button */
        .btn-view {
            background: #0d6efd;
            color: #fff;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid #0d6efd;
        }

        .btn-view:hover {
            background: #0b5ed7;
            color: #fff;
            border-color: #0b5ed7;
        }

        /* Delete Button */
        .btn-delete {
            background: rgba(100, 116, 139, 0.1);
            border: 1px solid rgba(100, 116, 139, 0.3);
            color: #64748b;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
            color: #ef4444;
        }

        /* Gradient Button */
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
            color: #fff;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            border: none;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-gradient:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        /* Alert Custom */
        .alert-success-custom {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            color: #6ee7b7;
            padding: 1rem 1.25rem;
        }

        /* Enhanced Device Card Animation */
        .device-card {
            position: relative;
            overflow: hidden;
        }

        .device-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary) 0%, #8b5cf6 50%, #ec4899 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .device-card:hover::before {
            opacity: 1;
        }

        /* Favorite Button */
        .btn-favorite {
            background: rgba(100, 116, 139, 0.1);
            border: 1px solid rgba(100, 116, 139, 0.3);
            color: #94a3b8;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-favorite:hover {
            color: #f59e0b;
            border-color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
        }

        .btn-favorite.active {
            color: #f59e0b;
            border-color: #f59e0b;
            background: rgba(245, 158, 11, 0.15);
        }

        .btn-favorite.active:hover {
            background: rgba(245, 158, 11, 0.25);
        }

        @keyframes starPop {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.4);
            }

            100% {
                transform: scale(1);
            }
        }

        .btn-favorite.pop {
            animation: starPop 0.3s ease;
        }

        /* Connection Status */
        .connection-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
        }

        .connection-status.online {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }

        .connection-status.offline {
            background: rgba(100, 116, 139, 0.12);
            color: #94a3b8;
        }

        .connection-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .connection-dot.online {
            background: #10b981;
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);
        }

        .connection-dot.offline {
            background: #94a3b8;
        }

        .last-seen-text {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.2rem !important;
            }
            .header-actions .btn-action {
                width: 42px;
                height: 42px;
                padding: 0 !important;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50% !important;
            }
            .header-actions .btn-action i {
                margin: 0 !important;
                font-size: 1.2rem;
            }
            .header-actions .action-text {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center"
                href="{{ session('is_pwa') ? route('monitoring.index') : route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Swaratani" height="40" class="me-2">
                <span class="fw-bold" style="color: var(--navbar_text, #333);">Swaratani IoT</span>
            </a>
            <div class="navbar-nav ms-auto flex-row align-items-center gap-4 gap-sm-3">
                @if(!session('is_pwa'))
                    <a class="nav-link px-0" href="{{ route('home') }}" title="Beranda" style="color: var(--navbar-text);">
                        <i class="bi bi-house fs-5 me-sm-1"></i><span class="d-none d-sm-inline"> Beranda</span>
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link px-0 text-decoration-none" style="color: var(--navbar-text);" title="Logout">
                        <i class="bi bi-box-arrow-right fs-5 me-sm-1"></i><span class="d-none d-sm-inline"> Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4 gap-2 header-container">
            <h2 class="page-title mb-0 text-truncate">
                <i class="bi bi-graph-up-arrow me-1"></i><span class="d-none d-sm-inline">Monitoring </span>Devices
            </h2>
            <div class="d-flex gap-2 header-actions flex-shrink-0">
                @if(!session('is_pwa'))
                    <button onclick="openSwarataniApp(event)" class="btn btn-outline-primary btn-action" style="border-radius: 50px; font-weight: 600;" title="Buka Aplikasi">
                        <i class="bi bi-phone me-sm-1"></i><span class="action-text"> Buka Aplikasi</span>
                    </button>
                @endif
                <a href="{{ route('riwayat.index') }}" class="btn btn-outline-primary btn-action" style="border-radius: 50px;" title="Riwayat">
                    <i class="bi bi-clock-history me-sm-1"></i><span class="action-text"> Riwayat</span>
                </a>
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-danger btn-action" style="border-radius: 50px;" title="Lapor Masalah">
                    <i class="bi bi-bug me-sm-1"></i><span class="action-text"> Lapor Masalah</span>
                </a>
                <a href="{{ route('monitoring.create') }}" class="btn btn-gradient btn-action" style="border-radius: 50px;" title="Tambah Device">
                    <i class="bi bi-plus-lg me-sm-1"></i><span class="action-text"> Tambah</span>
                </a>
            </div>
        </div>



        @if(session('success'))
            <div class="alert alert-success-custom mb-4">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($userDevices->count() > 0)
            <div class="row g-4">
                @foreach($userDevices as $userDevice)
                    <div class="col-md-6 col-lg-4">
                        <div class="device-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="device-icon">
                                        <i
                                            class="bi {{ $userDevice->device->type === 'aws' ? 'bi-cloud-sun-fill' : 'bi-flower1' }} text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="device-name mb-1">{{ $userDevice->custom_name }}</h5>
                                        <span class="device-type">
                                            {{ strtoupper($userDevice->device->type ?? 'N/A') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex gap-1">
                                    <button class="btn-favorite {{ $userDevice->is_favorite ? 'active' : '' }}"
                                        data-id="{{ $userDevice->id }}"
                                        title="{{ $userDevice->is_favorite ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
                                        <i class="bi {{ $userDevice->is_favorite ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    </button>
                                    <form action="{{ route('monitoring.destroy', $userDevice->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus device ini dari monitoring?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="sensor-count mb-0">
                                    <i class="bi bi-thermometer-half me-1"></i>
                                    {{ $userDevice->device->sensors->count() }} Sensor Aktif
                                </p>
                                <div class="text-end">
                                    @if($userDevice->device->isOnline())
                                        <span class="connection-status online">
                                            <span class="connection-dot online"></span> Online
                                        </span>
                                    @else
                                        <span class="connection-status offline">
                                            <span class="connection-dot offline"></span> Offline
                                        </span>
                                    @endif
                                    <div class="last-seen-text">{{ $userDevice->device->lastSeenText() }}</div>
                                </div>
                            </div>

                            <a href="{{ route('monitoring.show', $userDevice->id) }}"
                                class="btn-view w-100 d-block text-center">
                                <i class="bi bi-graph-up me-1"></i> Lihat Data
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>Belum Ada Device</h5>
                <p>Tambahkan device dengan memasukkan token untuk mulai monitoring.</p>
                <a href="{{ route('monitoring.create') }}" class="btn btn-gradient mt-3">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Device Pertama
                </a>
            </div>
        @endif
    </div>

    @include('partials.pwa-scripts')
    @include('partials.chatbot')

    <script>
        function openSwarataniApp(e) {
            e.preventDefault();
            
            // Sesuai dengan config di swaratani_mobile/android/app/build.gradle
            var packageName = "id.swaratani.swaratani_mobile";
            var playStoreLink = "https://play.google.com/store/apps/details?id=" + packageName;
            
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
            
            if (/android/i.test(userAgent)) {
                // Di Android, gunakan Intent untuk buka aplikasi. Jika belum install, browser akan fallback ke PlayStore (browser_fallback_url)
                var intentUrl = "intent://#Intent;scheme=swaratani;package=" + packageName + ";S.browser_fallback_url=" + encodeURIComponent(playStoreLink) + ";end";
                window.location.href = intentUrl;
            } else {
                // Untuk iOS atau desktop sementara buka PlayStore
                window.location.href = playStoreLink;
            }
        }

        document.querySelectorAll('.btn-favorite').forEach(btn => {
            btn.addEventListener('click', async function () {
                const id = this.dataset.id;
                const icon = this.querySelector('i');
                const btn = this;

                try {
                    const res = await fetch(`/monitoring/device/${id}/favorite`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    });

                    const data = await res.json();
                    if (data.success) {
                        btn.classList.toggle('active', data.is_favorite);
                        icon.className = data.is_favorite ? 'bi bi-star-fill' : 'bi bi-star';
                        btn.title = data.is_favorite ? 'Hapus dari favorit' : 'Tambah ke favorit';
                        btn.classList.add('pop');
                        setTimeout(() => btn.classList.remove('pop'), 300);
                    }
                } catch (err) {
                    console.error('Toggle favorite failed', err);
                }
            });
        });
    </script>
</body>

</html>