@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-white fw-bold">Otomasi Custom</h1>
                <p class="text-white-50 mb-0">Atur parameter otomatisasi untuk device {{ $device->name }}</p>
            </div>
            <a href="{{ route('monitoring.show', $device->id) }}" class="btn btn-outline-light btn-glass">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Monitoring
            </a>
        </div>

        <div class="row g-4">
            <!-- Card Pemupukan -->
            @if($hasFertilizer ?? false)
                <!-- Card Pemupukan -->
                <div class="col-md-6 col-lg-4">
                    <div class="card card-glass h-100 border-0 shadow-lg hover-scale">
                        <div class="card-body p-4 text-center d-flex flex-column justify-content-center align-items-center">
                            <div class="icon-circle bg-success bg-gradient text-white mb-3 shadow">
                                <i class="bi bi-flower1 fs-1"></i>
                            </div>
                            <h3 class="card-title fw-bold text-white">Pemupukan Otomatis</h3>
                            <p class="card-text text-white-50 mb-4">Pengaturan batas PPM untuk Pompa Mix A/B dan batas pH untuk
                                Pompa pH.</p>
                            <a href="{{ route('automasi.fertilizer', $device->id) }}"
                                class="btn btn-primary w-100 py-2 fw-bold text-uppercase">
                                Masuk Setting
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Card Climate -->
            @if($hasClimate ?? false)
                <!-- Card Climate -->
                <div class="col-md-6 col-lg-4">
                    <div class="card card-glass h-100 border-0 shadow-lg hover-scale">
                        <div class="card-body p-4 text-center d-flex flex-column justify-content-center align-items-center">
                            <div class="icon-circle bg-info bg-gradient text-white mb-3 shadow">
                                <i class="bi bi-thermometer-sun fs-1"></i>
                            </div>
                            <h3 class="card-title fw-bold text-white">Climate Auto Setting</h3>
                            <p class="card-text text-white-50 mb-4">Pengaturan batas Suhu dan Kelembaban (Humidity) untuk
                                kontrol iklim.</p>
                            <a href="{{ route('automasi.climate', $device->id) }}"
                                class="btn btn-primary w-100 py-2 fw-bold text-uppercase">
                                Masuk Setting
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if(!($hasFertilizer ?? false) && !($hasClimate ?? false))
                <div class="col-12 text-center py-5">
                    <div class="alert alert-warning bg-opacity-10 border-warning text-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Device ini belum memiliki konfigurasi sensor untuk Otomasi (Climate/Fertilizer).
                        <br><small class="text-white-50">Silakan edit device dan tambahkan sensor terkait.</small>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: translateY(-5px);
        }
    </style>
@endsection