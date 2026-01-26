@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 min-vh-100 d-flex align-items-center justify-content-center">
        <div class="w-100" style="max-width: 900px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('automasi.index', $device->id) }}" class="btn btn-circle btn-outline-light">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="text-white fw-bold mb-0 text-shadow text-uppercase bg-warning text-dark px-4 py-2 rounded-bill">
                    Setting Pemupukan Otomatis
                </h2>
                <button onclick="location.reload()" class="btn btn-link text-white text-decoration-none">
                    segarkan <i class="bi bi-arrow-clockwise fs-4 align-middle"></i>
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('automasi.fertilizer.store', $device->id) }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- COL 1: POMPA MIX A DAN B -->
                    <div class="col-md-6">
                        <div class="card card-glass border-0 shadow-lg h-100 position-relative overflow-hidden">
                            <!-- Vector BG hint -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-landscape-1"
                                style="opacity: 0.2; z-index: 0;"></div>

                            <div class="card-body position-relative" style="z-index: 1;">
                                <div
                                    class="bg-warning text-dark fw-bold text-center py-2 mb-4 fs-5 rounded shadow-sm text-uppercase">
                                    Pompa Mix A dan B
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-white fw-bold fs-5">Batas Atas</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="ats_tds"
                                            value="{{ $settings['ats_tds'] ?? '' }}"
                                            class="form-control form-control-lg text-center fw-bold" placeholder="0">
                                        <span class="input-group-text fw-bold bg-white text-dark">ppm</span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-white fw-bold fs-5">Batas Bawah</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="bwh_tds"
                                            value="{{ $settings['bwh_tds'] ?? '' }}"
                                            class="form-control form-control-lg text-center fw-bold" placeholder="0">
                                        <span class="input-group-text fw-bold bg-white text-dark">ppm</span>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success btn-lg px-5 fw-bold shadow">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COL 2: POMPA PH -->
                    <div class="col-md-6">
                        <div class="card card-glass border-0 shadow-lg h-100 position-relative overflow-hidden">
                            <!-- Vector BG hint -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-landscape-2"
                                style="opacity: 0.2; z-index: 0;"></div>

                            <div class="card-body position-relative" style="z-index: 1;">
                                <div
                                    class="bg-warning text-dark fw-bold text-center py-2 mb-4 fs-5 rounded shadow-sm text-uppercase">
                                    Pompa pH
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-white fw-bold fs-5">Batas Atas</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="ats_ph"
                                            value="{{ $settings['ats_ph'] ?? '' }}"
                                            class="form-control form-control-lg text-center fw-bold" placeholder="0">
                                        <span class="input-group-text fw-bold bg-white text-dark">pH</span>
                                        <!-- Assume pH unit -->
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-white fw-bold fs-5">Batas Bawah</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="bwh_ph"
                                            value="{{ $settings['bwh_ph'] ?? '' }}"
                                            class="form-control form-control-lg text-center fw-bold" placeholder="0">
                                        <span class="input-group-text fw-bold bg-white text-dark">pH</span>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success btn-lg px-5 fw-bold shadow">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .rounded-bill {
            border-radius: 50px;
            border: 2px solid #fff;
        }

        .btn-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Adding pseudo-elements or classes for vector art background could go here if assets existed */
    </style>
@endsection