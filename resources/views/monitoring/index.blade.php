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
        }

        .empty-state i {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.3);
        }

        .empty-state h5 {
            color: #fff;
            margin-top: 1rem;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.6);
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
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand" href="{{ session('is_pwa') ? route('monitoring.index') : route('home') }}">
                <i class="bi bi-tree-fill me-2"></i>Swaratani
            </a>
            <div class="navbar-nav ms-auto">
                @if(!session('is_pwa'))
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="bi bi-house me-1"></i> Beranda
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link" style="color: rgba(255,255,255,0.8);">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">
                <i class="bi bi-graph-up-arrow me-2"></i>Monitoring Devices
            </h2>
            <a href="{{ route('monitoring.create') }}" class="btn btn-gradient">
                <i class="bi bi-plus-lg me-1"></i> Tambah Device
            </a>
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
                                <form action="{{ route('monitoring.destroy', $userDevice->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus device ini dari monitoring?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <p class="sensor-count mb-3">
                                <i class="bi bi-thermometer-half me-1"></i>
                                {{ $userDevice->device->sensors->count() }} Sensor Aktif
                            </p>

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
</body>

</html>