<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Aktivitas - Swaratani</title>
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
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .log-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .log-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(14, 95, 138, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
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
            font-size: 0.8rem;
            color: var(--text-secondary);
            background: rgba(0,0,0,0.03);
            padding: 8px 12px;
            border-radius: 8px;
            margin-top: 8px;
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
            <a class="navbar-brand d-flex align-items-center" href="{{ route('monitoring.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                <span class="fw-bold" style="color: var(--navbar_text, #333);">Kembali</span>
            </a>
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
                        <div class="log-card d-flex gap-3">
                            <div class="log-icon">
                                @if($log->action === 'irrigation_control')
                                    <i class="bi bi-droplet-half"></i>
                                @elseif($log->action === 'pump_control')
                                    <i class="bi bi-fan"></i>
                                @else
                                    <i class="bi bi-sliders"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="log-desc">{{ $log->description }}</div>
                                    <div class="log-time">{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="log-time mb-2">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                
                                @if(!empty($log->details))
                                <div class="log-details">
                                    @foreach($log->details as $key => $value)
                                        @if($key !== 'device_id')
                                            <span class="me-3"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ is_bool($value) ? ($value ? 'Ya' : 'Tidak') : $value }}</span>
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
