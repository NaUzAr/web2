<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Aktivitas - {{ env('APP_NAME', 'Swaratani') }}</title>
    @include('partials.pwa-head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @include('partials.theme')

    <style>
        .page-title {
            color: var(--text-main);
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .log-card {
            background: #ffffff;
            border: 1px solid rgba(14, 165, 233, 0.15);
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .log-card:hover {
            border-color: rgba(14, 165, 233, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(14, 165, 233, 0.1);
        }

        .log-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #e0f2fe, #bae6fd);
            color: #0ea5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: inset 0 2px 4px rgba(255,255,255,0.5), 0 4px 8px rgba(0,0,0,0.05);
        }

        .log-icon.icon-on {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #16a34a;
        }

        .log-icon.icon-off {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }

        .btn-back {
            color: #0ea5e9;
            border: 1px solid rgba(14, 165, 233, 0.2);
            border-radius: 50px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            text-decoration: none;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        .btn-back:hover {
            background: #f0f9ff;
            color: #0284c7;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15);
        }

        .log-time {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .log-desc {
            color: var(--text-main);
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .log-details {
            margin-top: 10px;
        }
        
        .log-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            border: 1px solid transparent;
        }

        .log-badge.badge-primary {
            background: #f0f9ff;
            color: #0284c7;
            border-color: #bae6fd;
        }

        .log-badge.badge-success {
            background: #f0fdf4;
            color: #16a34a;
            border-color: #bbf7d0;
        }

        .log-badge.badge-danger {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .log-badge.badge-secondary {
            background: #f8fafc;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            background: rgba(255, 255, 255, 0.4);
            border: 2px dashed rgba(14, 95, 138, 0.3);
            border-radius: 24px;
            margin-top: 2rem;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: var(--primary);
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset(env('APP_LOGO', 'images/logo.png')) }}" alt="{{ env('APP_NAME', 'Swaratani') }}" height="40" class="me-2">
                <span class="fw-bold" style="color: var(--navbar_text, #333);">Swaratani IoT</span>
            </a>
            <div class="navbar-nav ms-auto flex-row align-items-center gap-4 gap-sm-3">
                <a class="nav-link px-0 text-decoration-none" href="{{ route('monitoring.index') }}" title="Kembali ke List Device" style="color: var(--navbar-text);">
                    <i class="bi bi-arrow-left fs-5 me-2 me-sm-1" style="-webkit-text-stroke: 1px currentColor;"></i>
                    <i class="bi bi-grid-1x2 fs-5 me-sm-1"></i><span class="d-none d-sm-inline"> List Device</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2 class="page-title mb-4">
            <i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas
        </h2>

        @if($logs->count() > 0)
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    @foreach($logs as $log)
                        @php
                            $isTurnOn = false;
                            $isTurnOff = false;
                            $statusText = '';
                            
                            if (isset($log->details['new_value'])) {
                                if ($log->details['new_value'] == 1 || $log->details['new_value'] === '1' || $log->details['new_value'] === true || strtolower($log->details['new_value']) === 'on') {
                                    $isTurnOn = true;
                                    $statusText = 'ON (Aktif)';
                                } else {
                                    $isTurnOff = true;
                                    $statusText = 'OFF (Mati)';
                                }
                            }
                        @endphp
                        <div class="log-card d-flex gap-3">
                            <div class="log-icon {{ $isTurnOn ? 'icon-on' : ($isTurnOff ? 'icon-off' : '') }}">
                                @if($log->action === 'irrigation_control')
                                    <i class="bi bi-droplet-half"></i>
                                @elseif($log->action === 'pump_control')
                                    <i class="bi bi-fan"></i>
                                @else
                                    <i class="bi bi-sliders"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div class="log-desc">{{ $log->description }}</div>
                                    <div class="log-time"><i class="bi bi-clock me-1"></i>{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="log-time mb-2">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                
                                @if(!empty($log->details))
                                <div class="log-details d-flex flex-wrap gap-2">
                                    @foreach($log->details as $key => $value)
                                        @if($key !== 'device_id')
                                            @if($key === 'new_value')
                                                <span class="log-badge {{ $isTurnOn ? 'badge-success' : ($isTurnOff ? 'badge-danger' : 'badge-secondary') }}">
                                                    <i class="bi {{ $isTurnOn ? 'bi-lightning-charge-fill' : 'bi-power' }} me-1"></i> 
                                                    Status: {{ $statusText ?: $value }}
                                                </span>
                                            @elseif($key === 'output_name' || $key === 'target')
                                                <span class="log-badge badge-primary">
                                                    <i class="bi bi-tag-fill me-1"></i> {{ strtoupper($value) }}
                                                </span>
                                            @else
                                                <span class="log-badge badge-secondary">
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ is_bool($value) ? ($value ? 'Ya' : 'Tidak') : $value }}
                                                </span>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $logs->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5 class="mt-3">Belum Ada Riwayat</h5>
                <p class="text-secondary">Aktivitas kontrol device Anda akan muncul di sini.</p>
            </div>
        @endif
    </div>

    @include('partials.pwa-scripts')
</body>
</html>
