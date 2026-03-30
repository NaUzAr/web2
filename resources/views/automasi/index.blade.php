<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Otomasi - {{ $device->name }}</title>
    @include('partials.pwa-head')
    @include('partials.theme')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* Page Specific Overrides */
        body {
            background: var(--gradient-bg);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            color: var(--text-main);
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

        /* Navbar Glass */
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

        .sensor-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            transition: all 0.3s ease;
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

        .btn-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-icon:hover {
            transform: scale(1.1);
            background-color: var(--glass-bg) !important;
            color: var(--primary);
        }

        /* Premium Params */
        .param-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        }
        
        .param-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.06);
            border-color: var(--primary);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .limit-row span:first-child {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .limit-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px dashed var(--glass-border);
        }
        
        .limit-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .limit-badge {
            background: rgba(14, 95, 138, 0.1);
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 800;
            font-size: 1.1rem;
            border: 1px solid rgba(14, 95, 138, 0.2);
        }

        /* Modal specific matching monitoring style */
        .modal-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            border-radius: 20px;
        }

        .form-glass {
            background-color: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
        }

        .form-glass:focus {
            background-color: var(--glass-bg);
            border-color: var(--primary);
            color: var(--text-main);
            box-shadow: 0 0 0 0.25rem rgba(var(--primary), 0.25);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: #fff;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        /* ========= Mobile Responsive ========= */
        @media (max-width: 768px) {
            .limit-badge {
                font-size: 0.85rem;
                padding: 4px 12px;
            }
            .limit-row span:first-child {
                font-size: 0.9rem;
            }

            .container.py-4 {
                padding: 1rem 0.75rem !important;
            }

            .page-header {
                padding: 1rem 1.25rem;
                border-radius: 16px;
            }

            .device-title {
                font-size: 1.15rem;
            }

            .sensor-card .px-4 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .sensor-card .p-4 {
                padding: 1rem !important;
            }

            .sensor-card h4 {
                font-size: 1rem;
            }

            /* Modal touch-friendly */
            .form-glass {
                font-size: 16px;
                min-height: 48px;
            }

            .btn-icon {
                width: 40px;
                height: 40px;
            }
        }

        @media (max-width: 400px) {
            .container.py-4 {
                padding: 0.75rem 0.5rem !important;
            }

            .page-header {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Swaratani" height="40" class="me-2">
                <span class="fw-bold" style="color: var(--navbar_text, #333);">Swaratani IoT</span>
            </a>
            <div class="navbar-nav ms-auto flex-row align-items-center gap-4 gap-sm-3">
                <a class="nav-link px-0 text-decoration-none" href="{{ route('monitoring.show', $deviceId) }}" title="Kembali ke Device" style="color: var(--navbar-text);">
                    <i class="bi bi-arrow-left fs-5 me-2 me-sm-1" style="-webkit-text-stroke: 1px currentColor;"></i>
                    <i class="bi bi-display fs-5 me-sm-1"></i><span class="d-none d-sm-inline"> Tampilan Device</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4 min-vh-100 d-flex flex-column">

        <div class="w-100 mb-4 mx-auto" style="max-width: 800px;">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <h2 class="device-title text-center flex-grow-1 text-uppercase">
                    List Otomasi - {{ $device->name }}
                </h2>
            </div>

            <div class="d-flex flex-column gap-4">

                @if($hasFertilizer ?? false)
                    <!-- PEMUPUKAN SECTION -->
                    <div class="sensor-card text-start p-0 overflow-hidden">
                        <div class="px-4 py-3 d-flex justify-content-between align-items-center"
                            style="background: linear-gradient(90deg, #ca8a04 0%, #facc15 100%);">
                            <h4 class="mb-0 fw-bold text-white text-uppercase text-shadow"><i
                                    class="bi bi-flower1 me-2"></i> Pemupukan</h4>
                        </div>
                        <div class="p-4">
                            <div class="row g-4">
                                <!-- TDS / Nutrisi -->
                                <div class="col-md-6">
                                    <div class="param-card d-flex flex-column justify-content-between">
                                        <div class="d-flex align-items-center justify-content-between mb-4 pb-3" style="border-bottom: 1px solid var(--glass-border);">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box" style="background: rgba(234, 179, 8, 0.1);">
                                                    <i class="bi bi-droplet-half text-warning fs-3"></i>
                                                </div>
                                                <span class="fw-bold fs-5" style="color: var(--text-main);">Pompa Mix (TDS)</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('tds', 'Pompa Mix (TDS)', 'ppm', {{ $settings['ats_tds'] ?? 0 }}, {{ $settings['bwh_tds'] ?? 0 }})" title="Edit Otomasi Pompa Mix">
                                                <i class="bi bi-pencil-square text-dark fs-5"></i>
                                            </button>
                                        </div>
                                        <div class="limit-row">
                                            <span style="color: var(--text-secondary); font-weight: 500;">Batas Atas</span>
                                            <span class="limit-badge">{{ $settings['ats_tds'] ?? '-' }} ppm</span>
                                        </div>
                                        <div class="limit-row">
                                            <span style="color: var(--text-secondary); font-weight: 500;">Batas Bawah</span>
                                            <span class="limit-badge">{{ $settings['bwh_tds'] ?? '-' }} ppm</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- pH -->
                                <div class="col-md-6">
                                    <div class="param-card d-flex flex-column justify-content-between">
                                        <div class="d-flex align-items-center justify-content-between mb-4 pb-3" style="border-bottom: 1px solid var(--glass-border);">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box" style="background: rgba(20, 184, 166, 0.1);">
                                                    <i class="bi bi-speedometer2 text-teal fs-3" style="color: #14b8a6;"></i>
                                                </div>
                                                <span class="fw-bold fs-5" style="color: var(--text-main);">Pompa pH</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('ph', 'Pompa pH', '', {{ $settings['ats_ph'] ?? 0 }}, {{ $settings['bwh_ph'] ?? 0 }})" title="Edit Otomasi Pompa pH">
                                                <i class="bi bi-pencil-square text-dark fs-5"></i>
                                            </button>
                                        </div>
                                        <div class="limit-row">
                                            <span style="color: var(--text-secondary); font-weight: 500;">Batas Atas</span>
                                            <span class="limit-badge">{{ $settings['ats_ph'] ?? '-' }}</span>
                                        </div>
                                        <div class="limit-row">
                                            <span style="color: var(--text-secondary); font-weight: 500;">Batas Bawah</span>
                                            <span class="limit-badge">{{ $settings['bwh_ph'] ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($hasClimate ?? false)
                    <!-- CLIMATE SECTION -->
                    <div class="sensor-card text-start p-0 overflow-hidden mt-2">
                        <div class="px-4 py-3 d-flex justify-content-between align-items-center"
                            style="background: linear-gradient(90deg, #0891b2 0%, #06b6d4 100%);">
                            <h4 class="mb-0 fw-bold text-white text-uppercase text-shadow"><i
                                    class="bi bi-thermometer-sun me-2"></i> Climate</h4>
                        </div>
                        <div class="p-4">
                            <!-- Items -->
                            <div class="row g-4">
                                <!-- Suhu -->
                                <div class="col-md-6">
                                    <div class="param-card d-flex flex-column justify-content-between">
                                        <div class="d-flex align-items-center justify-content-between mb-4 pb-3" style="border-bottom: 1px solid var(--glass-border);">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box" style="background: rgba(239, 68, 68, 0.1);">
                                                    <i class="bi bi-thermometer-half text-danger fs-3"></i>
                                                </div>
                                                <span class="fw-bold fs-5" style="color: var(--text-main);">Suhu Ruangan</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('suhu', 'Suhu Ruangan', '°C', {{ $settings['ats_suhu'] ?? 0 }}, {{ $settings['bwh_suhu'] ?? 0 }})" title="Edit Otomasi Suhu Ruangan">
                                                <i class="bi bi-pencil-square text-dark fs-5"></i>
                                            </button>
                                        </div>
                                        <div class="limit-row">
                                            <span style="color: var(--text-secondary); font-weight: 500;">Batas Atas</span>
                                            <span class="limit-badge">{{ $settings['ats_suhu'] ?? '-' }} °C</span>
                                        </div>
                                        <div class="limit-row">
                                            <span style="color: var(--text-secondary); font-weight: 500;">Batas Bawah</span>
                                            <span class="limit-badge">{{ $settings['bwh_suhu'] ?? '-' }} °C</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Kelembaban -->
                                <div class="col-md-6">
                                    <div class="param-card d-flex flex-column justify-content-between">
                                        <div class="d-flex align-items-center justify-content-between mb-4 pb-3" style="border-bottom: 1px solid var(--glass-border);">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box" style="background: rgba(59, 130, 246, 0.1);">
                                                    <i class="bi bi-droplet text-primary fs-3"></i>
                                                </div>
                                                <span class="fw-bold fs-5" style="color: var(--text-main);">Kelembaban</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('kelem', 'Kelembaban', '%', {{ $settings['ats_kelem'] ?? 0 }}, {{ $settings['bwh_kelem'] ?? 0 }})" title="Edit Otomasi Kelembaban">
                                                <i class="bi bi-pencil-square text-dark fs-5"></i>
                                            </button>
                                        </div>
                                        <div class="limit-row">
                                            <span style="color: var(--text-secondary); font-weight: 500;">Batas Atas</span>
                                            <span class="limit-badge">{{ $settings['ats_kelem'] ?? '-' }} %</span>
                                        </div>
                                        <div class="limit-row">
                                            <span style="color: var(--text-secondary); font-weight: 500;">Batas Bawah</span>
                                            <span class="limit-badge">{{ $settings['bwh_kelem'] ?? '-' }} %</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!($hasFertilizer ?? false) && !($hasClimate ?? false))
                    <div class="alert alert-warning bg-opacity-25 border-warning text-center rounded-4 p-4"
                        style="color: var(--text-main);">
                        <i class="bi bi-exclamation-triangle fs-1 mb-2 d-block"></i>
                        <h5 class="fw-bold">Belum Ada Fitur Otomasi</h5>
                        <p class="mb-0 small" style="color: var(--text-secondary);">Device ini tidak memiliki sensor yang
                            mendukung otomasi
                            (Suhu/Kelembaban atau pH/TDS).</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-glass">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title fw-bold" id="editModalLabel">Edit Setting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Route update single handling -->
                <form action="{{ route('automasi.update_single', ['id' => $deviceId]) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <input type="hidden" name="sensor_type" id="modalSensorType">

                        <div class="mb-3">
                            <label for="atsVal" class="form-label" style="color: var(--text-secondary);">Batas Atas
                                <span id="modalUnit1"></span></label>
                            <input type="number" step="0.01" class="form-control form-glass" id="atsVal" name="ats_val"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="bwhVal" class="form-label" style="color: var(--text-secondary);">Batas Bawah
                                <span id="modalUnit2"></span></label>
                            <input type="number" step="0.01" class="form-control form-glass" id="bwhVal" name="bwh_val"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-outline-light rounded-pill"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEditModal(sensorType, title, unit, currentTop, currentBottom) {
            document.getElementById('editModalLabel').textContent = 'Edit ' + title;
            document.getElementById('modalSensorType').value = sensorType;

            document.getElementById('atsVal').value = currentTop;
            document.getElementById('bwhVal').value = currentBottom;

            const unitText = unit ? '(' + unit + ')' : '';
            document.getElementById('modalUnit1').textContent = unitText;
            document.getElementById('modalUnit2').textContent = unitText;

            var myModal = new bootstrap.Modal(document.getElementById('editModal'));
            myModal.show();
        }
    </script>
</body>

</html>