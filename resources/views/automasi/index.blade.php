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
                            <a href="{{ route('automasi.fertilizer', $device->id) }}"
                                class="btn btn-light btn-sm fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <!-- Items -->
                            <div class="row g-3">
                                <!-- TDS / Nutrisi -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.2);">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-droplet-half text-warning fs-4 me-2"></i>
                                            <span class="text-white fw-bold">Pompa Mix (TDS)</span>
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
                                    <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.2);">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-speedometer2 text-warning fs-4 me-2"></i>
                                            <span class="text-white fw-bold">Pompa pH</span>
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
                            <a href="{{ route('automasi.climate', $device->id) }}"
                                class="btn btn-light btn-sm fw-bold rounded-pill px-3 shadow-sm">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <!-- Items -->
                            <div class="row g-3">
                                <!-- Suhu -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.2);">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-thermometer-half text-info fs-4 me-2"></i>
                                            <span class="text-white fw-bold">Suhu Ruangan</span>
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
                                    <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.2);">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-droplet text-info fs-4 me-2"></i>
                                            <span class="text-white fw-bold">Kelembaban</span>
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
    </style>
@endsection