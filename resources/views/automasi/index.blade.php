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
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-tree-fill me-2"></i>Swaratani
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('monitoring.show', $deviceId) }}">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
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
                            <!-- Items -->
                            <div class="row g-3">
                                <!-- TDS / Nutrisi -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between"
                                        style="background: var(--glass-bg);">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-droplet-half text-warning fs-4 me-2"></i>
                                                <span class="fw-bold" style="color: var(--text-main);">Pompa Mix
                                                    (TDS)</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('tds', 'Pompa Mix (TDS)', 'ppm', {{ $settings['ats_tds'] ?? 0 }}, {{ $settings['bwh_tds'] ?? 0 }})">
                                                <i class="bi bi-pencil-square text-dark"></i>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-1"
                                            style="color: var(--text-secondary);">
                                            <span>Batas Atas:</span>
                                            <span class="fw-bold" style="color: var(--text-main);">{{ $settings['ats_tds'] ?? '-' }} ppm</span>
                                        </div>
                                        <div class="d-flex justify-content-between small"
                                            style="color: var(--text-secondary);">
                                            <span>Batas Bawah:</span>
                                            <span class="fw-bold" style="color: var(--text-main);">{{ $settings['bwh_tds'] ?? '-' }} ppm</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- pH -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between"
                                        style="background: var(--glass-bg);">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-speedometer2 text-warning fs-4 me-2"></i>
                                                <span class="fw-bold" style="color: var(--text-main);">Pompa pH</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('ph', 'Pompa pH', '', {{ $settings['ats_ph'] ?? 0 }}, {{ $settings['bwh_ph'] ?? 0 }})">
                                                <i class="bi bi-pencil-square text-dark"></i>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-1"
                                            style="color: var(--text-secondary);">
                                            <span>Batas Atas:</span>
                                            <span class="fw-bold" style="color: var(--text-main);">{{ $settings['ats_ph'] ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between small"
                                            style="color: var(--text-secondary);">
                                            <span>Batas Bawah:</span>
                                            <span class="fw-bold" style="color: var(--text-main);">{{ $settings['bwh_ph'] ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($hasClimate ?? false)
                    <!-- CLIMATE SECTION -->
                    <div class="sensor-card text-start p-0 overflow-hidden">
                        <div class="px-4 py-3 d-flex justify-content-between align-items-center"
                            style="background: linear-gradient(90deg, #0891b2 0%, #06b6d4 100%);">
                            <h4 class="mb-0 fw-bold text-white text-uppercase text-shadow"><i
                                    class="bi bi-thermometer-sun me-2"></i> Climate</h4>
                        </div>
                        <div class="p-4">
                            <!-- Items -->
                            <div class="row g-3">
                                <!-- Suhu -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between"
                                        style="background: var(--glass-bg);">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-thermometer-half text-info fs-4 me-2"></i>
                                                <span class="fw-bold" style="color: var(--text-main);">Suhu Ruangan</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('suhu', 'Suhu Ruangan', '°C', {{ $settings['ats_suhu'] ?? 0 }}, {{ $settings['bwh_suhu'] ?? 0 }})">
                                                <i class="bi bi-pencil-square text-dark"></i>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-1"
                                            style="color: var(--text-secondary);">
                                            <span>Batas Atas:</span>
                                            <span class="fw-bold" style="color: var(--text-main);">{{ $settings['ats_suhu'] ?? '-' }} °C</span>
                                        </div>
                                        <div class="d-flex justify-content-between small"
                                            style="color: var(--text-secondary);">
                                            <span>Batas Bawah:</span>
                                            <span class="fw-bold" style="color: var(--text-main);">{{ $settings['bwh_suhu'] ?? '-' }} °C</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Kelembaban -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between"
                                        style="background: var(--glass-bg);">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-droplet text-info fs-4 me-2"></i>
                                                <span class="fw-bold" style="color: var(--text-main);">Kelembaban</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('kelem', 'Kelembaban', '%', {{ $settings['ats_kelem'] ?? 0 }}, {{ $settings['bwh_kelem'] ?? 0 }})">
                                                <i class="bi bi-pencil-square text-dark"></i>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-1"
                                            style="color: var(--text-secondary);">
                                            <span>Batas Atas:</span>
                                            <span class="fw-bold" style="color: var(--text-main);">{{ $settings['ats_kelem'] ?? '-' }} %</span>
                                        </div>
                                        <div class="d-flex justify-content-between small"
                                            style="color: var(--text-secondary);">
                                            <span>Batas Bawah:</span>
                                            <span class="fw-bold" style="color: var(--text-main);">{{ $settings['bwh_kelem'] ?? '-' }} %</span>
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