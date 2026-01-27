@extends('layouts.app')

@section('content')
    <div class="container py-4 min-vh-100 d-flex flex-column align-items-center justify-content-center">

        <div class="w-100 mb-4" style="max-width: 800px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('monitoring.show', $device->id) }}" class="btn btn-glass btn-circle text-white">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h2 class="text-white fw-bold mb-0 text-uppercase text-shadow text-center flex-grow-1">
                    List Otomasi
                </h2>
                <div style="width: 50px;"></div>
            </div>

            <div class="d-flex flex-column gap-4">

                @if($hasFertilizer ?? false)
                    <!-- PEMUPUKAN SECTION -->
                    <div class="card border-0 shadow-lg overflow-hidden"
                        style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 20px;">
                        <div class="card-header border-0 py-3 px-4 d-flex justify-content-between align-items-center"
                            style="background: linear-gradient(90deg, #ca8a04 0%, #facc15 100%);">
                            <h4 class="mb-0 fw-bold text-white text-uppercase text-shadow"><i class="bi bi-flower1 me-2"></i>
                                Pemupukan</h4>
                        </div>
                        <div class="card-body p-4">
                            <!-- Items -->
                            <div class="row g-3">
                                <!-- TDS / Nutrisi -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between"
                                        style="background: rgba(0,0,0,0.2);">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-droplet-half text-warning fs-4 me-2"></i>
                                                <span class="text-white fw-bold">Pompa Mix (TDS)</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('tds', 'Pompa Mix (TDS)', 'ppm', {{ $settings['ats_tds'] ?? 0 }}, {{ $settings['bwh_tds'] ?? 0 }})">
                                                <i class="bi bi-pencil-square text-dark"></i>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between text-white-50 small mb-1">
                                            <span>Batas Atas:</span>
                                            <span class="text-white fw-bold">{{ $settings['ats_tds'] ?? '-' }} ppm</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-white-50 small">
                                            <span>Batas Bawah:</span>
                                            <span class="text-white fw-bold">{{ $settings['bwh_tds'] ?? '-' }} ppm</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- pH -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between"
                                        style="background: rgba(0,0,0,0.2);">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-speedometer2 text-warning fs-4 me-2"></i>
                                                <span class="text-white fw-bold">Pompa pH</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('ph', 'Pompa pH', '', {{ $settings['ats_ph'] ?? 0 }}, {{ $settings['bwh_ph'] ?? 0 }})">
                                                <i class="bi bi-pencil-square text-dark"></i>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between text-white-50 small mb-1">
                                            <span>Batas Atas:</span>
                                            <span class="text-white fw-bold">{{ $settings['ats_ph'] ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-white-50 small">
                                            <span>Batas Bawah:</span>
                                            <span class="text-white fw-bold">{{ $settings['bwh_ph'] ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($hasClimate ?? false)
                    <!-- CLIMATE SECTION -->
                    <div class="card border-0 shadow-lg overflow-hidden"
                        style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 20px;">
                        <div class="card-header border-0 py-3 px-4 d-flex justify-content-between align-items-center"
                            style="background: linear-gradient(90deg, #0891b2 0%, #06b6d4 100%);">
                            <h4 class="mb-0 fw-bold text-white text-uppercase text-shadow"><i
                                    class="bi bi-thermometer-sun me-2"></i> Climate</h4>
                        </div>
                        <div class="card-body p-4">
                            <!-- Items -->
                            <div class="row g-3">
                                <!-- Suhu -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between"
                                        style="background: rgba(0,0,0,0.2);">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-thermometer-half text-info fs-4 me-2"></i>
                                                <span class="text-white fw-bold">Suhu Ruangan</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('suhu', 'Suhu Ruangan', '°C', {{ $settings['ats_suhu'] ?? 0 }}, {{ $settings['bwh_suhu'] ?? 0 }})">
                                                <i class="bi bi-pencil-square text-dark"></i>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between text-white-50 small mb-1">
                                            <span>Batas Atas:</span>
                                            <span class="text-white fw-bold">{{ $settings['ats_suhu'] ?? '-' }} °C</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-white-50 small">
                                            <span>Batas Bawah:</span>
                                            <span class="text-white fw-bold">{{ $settings['bwh_suhu'] ?? '-' }} °C</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Kelembaban -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between"
                                        style="background: rgba(0,0,0,0.2);">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-droplet text-info fs-4 me-2"></i>
                                                <span class="text-white fw-bold">Kelembaban</span>
                                            </div>
                                            <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm"
                                                onclick="openEditModal('kelem', 'Kelembaban', '%', {{ $settings['ats_kelem'] ?? 0 }}, {{ $settings['bwh_kelem'] ?? 0 }})">
                                                <i class="bi bi-pencil-square text-dark"></i>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between text-white-50 small mb-1">
                                            <span>Batas Atas:</span>
                                            <span class="text-white fw-bold">{{ $settings['ats_kelem'] ?? '-' }} %</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-white-50 small">
                                            <span>Batas Bawah:</span>
                                            <span class="text-white fw-bold">{{ $settings['bwh_kelem'] ?? '-' }} %</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!($hasFertilizer ?? false) && !($hasClimate ?? false))
                    <div class="alert alert-warning bg-opacity-25 border-warning text-white text-center rounded-4 p-4">
                        <i class="bi bi-exclamation-triangle fs-1 mb-2 d-block"></i>
                        <h5 class="fw-bold">Belum Ada Fitur Otomasi</h5>
                        <p class="mb-0 small text-white-50">Device ini tidak memiliki sensor yang mendukung otomasi
                            (Suhu/Kelembaban atau pH/TDS).</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white"
                style="background: rgba(40, 40, 43, 0.95); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title fw-bold" id="editModalLabel">Edit Setting</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <!-- Route update single handling -->
                <form action="{{ route('automasi.update_single', $device->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <input type="hidden" name="sensor_type" id="modalSensorType">

                        <div class="mb-3">
                            <label for="atsVal" class="form-label text-white-50">Batas Atas <span
                                    id="modalUnit1"></span></label>
                            <input type="number" step="0.01" class="form-control bg-dark text-white border-secondary"
                                id="atsVal" name="ats_val" required>
                        </div>

                        <div class="mb-3">
                            <label for="bwhVal" class="form-label text-white-50">Batas Bawah <span
                                    id="modalUnit2"></span></label>
                            <input type="number" step="0.01" class="form-control bg-dark text-white border-secondary"
                                id="bwhVal" name="bwh_val" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-outline-light rounded-pill"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    <style>
        .btn-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
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
            background-color: white !important;
        }

        .modal-content {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
    </style>
@endsection