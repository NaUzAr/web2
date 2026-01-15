<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Device - Smart Agriculture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary-green: #22c55e;
            --dark-green: #166534;
            --light-green: #86efac;
            --sky-blue: #0ea5e9;
            --light-sky: #7dd3fc;
            --primary-gradient: linear-gradient(135deg, #22c55e 0%, #16a34a 50%, #0ea5e9 100%);
            --secondary-gradient: linear-gradient(135deg, #86efac 0%, #22c55e 100%);
            --nature-gradient: linear-gradient(135deg, #134e4a 0%, #166534 50%, #14532d 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--nature-gradient);
            min-height: 100vh;
        }

        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 20% 80%, rgba(34, 197, 94, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(14, 165, 233, 0.2) 0%, transparent 50%);
        }

        .navbar-glass {
            background: rgba(20, 83, 45, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
        }

        .navbar-brand {
            font-weight: 700;
            color: #86efac !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .nav-link:hover {
            color: #86efac !important;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #facc15 0%, #f59e0b 100%);
            border-radius: 24px 24px 0 0 !important;
            padding: 1.5rem 2rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            color: #fff;
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-green);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control-readonly {
            background: rgba(255, 255, 255, 0.05) !important;
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .form-label {
            color: #86efac;
            font-weight: 600;
        }

        .form-text {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
            color: #fff;
        }

        .btn-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 1rem;
        }

        .info-label {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #fff;
            font-weight: 600;
        }

        .badge-sensor {
            background: rgba(34, 197, 94, 0.2);
            color: #86efac;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 10px;
            margin: 4px;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
        }

        .badge-sensor-name {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-sensor-column {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            font-family: monospace;
        }

        .sensors-container {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1rem;
        }

        .alert-info-custom {
            background: rgba(14, 165, 233, 0.15);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: var(--light-sky);
            border-radius: 12px;
        }

        .badge-type {
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
            color: #fff;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
        }

        .badge-output {
            background: rgba(250, 204, 21, 0.2);
            color: #fde047;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 10px;
            margin: 4px;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
        }

        .badge-output-name {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-output-type {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-tree-fill me-2"></i>SmartAgri
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.devices.index') }}">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Devices
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="glass-card shadow-lg">
                    <div class="card-header-gradient">
                        <h4 class="mb-0 text-dark">
                            <i class="bi bi-pencil-square me-2"></i>Edit Device
                        </h4>
                    </div>
                    <div class="card-body p-4">

                        <form action="{{ route('admin.device.update', $device->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Info Read Only -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-label"><i class="bi bi-key me-1"></i>Token</div>
                                        <div class="info-value font-monospace">{{ $device->token }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-label"><i class="bi bi-cpu me-1"></i>Tipe Alat</div>
                                        <div class="info-value">
                                            <span class="badge-type">
                                                <i
                                                    class="bi {{ $device->type === 'aws' ? 'bi-cloud-sun' : 'bi-flower1' }} me-1"></i>
                                                {{ $deviceTypes[$device->type] ?? $device->type }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sensors Info -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-thermometer-half me-1"></i> Sensors ({{ $device->sensors->count() }}
                                    sensor)
                                </label>
                                <div class="sensors-container">
                                    @if($device->sensors->count() > 0)
                                        @foreach($device->sensors as $sensor)
                                            <span class="badge-sensor">
                                                <span class="badge-sensor-name">{{ $sensor->sensor_label }}</span>
                                                <span class="badge-sensor-column">{{ $sensor->sensor_name }}</span>
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-white-50">Tidak ada sensor</span>
                                    @endif
                                </div>
                                <div class="alert alert-info-custom mt-2 mb-0 py-2">
                                    <small><i class="bi bi-info-circle me-1"></i>
                                        Sensor tidak dapat diubah karena sudah terikat dengan struktur tabel database.
                                    </small>
                                </div>
                            </div>

                            <!-- Outputs Info -->
                            <div class="mb-4">
                                <label class="form-label" style="color: #fde047;">
                                    <i class="bi bi-toggle-on me-1"></i> Outputs ({{ $device->outputs->count() }}
                                    output)
                                </label>
                                <div class="sensors-container">
                                    @if($device->outputs->count() > 0)
                                        @foreach($device->outputs as $output)
                                            <span class="badge-output">
                                                <span class="badge-output-name"><i
                                                        class="bi bi-toggle-on me-1"></i>{{ $output->output_label }}</span>
                                                <span class="badge-output-type">{{ $output->output_name }}
                                                    ({{ $output->output_type }})</span>
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-white-50">Tidak ada output</span>
                                    @endif
                                </div>
                                <div class="alert alert-info-custom mt-2 mb-0 py-2"
                                    style="background: rgba(250, 204, 21, 0.15); border-color: rgba(250, 204, 21, 0.3);">
                                    <small style="color: #fde047;"><i class="bi bi-info-circle me-1"></i>
                                        Output dapat dikontrol melalui MQTT topic:
                                        <code>{{ $device->mqtt_topic }}/control</code>
                                    </small>
                                </div>
                            </div>

                            <hr class="border-secondary my-4">

                            <!-- Editable Fields -->
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-tag me-1"></i> Nama Device</label>
                                <input type="text" name="name" class="form-control" value="{{ $device->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-geo-alt me-1"></i> Lokasi Alat</label>
                                <input type="text" name="location" id="locationInput" class="form-control" 
                                    value="{{ $device->location }}"
                                    placeholder="Contoh: Greenhouse A, Kebun Teh Blok 3">
                            </div>

                            <!-- Map Picker -->
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-map me-1"></i> Pilih Titik Lokasi di Map</label>
                                <div id="mapPicker" style="height: 250px; border-radius: 12px; border: 1px solid var(--glass-border);"></div>
                                <div class="form-text">Klik pada map untuk mengubah koordinat lokasi device.</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-geo me-1"></i> Latitude</label>
                                    <input type="number" step="any" name="latitude" id="latitudeInput" class="form-control"
                                        value="{{ $device->latitude }}" placeholder="-6.9175" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-geo me-1"></i> Longitude</label>
                                    <input type="number" step="any" name="longitude" id="longitudeInput" class="form-control"
                                        value="{{ $device->longitude }}" placeholder="107.6191" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label"><i class="bi bi-broadcast me-1"></i> MQTT Topic</label>
                                <input type="text" name="mqtt_topic" class="form-control"
                                    value="{{ $device->mqtt_topic }}" required>
                            </div>

                            <div class="d-flex gap-3 mt-4">
                                <a href="{{ route('admin.devices.index') }}" class="btn btn-glass">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-gradient flex-grow-1">
                                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS for Map Picker -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get existing coordinates or use default
            const existingLat = {{ $device->latitude ?? -6.9175 }};
            const existingLng = {{ $device->longitude ?? 107.6191 }};
            const hasExistingCoords = {{ ($device->latitude && $device->longitude) ? 'true' : 'false' }};
            
            const map = L.map('mapPicker').setView([existingLat, existingLng], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            
            let marker = null;
            
            // Add existing marker if coordinates exist
            if (hasExistingCoords) {
                marker = L.marker([existingLat, existingLng], { draggable: true }).addTo(map);
                marker.bindPopup('<b>📍 Lokasi Device</b><br>Lat: ' + existingLat + '<br>Lng: ' + existingLng);
                
                marker.on('dragend', function(event) {
                    const position = marker.getLatLng();
                    document.getElementById('latitudeInput').value = position.lat.toFixed(7);
                    document.getElementById('longitudeInput').value = position.lng.toFixed(7);
                });
            }
            
            map.on('click', function(e) {
                const lat = e.latlng.lat.toFixed(7);
                const lng = e.latlng.lng.toFixed(7);
                
                document.getElementById('latitudeInput').value = lat;
                document.getElementById('longitudeInput').value = lng;
                
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng, { draggable: true }).addTo(map);
                    marker.on('dragend', function(event) {
                        const position = marker.getLatLng();
                        document.getElementById('latitudeInput').value = position.lat.toFixed(7);
                        document.getElementById('longitudeInput').value = position.lng.toFixed(7);
                    });
                }
                
                marker.bindPopup('<b>📍 Lokasi Device</b><br>Lat: ' + lat + '<br>Lng: ' + lng).openPopup();
            });
            
            setTimeout(function() { map.invalidateSize(); }, 100);
        });
    </script>
</body>

</html>