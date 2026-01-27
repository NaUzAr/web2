@extends('layouts.app')

@section('content')
    <div class="container py-4 min-vh-100 d-flex flex-column align-items-center justify-content-center">

        <div class="w-100 mb-4" style="max-width: 600px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('monitoring.show', $device->id) }}" class="btn btn-glass btn-circle text-white">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h2 class="text-white fw-bold mb-0 text-uppercase text-shadow text-center flex-grow-1">
                    Menu Otomasi
                </h2>
                <div style="width: 50px;"></div> <!-- Spacer for centering -->
            </div>

            <div class="d-flex flex-column gap-3">

                @if($hasFertilizer ?? false)
                    <!-- PEMUPUKAN BUTTON -->
                    <a href="{{ route('automasi.fertilizer', $device->id) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-lg hover-scale overflow-hidden"
                            style="background: linear-gradient(135deg, #facc15 0%, #ca8a04 100%); border-radius: 20px;">
                            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box bg-white text-warning shadow-sm">
                                        <i class="bi bi-flower1 fs-2"></i>
                                    </div>
                                    <div class="text-white">
                                        <h3 class="fw-bold mb-0 text-uppercase text-shadow">Pemupukan</h3>
                                        <small class="text-white-50">Setting Pompa Mix & pH</small>
                                    </div>
                                </div>
                                <div class="btn btn-light rounded-circle btn-icon">
                                    <i class="bi bi-chevron-right text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif

                @if($hasClimate ?? false)
                    <!-- CLIMATE BUTTON -->
                    <a href="{{ route('automasi.climate', $device->id) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-lg hover-scale overflow-hidden"
                            style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border-radius: 20px;">
                            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box bg-white text-info shadow-sm">
                                        <i class="bi bi-thermometer-sun fs-2"></i>
                                    </div>
                                    <div class="text-white">
                                        <h3 class="fw-bold mb-0 text-uppercase text-shadow">Climate</h3>
                                        <small class="text-white-50">Setting Suhu & Kelembaban</small>
                                    </div>
                                </div>
                                <div class="btn btn-light rounded-circle btn-icon">
                                    <i class="bi bi-chevron-right text-info"></i>
                                </div>
                            </div>
                        </div>
                    </a>
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

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }
    </style>
@endsection