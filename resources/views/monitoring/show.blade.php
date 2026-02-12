<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ($isAdminView ?? false) ? $device->name : $userDevice->custom_name }} - Monitoring</title>
    @include('partials.pwa-head')
    @include('partials.theme')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Page Specific Overrides */


        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
        }

        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 20% 80%, var(--glow-1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, var(--glow-2) 0%, transparent 50%);
        }

        .navbar-glass {
            background: var(--navbar-bg) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary) !important;
        }

        .nav-link {
            color: var(--text-secondary) !important;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .page-header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
        }

        .device-title {
            color: var(--text-main);
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }

        .device-type-badge {
            background: var(--primary-gradient);
            color: #fff;
            padding: 0.35rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .sensor-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }

        .sensor-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .sensor-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-gradient);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.3rem;
            color: white;
        }

        .sensor-label {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .sensor-value {
            color: var(--text-main);
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .sensor-unit {
            color: var(--primary);
            font-size: 1rem;
            font-weight: 600;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .card-title {
            color: var(--text-main);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .last-update {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        /* Glassmorphism Classes (From Schedule) */
        .modal-content-glass {
            background: var(--glass-bg, rgba(30, 41, 59, 0.85));
            /* Fallback to dark glass if variable missing */
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.1));
            color: var(--text-main, #fff);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .form-control-dark,
        .form-select-dark {
            background-color: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
        }

        .form-control-dark:focus,
        .form-select-dark:focus {
            background-color: var(--glass-bg);
            border-color: var(--primary);
            color: var(--text-main);
            box-shadow: 0 0 0 0.25rem rgba(var(--primary), 0.25);
        }

        .btn-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            text-decoration: none;
        }

        .btn-glass:hover {
            background: var(--glass-bg);
            border-color: var(--primary);
            color: var(--primary);
        }

        .no-data {
            color: var(--text-secondary);
            text-align: center;
            padding: 3rem;
        }

        .btn-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-glass:hover {
            background: var(--glass-bg);
            border-color: var(--primary);
            color: var(--primary);
        }

        .live-dot {
            width: 10px;
            height: 10px;
            background: var(--primary);
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        /* Table Styles */
        .table-glass {
            color: var(--text-main);
        }

        .table-glass thead th {
            background: rgba(var(--primary), 0.1);
            color: var(--primary);
            font-weight: 600;
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem;
        }

        .table-glass tbody td {
            border-bottom: 1px solid var(--glass-border);
            padding: 0.75rem 1rem;
            color: var(--text-main);
        }

        .table-glass tbody tr:hover {
            background: rgba(var(--primary), 0.05);
        }

        /* Pagination */
        .pagination-glass .page-link {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
        }

        .pagination-glass .page-link:hover {
            background: var(--primary-light);
            color: #fff;
        }

        .pagination-glass .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: transparent;
            color: #fff;
        }

        .pagination-glass .page-item.disabled .page-link {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-secondary);
        }

        /* Tabs */
        .nav-tabs-glass {
            border-bottom: 1px solid var(--glass-border);
        }

        .nav-tabs-glass .nav-link {
            color: var(--text-secondary);
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .nav-tabs-glass .nav-link:hover {
            color: var(--primary);
            border: none;
        }

        .nav-tabs-glass .nav-link.active {
            background: transparent;
            color: var(--primary);
            border-bottom: 3px solid var(--primary);
        }

        /* Output Control Styles */
        .output-card {
            background: rgba(250, 204, 21, 0.05);
            border: 1px solid rgba(250, 204, 21, 0.2);
            border-radius: 16px;
            padding: 1.25rem;
            text-align: center;
            transition: all 0.3s ease;
            min-height: 180px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .output-card:hover {
            border-color: #fde047;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .output-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.2rem;
            color: white;
        }

        .output-label {
            color: var(--text-main);
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 60px;
            height: 32px;
            margin: 0 auto;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--text-secondary);
            transition: 0.3s;
            border-radius: 32px;
            opacity: 0.3;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        .toggle-switch input:checked+.toggle-slider {
            background: var(--primary-gradient);
            opacity: 1;
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(28px);
        }

        .output-status {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        .output-status.on {
            color: var(--primary);
        }

        .output-status.off {
            color: var(--text-secondary);
        }

        /* Range Slider */
        .range-slider {
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.1);
            outline: none;
            -webkit-appearance: none;
        }

        .range-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary-gradient);
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .range-value {
            color: #f59e0b;
            font-size: 1.25rem;
            font-weight: 700;
        }

        /* Special Pump Card Styles */
        .output-card-special {
            background: rgba(14, 165, 233, 0.1);
            border: 2px solid rgba(14, 165, 233, 0.3);
            border-radius: 16px;
            padding: 1.25rem;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 0.4rem;
        }

        /* Mobile responsive adjustments */
        @media (max-width: 576px) {
            .output-card-special {
                padding: 1rem;
            }

            .output-card-special .badge {
                font-size: 0.7rem;
                padding: 0.35rem 0.6rem !important;
            }

            .output-card-special .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
                min-width: 45px !important;
            }

            .output-card {
                padding: 1rem;
            }

            .output-card .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
                min-width: 45px !important;
            }
        }

        .output-card-special:hover {
            border-color: #0ea5e9;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2);
        }

        .output-icon-special {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.2rem;
            color: white;
        }

        .btn-pump-special {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-pump-special:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: white;
            transform: scale(1.05);
        }

        /* Modal styles for pump */
        .modal-content-pump {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: var(--text-main);
        }

        .form-control-pump,
        .form-select-pump {
            background-color: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
        }

        .form-control-pump:focus,
        .form-select-pump:focus {
            background-color: var(--glass-bg);
            border-color: #0ea5e9;
            color: var(--text-main);
            box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.25);
        }

        .btn-pump-send {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            border: none;
            color: white;
            font-weight: 600;
        }

        .btn-pump-send:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
        }

        .btn-pump-off {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            color: white;
            font-weight: 600;
        }

        .btn-pump-off:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
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
            <div class="navbar-nav ms-auto">
                @if($isAdminView ?? false)
                    <a class="nav-link" href="{{ route('admin.devices.index') }}">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Device Manager
                    </a>
                @else
                    <a class="nav-link" href="{{ route('monitoring.index') }}">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Monitoring
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Header -->
        <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="device-title">
                    <i class="bi {{ $device->type === 'aws' ? 'bi-cloud-sun' : 'bi-flower1' }} me-2"></i>
                    @if($isAdminView ?? false)
                        {{ $device->name }}
                    @else
                        {{ $userDevice->custom_name }}
                    @endif
                </h1>
                <p class="mb-0 mt-1" style="color: var(--text-secondary);">
                    <span class="live-dot me-2"></span>
                    @if($latestData)
                        Terakhir update: {{ \Carbon\Carbon::parse($latestData->recorded_at)->diffForHumans() }}
                    @else
                        Menunggu data...
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap justify-content-md-end">
                @if($isAdminView ?? false)
                    <span class="device-type-badge" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="bi bi-shield-check me-1"></i> Admin View
                    </span>
                @endif
                <span class="device-type-badge">
                    {{ strtoupper($device->type ?? 'DEVICE') }}
                </span>
                @if(!($isAdminView ?? false))
                    @if($scheduleConfig ?? false)
                        <a href="{{ route('schedule.index', $userDevice->id) }}" class="btn-glass">
                            <i class="bi bi-calendar-check me-1"></i> Jadwal
                        </a>
                    @endif
                    @if($hasAutomation ?? false)
                        <a href="{{ route('automasi.index', $userDevice->id) }}" class="btn-glass">
                            <i class="bi bi-cpu me-1"></i> Otomasi
                        </a>
                    @endif
                    <button type="button" class="btn-glass" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="bi bi-download me-1"></i> Download CSV
                    </button>
                @endif
            </div>
        </div>

        @if($latestData)
            <!-- Sensor Cards -->
            <div class="row g-4">
                @foreach($sensors as $sensor)
                    @php
                        $value = $latestData->{$sensor->sensor_name} ?? null;
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="sensor-card">
                            <div class="sensor-icon">
                                <i class="bi bi-thermometer-half text-white"></i>
                            </div>
                            <div class="sensor-label">{{ $sensor->sensor_label }}</div>
                            <div class="sensor-value" id="sensor-val-{{ $sensor->id }}">
                                @if($value !== null)
                                    {{ number_format($value, 1) }}
                                @else
                                    --
                                @endif
                            </div>
                            <div class="sensor-unit">{{ $sensor->unit }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($outputs->count() > 0)
            <!-- Output Control Panel -->
            <div class="glass-card"
                style="background: rgba(250, 204, 21, 0.05); border-color: rgba(250, 204, 21, 0.2); margin-top: 1.5rem;">
                <h5 class="card-title" style="color: #fde047;">
                    <i class="bi bi-sliders me-2"></i>Kontrol Output
                </h5>
                <div class="row g-4 mt-2">
                    @php
                        // Sort outputs by priority then name (excluding multi_zone)
                        $sortedOutputs = $outputs->where('output_type', '!=', 'multi_zone')->sortBy(function ($output) {
                            $name = strtolower($output->output_name);

                            // Priority Mapping
                            if (str_contains($name, 'pompa') || str_contains($name, 'pump') && !str_contains($name, 'ab') && !str_contains($name, 'ph'))
                                return 10;
                            if (str_contains($name, 'pump_ab') || str_contains($name, 'dosing'))
                                return 20;
                            if (str_contains($name, 'ph_up') || str_contains($name, 'ph1'))
                                return 30; // pH Up (pmpPH)
                            if (str_contains($name, 'ph_down') || str_contains($name, 'ph2'))
                                return 31; // pH Down (pmpPH2)

                            // Environment controls
                            if (str_contains($name, 'mist'))
                                return 50;
                            if (str_contains($name, 'fan'))
                                return 51;
                            if (str_contains($name, 'air'))
                                return 52;
                            if (str_contains($name, 'lamp'))
                                return 53;
                            if (str_contains($name, 'mix'))
                                return 54;

                            return 99; // Default priority
                        })->values();
                    @endphp

                    {{-- Dynamic Irrigation Pump Cards (from database) --}}
                    @php
                        $irrigationPumps = $outputs->where('output_type', 'multi_zone');
                    @endphp

                    @foreach($irrigationPumps as $pump)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="output-card-special" id="output-card-irrigation-{{ $pump->id }}">
                                <div class="output-icon-special mb-3">
                                    <i class="bi bi-droplet-fill text-white"></i>
                                </div>
                                <div class="output-label">{{ $pump->output_label }}</div>

                                @if($pump->max_sectors > 1)
                                    <div class="mb-3 text-center">
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-2">
                                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> {{ $pump->max_sectors }} Zona Tersedia
                                        </span>
                                    </div>
                                @endif

                                <div class="d-flex gap-2 justify-content-center mt-3">
                                    <button type="button" class="btn btn-sm btn-outline-success"
                                        onclick="openIrrigationModal({{ $pump->id }}, {{ $pump->max_sectors ?? 1 }})"
                                        id="btn-on-{{ $pump->id }}" style="min-width: 50px;">
                                        <i class="bi bi-power"></i> ON
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="sendIrrigationPumpOff({{ $pump->id }})" id="btn-off-{{ $pump->id }}"
                                        style="min-width: 50px;">
                                        <i class="bi bi-x-lg"></i> OFF
                                    </button>
                                </div>
                                <div class="output-status off mt-2" id="pump-status-{{ $pump->id }}">
                                    OFF
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach($sortedOutputs as $output)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="output-card" id="output-card-{{ $output->id }}">
                                <div class="output-icon">
                                    <i class="bi bi-toggle-on text-white"></i>
                                </div>
                                <div class="output-label">{{ $output->output_label }}</div>

                                @if($output->output_type === 'boolean')
                                    {{-- ON/OFF Buttons for Boolean --}}
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button"
                                            class="btn btn-sm {{ $output->current_value ? 'btn-success' : 'btn-outline-success' }}"
                                            onclick="setOutput({{ $output->id }}, true)" id="btn-on-{{ $output->id }}"
                                            style="min-width: 50px;">
                                            <i class="bi bi-power"></i> ON
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm {{ !$output->current_value ? 'btn-danger' : 'btn-outline-danger' }}"
                                            onclick="setOutput({{ $output->id }}, false)" id="btn-off-{{ $output->id }}"
                                            style="min-width: 50px;">
                                            <i class="bi bi-x-lg"></i> OFF
                                        </button>
                                    </div>
                                    <div class="output-status {{ $output->current_value ? 'on' : 'off' }}"
                                        id="output-status-{{ $output->id }}">
                                        {{ $output->current_value ? 'ON' : 'OFF' }}
                                    </div>
                                @else
                                    <!-- Range Slider for Number/Percentage -->
                                    <div class="range-value" id="output-value-{{ $output->id }}">
                                        {{ (int) $output->current_value }}{{ $output->unit }}
                                    </div>
                                    <input type="range" class="range-slider mt-2" id="output-{{ $output->id }}"
                                        data-output-id="{{ $output->id }}" data-output-type="{{ $output->output_type }}" min="0"
                                        max="{{ $output->output_type === 'percentage' ? 100 : 180 }}"
                                        value="{{ (int) $output->current_value }}"
                                        oninput="updateRangeValue({{ $output->id }}, this.value, '{{ $output->unit }}')"
                                        onchange="toggleOutput({{ $output->id }}, this.value)">
                                    <div class="output-status on mt-1">
                                        {{ $output->output_type === 'percentage' ? '0-100%' : '0-180°' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($latestData)
            <!-- Tabs -->
            @php
                $isTableActive = request()->has('page');
            @endphp
            <div id="data-section"></div>
            <ul class="nav nav-tabs nav-tabs-glass mt-4" id="dataTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link {{ !$isTableActive ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#chartTab">
                        <i class="bi bi-graph-up me-1"></i> Grafik
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $isTableActive ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#tableTab">
                        <i class="bi bi-table me-1"></i> Tabel Data ({{ $logData->total() }} records)
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Chart Tab -->
                <div class="tab-pane fade {{ !$isTableActive ? 'show active' : '' }}" id="chartTab">
                    <div class="glass-card mt-0" style="border-radius: 0 0 20px 20px;">
                        <!-- Sensor Dropdown -->
                        <div class="d-flex align-items-center mb-3">
                            <label class="me-2" style="color: var(--text-main);"><i
                                    class="bi bi-bar-chart-line me-1"></i>Pilih
                                Sensor:</label>
                            <select id="chartSensorSelect" class="form-select form-select-sm"
                                style="width: auto; background: #ffffff; color: var(--text-main); border: 1px solid var(--glass-border);">
                                @foreach($sensors as $index => $sensor)
                                    <option value="{{ $index }}" style="color: #333; background-color: #ffffff;">
                                        {{ $sensor->sensor_label }}
                                        ({{ $sensor->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="position: relative; height: 50vh; min-height: 300px; max-height: 500px; width: 100%;">
                            <canvas id="sensorChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Table Tab -->
                <div class="tab-pane fade {{ $isTableActive ? 'show active' : '' }}" id="tableTab">
                    <div class="glass-card mt-0" style="border-radius: 0 0 20px 20px;">
                        <!-- Sensor Dropdown for Table -->
                        <div class="d-flex align-items-center mb-3">
                            <label class="me-2" style="color: var(--text-main);"><i class="bi bi-filter me-1"></i>Filter
                                Sensor:</label>
                            <select id="tableSensorSelect" class="form-select form-select-sm"
                                style="width: auto; background: #ffffff; color: var(--text-main); border: 1px solid var(--glass-border);">
                                <option value="all" style="color: #333; background-color: #ffffff;">Semua Sensor</option>
                                @foreach($sensors as $index => $sensor)
                                    <option value="{{ $index }}" style="color: #333; background-color: #ffffff;">
                                        {{ $sensor->sensor_label }}
                                        ({{ $sensor->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-glass mb-0" id="sensorDataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Waktu</th>
                                        @foreach($sensors as $sensorIndex => $sensor)
                                            <th class="sensor-col" data-sensor-index="{{ $sensorIndex }}">
                                                {{ $sensor->sensor_label }} ({{ $sensor->unit }})
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logData as $index => $row)
                                        <tr>
                                            <td>{{ $logData->firstItem() + $index }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row->recorded_at)->format('d/m/Y H:i:s') }}</td>
                                            @foreach($sensors as $sensorIndex => $sensor)
                                                <td class="sensor-col" data-sensor-index="{{ $sensorIndex }}">
                                                    @if(isset($row->{$sensor->sensor_name}))
                                                        {{ number_format($row->{$sensor->sensor_name}, 2) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($logData->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                <nav>
                                    <ul class="pagination pagination-glass mb-0">
                                        {{-- Previous --}}
                                        @if($logData->onFirstPage())
                                            <li class="page-item disabled"><span class="page-link">«</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $logData->previousPageUrl() }}#data-section">«</a></li>
                                        @endif

                                        {{-- Page Numbers --}}
                                        @foreach($logData->getUrlRange(max(1, $logData->currentPage() - 2), min($logData->lastPage(), $logData->currentPage() + 2)) as $page => $url)
                                            @if($page == $logData->currentPage())
                                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                            @else
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $url }}#data-section">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next --}}
                                        @if($logData->hasMorePages())
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $logData->nextPageUrl() }}#data-section">»</a></li>
                                        @else
                                            <li class="page-item disabled"><span class="page-link">»</span></li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                            <p class="text-center mt-2 small" style="color: var(--text-secondary);">
                                Showing {{ $logData->firstItem() }} - {{ $logData->lastItem() }} of {{ $logData->total() }}
                                records
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <script>             const ctx = document.getElementById('sensorChart').getContext('2d');

                // Data dari PHP
                const chartData = @json($chartData);
                const sensors = @json($sensors);

                const labels = chartData.map(row => {
                    const date = new Date(row.recorded_at);
                    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                });

                const colors = [
                    { border: '#22c55e', bg: 'rgba(34, 197, 94, 0.3)' },
                    { border: '#0ea5e9', bg: 'rgba(14, 165, 233, 0.3)' },
                    { border: '#f59e0b', bg: 'rgba(245, 158, 11, 0.3)' },
                    { border: '#8b5cf6', bg: 'rgba(139, 92, 246, 0.3)' },
                    { border: '#ef4444', bg: 'rgba(239, 68, 68, 0.3)' },
                    { border: '#06b6d4', bg: 'rgba(6, 182, 212, 0.3)' },
                    { border: '#84cc16', bg: 'rgba(132, 204, 22, 0.3)' },
                    { border: '#ec4899', bg: 'rgba(236, 72, 153, 0.3)' },
                ];

                // Function to build filtered data for a single sensor
                function getFilteredData(sensorIndex) {
                    const sensor = sensors[sensorIndex];
                    const sensorName = sensor.sensor_name;

                    // Filter to only include rows with valid data for this sensor
                    const filteredRows = chartData.filter(row =>
                        row[sensorName] !== null && row[sensorName] !== undefined
                    );

                    const filteredLabels = filteredRows.map(row => {
                        const date = new Date(row.recorded_at);
                        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    });

                    const filteredData = filteredRows.map(row => row[sensorName]);

                    const colorIndex = sensorIndex % colors.length;
                    const dataset = {
                        label: sensor.sensor_label + (sensor.unit ? ` (${sensor.unit})` : ''),
                        data: filteredData,
                        borderColor: colors[colorIndex].border,
                        backgroundColor: colors[colorIndex].bg,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: colors[colorIndex].border,
                        pointHoverRadius: 6,
                    };

                    return { labels: filteredLabels, dataset };
                }

                // Initialize chart with first sensor (filtered)
                const initialData = getFilteredData(0);
                let sensorChart = new Chart(ctx, {
                    type: 'line',
                    data: { labels: initialData.labels, datasets: [initialData.dataset] },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: getComputedStyle(document.body).getPropertyValue('--text-secondary').trim() },
                                grid: { color: getComputedStyle(document.body).getPropertyValue('--glass-border').trim() }
                            },
                            y: {
                                ticks: { color: getComputedStyle(document.body).getPropertyValue('--text-secondary').trim() },
                                grid: { color: getComputedStyle(document.body).getPropertyValue('--glass-border').trim() }
                            }
                        }
                    }
                });

                // Dropdown change listener for chart
                document.getElementById('chartSensorSelect').addEventListener('change', function () {
                    const selectedIndex = parseInt(this.value);
                    const filteredData = getFilteredData(selectedIndex);
                    sensorChart.data.labels = filteredData.labels;
                    sensorChart.data.datasets = [filteredData.dataset];
                    sensorChart.update();
                });

                // Table column and row filter listener
                document.getElementById('tableSensorSelect')?.addEventListener('change', function () {
                    const selectedValue = this.value;
                    const sensorCols = document.querySelectorAll('.sensor-col');
                    const tableRows = document.querySelectorAll('#sensorDataTable tbody tr');

                    // Show/hide columns
                    sensorCols.forEach(col => {
                        if (selectedValue === 'all') {
                            col.style.display = '';
                        } else {
                            if (col.dataset.sensorIndex === selectedValue) {
                                col.style.display = '';
                            } else {
                                col.style.display = 'none';
                            }
                        }
                    });

                    // Show/hide rows based on data availability
                    tableRows.forEach(row => {
                        if (selectedValue === 'all') {
                            row.style.display = '';
                        } else {
                            // Find the cell for this sensor in this row
                            const sensorCell = row.querySelector(`.sensor-col[data-sensor-index="${selectedValue}"]`);
                            if (sensorCell) {
                                const cellValue = sensorCell.textContent.trim();
                                // Hide row if value is '-' (no data)
                                if (cellValue === '-') {
                                    row.style.display = 'none';
                                } else {
                                    row.style.display = '';
                                }
                            }
                        }
                    });
                });
            </script>
        @else
            <!-- No Data -->
            <div class="glass-card">
                <div class="no-data">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-white">Belum Ada Data</h5>
                    <p>Device ini belum mengirimkan data sensor.<br>Data akan muncul setelah device terhubung dan mengirim
                        data.</p>
                </div>
            </div>

            <div class="glass-card mt-4">
                <h5 class="card-title"><i class="bi bi-list-check me-2"></i>Sensor yang Dikonfigurasi</h5>
                <div class="row g-3 mt-2">
                    @foreach($sensors as $sensor)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3"
                                style="background: rgba(255,255,255,0.05); border-radius: 12px;">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <div>
                                    <div class="text-white fw-semibold">{{ $sensor->sensor_label }}</div>
                                    <small class="text-white-50">{{ $sensor->sensor_name }}
                                        {{ $sensor->unit ? '(' . $sensor->unit . ')' : '' }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif


    </div>


    @if(!($isAdminView ?? false))
        <!-- Export Modal -->
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content"
                    style="background: linear-gradient(135deg, #134e4a 0%, #166534 100%); border: 1px solid rgba(255,255,255,0.2);">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-white" id="exportModalLabel">
                            <i class="bi bi-download me-2"></i>Download Data CSV
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('monitoring.export', $userDevice->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="text-white-50 mb-4">Pilih rentang tanggal untuk data yang ingin di-download:</p>

                            <div class="mb-3">
                                <label class="form-label text-white">
                                    <i class="bi bi-calendar-event me-1"></i> Tanggal Mulai
                                </label>
                                <input type="date" name="start_date"
                                    class="form-control bg-dark text-white border-secondary"
                                    value="{{ date('Y-m-d', strtotime('-7 days')) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white">
                                    <i class="bi bi-calendar-check me-1"></i> Tanggal Akhir
                                </label>
                                <input type="date" name="end_date" class="form-control bg-dark text-white border-secondary"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-light" onclick="setDateRange(7)">7
                                    Hari</button>
                                <button type="button" class="btn btn-sm btn-outline-light" onclick="setDateRange(30)">30
                                    Hari</button>
                                <button type="button" class="btn btn-sm btn-outline-light" onclick="setDateRange(90)">3
                                    Bulan</button>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn" style="background: var(--primary-gradient); color: #fff;">
                                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Download CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Irrigation Pump Control Modal (Dynamic & Glass Style) -->
        <div class="modal fade" id="irrigationPumpModal" tabindex="-1" aria-labelledby="irrigationPumpModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-content-glass">
                    <div class="modal-header border-bottom border-secondary">
                        <h5 class="modal-title" id="irrigationPumpModalLabel">
                            <i class="bi bi-droplet-fill me-2" style="color: #0ea5e9;"></i>Kontrol Pompa Irigasi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-4 text-secondary">Pilih jenis input air dan zona irigasi:</p>

                        <!-- Hidden field for output ID -->
                        <input type="hidden" id="irrigationOutputId" value="">

                        <div class="mb-3">
                            <label class="form-label text-secondary">
                                <i class="bi bi-water me-1" style="color: #0ea5e9;"></i> Jenis Air
                            </label>
                            <select id="irrigationWaterType" class="form-select form-select-dark">
                                <option value="0">Air Baku</option>
                                <option value="1">Air Pupuk</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">
                                <i class="bi bi-geo-alt me-1" style="color: #0ea5e9;"></i> Zona / Blok
                            </label>
                            <select id="irrigationZone" class="form-select form-select-dark">
                                <!-- Dynamically populated by JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-link text-secondary text-decoration-none"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="sendIrrigationPumpOn()">
                            <i class="bi bi-play-fill me-1"></i> Nyalakan Pompa
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Setup CSRF token for AJAX requests
            const csrfToken = '{{ csrf_token() }}';
            const userDeviceId = {{ $userDevice->id }};

            // Set output ON/OFF (for buttons)
            function setOutput(outputId, isOn) {
                const url = `/monitoring/device/${userDeviceId}/output/${outputId}/toggle`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ value: isOn })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button styles
                            const btnOn = document.getElementById(`btn-on-${outputId}`);
                            const btnOff = document.getElementById(`btn-off-${outputId}`);
                            const statusEl = document.getElementById(`output-status-${outputId}`);

                            if (isOn) {
                                btnOn.className = 'btn btn-sm btn-success';
                                btnOff.className = 'btn btn-sm btn-outline-danger';
                            } else {
                                btnOn.className = 'btn btn-sm btn-outline-success';
                                btnOff.className = 'btn btn-sm btn-danger';
                            }

                            if (statusEl) {
                                statusEl.textContent = isOn ? 'ON' : 'OFF';
                                statusEl.className = isOn ? 'output-status on' : 'output-status off';
                            }

                            // Show success feedback
                            const card = document.getElementById(`output-card-${outputId}`);
                            if (card) {
                                card.style.borderColor = '#22c55e';
                                setTimeout(() => {
                                    card.style.borderColor = 'rgba(250, 204, 21, 0.3)';
                                }, 500);
                            }

                            console.log('Output updated:', data.message);
                        } else {
                            console.error('Failed to update output');
                            alert('Gagal mengupdate output. Silakan coba lagi.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupdate output.');
                    });
            }

            // ============= IRRIGATION PUMP MODAL FUNCTIONS =============

            // Open irrigation modal with dynamic zone selection
            function openIrrigationModal(outputId, maxZones) {
                // Set output ID in hidden field
                document.getElementById('irrigationOutputId').value = outputId;

                // Populate zone dropdown dynamically
                const zoneSelect = document.getElementById('irrigationZone');
                zoneSelect.innerHTML = '';
                for (let z = 1; z <= maxZones; z++) {
                    const option = document.createElement('option');
                    option.value = z;
                    option.textContent = `Zona ${z}`;
                    zoneSelect.appendChild(option);
                }

                // Reset to default values
                document.getElementById('irrigationWaterType').value = '0'; // Default Air Baku (0)

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('irrigationPumpModal'));
                modal.show();
            }

            // Send pump ON command from modal
            function sendIrrigationPumpOn() {
                const outputId = document.getElementById('irrigationOutputId').value;
                const zone = document.getElementById('irrigationZone').value;
                const waterType = document.getElementById('irrigationWaterType').value;

                // Send via AJAX
                const url = `/monitoring/device/${userDeviceId}/output/${outputId}/irrigation-pump`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        zone: zone,
                        turnOn: true,
                        waterType: waterType
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('irrigationPumpModal'));
                            modal.hide();

                            // Update status display
                            const statusEl = document.getElementById(`pump-status-${outputId}`);
                            if (statusEl) {
                                const waterTypeName = waterType === '1' ? 'Air Baku' : 'Air Pupuk';
                                statusEl.textContent = `Zona ${zone} - ${waterTypeName} - ON`;
                                statusEl.style.color = '#22c55e';
                            }

                            // Flash card border for feedback
                            const card = document.getElementById(`output-card-irrigation-${outputId}`);
                            if (card) {
                                card.style.borderColor = '#22c55e';
                                setTimeout(() => {
                                    card.style.borderColor = 'rgba(14, 165, 233, 0.3)';
                                }, 1000);
                            }

                            console.log('Irrigation pump ON:', data.message);
                        } else {
                            alert('Gagal mengirim perintah pompa. Silakan coba lagi.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim perintah.');
                    });
            }

            // Send pump OFF command (direct, no modal needed)
            function sendIrrigationPumpOff(outputId) {
                const url = `/monitoring/device/${userDeviceId}/output/${outputId}/irrigation-pump`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        zone: '0', // 0 = all zones
                        turnOn: false,
                        waterType: '1'
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update status display
                            const statusEl = document.getElementById(`pump-status-${outputId}`);
                            if (statusEl) {
                                statusEl.textContent = 'OFF';
                                statusEl.style.color = 'var(--text-secondary)';
                            }

                            // Flash card border for feedback
                            const card = document.getElementById(`output-card-irrigation-${outputId}`);
                            if (card) {
                                card.style.borderColor = '#ef4444';
                                setTimeout(() => {
                                    card.style.borderColor = 'rgba(14, 165, 233, 0.3)';
                                }, 1000);
                            }

                            console.log('Irrigation pump OFF:', data.message);
                        } else {
                            alert('Gagal mematikan pompa. Silakan coba lagi.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim perintah.');
                    });
            }

            // Toggle output (AJAX) - kept for range sliders
            function toggleOutput(outputId, value) {
                const url = `/monitoring/device/${userDeviceId}/output/${outputId}/toggle`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ value: value })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update status text for boolean
                            const statusEl = document.getElementById(`output-status-${outputId}`);
                            if (statusEl) {
                                const isOn = data.new_value == 1 || data.new_value === true;
                                statusEl.textContent = isOn ? 'ON' : 'OFF';
                                statusEl.className = isOn ? 'output-status on' : 'output-status off';
                            }

                            // Show success feedback
                            const card = document.getElementById(`output-card-${outputId}`);
                            if (card) {
                                card.style.borderColor = '#22c55e';
                                setTimeout(() => {
                                    card.style.borderColor = 'rgba(250, 204, 21, 0.3)';
                                }, 500);
                            }

                            console.log('Output updated:', data.message);
                        } else {
                            console.error('Failed to update output');
                            alert('Gagal mengupdate output. Silakan coba lagi.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupdate output.');
                    });
            }

            // Update range value display
            function updateRangeValue(outputId, value, unit) {
                const valueEl = document.getElementById(`output-value-${outputId}`);
                if (valueEl) {
                    valueEl.textContent = value + unit;
                }
            }

            function setDateRange(days) {
                const endDate = new Date();
                const startDate = new Date();
                startDate.setDate(startDate.getDate() - days);

                document.querySelector('input[name="start_date"]').value = startDate.toISOString().split('T')[0];
                document.querySelector('input[name="end_date"]').value = endDate.toISOString().split('T')[0];
            }

            // Special Pump Control Functions
            function sendPumpOn() {
                const zone = document.getElementById('pumpZone').value;
                const inputType = document.getElementById('pumpInputType').value;
                const url = `/monitoring/device/${userDeviceId}/pump/control`;

                // Show loading state
                const btn = document.querySelector('#pumpModal .btn-pump-send');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';
                btn.disabled = true;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        action: 'on',
                        zone: zone,
                        input_type: inputType
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update status display
                            const statusEl = document.getElementById('pump-special-status');
                            const typeName = inputType == '0' ? 'Air Baku' : 'Air Pupuk';
                            if (statusEl) {
                                statusEl.textContent = `ON - Zona ${zone} (${typeName})`;
                                statusEl.style.color = '#22c55e';
                            }

                            // Visual feedback
                            const card = document.getElementById('output-card-special-pump');
                            if (card) {
                                card.style.borderColor = '#22c55e';
                                setTimeout(() => {
                                    card.style.borderColor = 'rgba(14, 165, 233, 0.3)';
                                }, 1000);
                            }

                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('pumpModal'));
                            modal.hide();

                            console.log('Pump ON sent:', data.message);
                        } else {
                            alert('Gagal mengirim perintah pompa: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim perintah pompa.');
                    })
                    .finally(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
            }

            function sendPumpOff() {
                const url = `/monitoring/device/${userDeviceId}/pump/control`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ action: 'off' })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update status display
                            const statusEl = document.getElementById('pump-special-status');
                            if (statusEl) {
                                statusEl.textContent = 'OFF';
                                statusEl.style.color = 'var(--text-secondary)';
                            }

                            // Visual feedback
                            const card = document.getElementById('output-card-special-pump');
                            if (card) {
                                card.style.borderColor = '#ef4444';
                                setTimeout(() => {
                                    card.style.borderColor = 'rgba(14, 165, 233, 0.3)';
                                }, 1000);
                            }

                            console.log('Pump OFF sent:', data.message);
                        } else {
                            alert('Gagal mengirim perintah pompa: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim perintah pompa.');
                    });
            }


        </script>
    @endif

    @if($isAdminView ?? false)
        <script>
            // Admin Output Control JavaScript
            const csrfToken = '{{ csrf_token() }}';
            const deviceId = {{ $device->id }};

            // Set output ON/OFF (for buttons)
            function setOutput(outputId, isOn) {
                const url = `/admin/device/${deviceId}/output/${outputId}/toggle`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ value: isOn })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const btnOn = document.getElementById(`btn-on-${outputId}`);
                            const btnOff = document.getElementById(`btn-off-${outputId}`);
                            const statusEl = document.getElementById(`output-status-${outputId}`);

                            if (isOn) {
                                btnOn.className = 'btn btn-sm btn-success';
                                btnOff.className = 'btn btn-sm btn-outline-danger';
                            } else {
                                btnOn.className = 'btn btn-sm btn-outline-success';
                                btnOff.className = 'btn btn-sm btn-danger';
                            }

                            if (statusEl) {
                                statusEl.textContent = isOn ? 'ON' : 'OFF';
                                statusEl.className = isOn ? 'output-status on' : 'output-status off';
                            }

                            const card = document.getElementById(`output-card-${outputId}`);
                            if (card) {
                                card.style.borderColor = '#22c55e';
                                setTimeout(() => {
                                    card.style.borderColor = 'rgba(250, 204, 21, 0.3)';
                                }, 500);
                            }
                        } else {
                            alert('Gagal mengupdate output.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupdate output.');
                    });
            }

            function toggleOutput(outputId, value) {
                const url = `/admin/device/${deviceId}/output/${outputId}/toggle`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ value: value })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const card = document.getElementById(`output-card-${outputId}`);
                            if (card) {
                                card.style.borderColor = '#22c55e';
                                setTimeout(() => {
                                    card.style.borderColor = 'rgba(250, 204, 21, 0.3)';
                                }, 500);
                            }
                        } else {
                            alert('Gagal mengupdate output.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupdate output.');
                    });
            }

            function updateRangeValue(outputId, value, unit) {
                const valueEl = document.getElementById(`output-value-${outputId}`);
                if (valueEl) {
                    valueEl.textContent = value + unit;
                }
            }

            // Auto-reload status every 2 seconds
            setInterval(fetchStatus, 2000);

            async function fetchStatus() {
                try {
                    @if($isAdminView ?? false)
                        const response = await fetch('{{ route("admin.device.status", $device->id) }}');
                    @else
                        const response = await fetch('{{ route("monitoring.status", $userDevice->id) }}');
                    @endif
                                                                                                                                                                        const data = await response.json();

                    if (data.success) {
                        if (data.outputs) {
                            updateOutputs(data.outputs);
                        }
                        if (data.sensors) {
                            updateSensors(data.sensors);
                        }
                    }
                } catch (error) {
                    console.error('Polling error:', error);
                }
            }

            // Map sensor name to ID using PHP array
            const sensorMap = @json($sensors->pluck('id', 'sensor_name'));

            function updateSensors(sensorData) {
                // key is sensor_name (e.g. ni_PH), value is the reading
                for (const [key, value] of Object.entries(sensorData)) {
                    if (sensorMap[key]) {
                        const sensorId = sensorMap[key];
                        const el = document.getElementById(`sensor-val-${sensorId}`);
                        if (el) {
                            // Format number to 1 decimal place if it's a number
                            const num = parseFloat(value);
                            el.innerText = !isNaN(num) ? num.toFixed(1) : value;
                        }
                    }
                }
            }

            function updateOutputs(outputs) {
                outputs.forEach(output => {
                    // Update Boolean Outputs (Buttons)
                    const btnOn = document.getElementById(`btn-on-${output.id}`);
                    const btnOff = document.getElementById(`btn-off-${output.id}`);
                    const statusEl = document.getElementById(`output-status-${output.id}`);

                    if (btnOn && btnOff && statusEl) {
                        const isOn = parseFloat(output.value) > 0;

                        if (isOn) {
                            btnOn.classList.remove('btn-outline-success');
                            btnOn.classList.add('btn-success');
                            btnOff.classList.remove('btn-danger');
                            btnOff.classList.add('btn-outline-danger');
                            statusEl.className = 'output-status on';
                            statusEl.innerText = 'ON';
                        } else {
                            btnOn.classList.remove('btn-success');
                            btnOn.classList.add('btn-outline-success');
                            btnOff.classList.remove('btn-outline-danger');
                            btnOff.classList.add('btn-danger');
                            statusEl.className = 'output-status off';
                            statusEl.innerText = 'OFF';
                        }
                    }

                    // Update Range/Slider Outputs
                    const slider = document.getElementById(`output-${output.id}`);
                    const valueDisplay = document.getElementById(`output-value-${output.id}`);

                    if (slider && document.activeElement !== slider) {
                        slider.value = output.value;
                        if (valueDisplay) {
                            // Extract unit from existing text or data attribute (simplification needed?)
                            // Assuming unit is static suffix for now or extract last non-digits
                            const currentText = valueDisplay.innerText;
                            const unit = currentText.replace(/[0-9\.]/g, '');
                            valueDisplay.innerText = parseInt(output.value) + unit;
                        }
                    }
                });
            }
        </script>
    @endif

    {{-- Auto-Reload Script - Always runs regardless of initial data --}}
    <script>
        // Auto-reload status every 2 seconds
        setInterval(fetchStatus, 2000);

        async function fetchStatus() {
            try {
                @if($isAdminView ?? false)
                    const response = await fetch('{{ route("admin.device.status", $device->id) }}');
                @else
                    const response = await fetch('{{ route("monitoring.status", $userDevice->id) }}');
                @endif
                const data = await response.json();

                if (data.success) {
                    if (data.outputs) {
                        updateOutputs(data.outputs);
                    }
                    if (data.sensors) {
                        updateSensors(data.sensors);
                    }
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }

        // Map sensor name to ID using PHP array
        const sensorMap = @json($sensors->pluck('id', 'sensor_name'));

        function updateSensors(sensorData) {
            for (const [key, value] of Object.entries(sensorData)) {
                if (sensorMap[key]) {
                    const sensorId = sensorMap[key];
                    const el = document.getElementById(`sensor-val-${sensorId}`);
                    if (el) {
                        const num = parseFloat(value);
                        el.innerText = !isNaN(num) ? num.toFixed(1) : value;
                    }
                }
            }
        }

        function updateOutputs(outputs) {
            outputs.forEach(output => {
                // Update Boolean Outputs (Buttons)
                const btnOn = document.getElementById(`btn-on-${output.id}`);
                const btnOff = document.getElementById(`btn-off-${output.id}`);
                const statusEl = document.getElementById(`output-status-${output.id}`);

                if (btnOn && btnOff && statusEl) {
                    const isOn = parseFloat(output.value) > 0;

                    if (isOn) {
                        btnOn.classList.remove('btn-outline-success');
                        btnOn.classList.add('btn-success');
                        btnOff.classList.remove('btn-danger');
                        btnOff.classList.add('btn-outline-danger');
                        statusEl.className = 'output-status on';
                        statusEl.innerText = 'ON';
                    } else {
                        btnOn.classList.remove('btn-success');
                        btnOn.classList.add('btn-outline-success');
                        btnOff.classList.remove('btn-outline-danger');
                        btnOff.classList.add('btn-danger');
                        statusEl.className = 'output-status off';
                        statusEl.innerText = 'OFF';
                    }
                }

                // Update Range/Slider Outputs
                const slider = document.getElementById(`output-${output.id}`);
                const valueDisplay = document.getElementById(`output-value-${output.id}`);

                if (slider && document.activeElement !== slider) {
                    slider.value = output.value;
                    if (valueDisplay) {
                        const currentText = valueDisplay.innerText;
                        const unit = currentText.replace(/[0-9\.]/g, '');
                        valueDisplay.innerText = parseInt(output.value) + unit;
                    }
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.pwa-scripts')
</body>

</html>