<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Device - Smart Agriculture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary-red: #ef4444;
            --dark-red: #991b1b;
            --light-red: #fca5a5;
            --accent-orange: #f97316;
            --light-orange: #fdba74;
            --primary-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #f97316 100%);
            --secondary-gradient: linear-gradient(135deg, #fca5a5 0%, #ef4444 100%);
            --nature-gradient: linear-gradient(135deg, #450a0a 0%, #7f1d1d 50%, #991b1b 100%);
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
            background: radial-gradient(circle at 20% 80%, rgba(239, 68, 68, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(249, 115, 22, 0.2) 0%, transparent 50%);
        }

        .navbar-glass {
            background: rgba(127, 29, 29, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
        }

        .navbar-brand {
            font-weight: 700;
            color: #fca5a5 !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .nav-link:hover {
            color: #fca5a5 !important;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
        }

        .card-header-gradient {
            background: var(--primary-gradient);
            border-radius: 24px 24px 0 0 !important;
            padding: 1.5rem 2rem;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            color: #fff;
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ef4444;
            color: #fff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-select option {
            background: #991b1b;
            color: #fff;
        }

        .form-label {
            color: #fca5a5;
            font-weight: 600;
        }

        .form-text {
            color: rgba(255, 255, 255, 0.6);
        }

        .type-card {
            cursor: pointer;
            background: var(--glass-bg);
            border: 2px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            color: #fff;
        }

        .type-card:hover,
        .type-card.selected {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.2);
            transform: translateY(-5px);
        }

        .type-card i {
            font-size: 2.5rem;
            color: #fca5a5;
        }

        .type-card h6 {
            margin-top: 0.5rem;
            margin-bottom: 0;
        }

        .sensor-row {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 1px solid var(--glass-border);
            transition: all 0.2s ease;
        }

        .sensor-row:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: #ef4444;
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
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
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

        .btn-outline-add {
            background: transparent;
            border: 2px dashed #ef4444;
            color: #fca5a5;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-add:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #fff;
            border-style: solid;
        }

        .alert-info-custom {
            background: rgba(14, 165, 233, 0.2);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: var(--light-sky);
            border-radius: 12px;
        }

        .alert-warning-custom {
            background: rgba(250, 204, 21, 0.2);
            border: 1px solid rgba(250, 204, 21, 0.3);
            color: #fef08a;
            border-radius: 12px;
        }

        .badge-count {
            background: var(--secondary-gradient);
            color: #991b1b;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
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
            <div class="col-lg-10">
                <div class="glass-card shadow-lg">
                    <div class="card-header-gradient">
                        <h4 class="mb-0 text-white">
                            <i class="bi bi-plus-circle-fill me-2"></i>Tambah Device Baru
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger bg-danger bg-opacity-25 border-danger text-white">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger bg-danger bg-opacity-25 border-danger text-white">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.device.store') }}" method="POST" id="deviceForm">
                            @csrf

                            <!-- STEP 1: TIPE ALAT -->
                            <div class="mb-4">
                                <label class="form-label"><i class="bi bi-cpu me-1"></i> Pilih Tipe Alat</label>
                                <div class="row g-3">
                                    @foreach($deviceTypes as $typeKey => $typeLabel)
                                        <div class="col-md-6">
                                            <div class="type-card" data-type="{{ $typeKey }}"
                                                onclick="selectDeviceType('{{ $typeKey }}')">
                                                <i
                                                    class="bi {{ $typeKey === 'aws' ? 'bi-cloud-sun-fill' : 'bi-flower1' }}"></i>
                                                <h6>{{ $typeLabel }}</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="type" id="deviceType" value="" required>
                            </div>

                            <!-- STEP 2: INFO DEVICE -->
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-tag me-1"></i> Nama Device</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Sensor GH-01"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-geo-alt me-1"></i> Lokasi Alat</label>
                                <input type="text" name="location" id="locationInput" class="form-control"
                                    placeholder="Contoh: Greenhouse A, Kebun Teh Blok 3">
                            </div>

                            <!-- Map Picker -->
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-map me-1"></i> Pilih Titik Lokasi di
                                    Map</label>
                                <div id="mapPicker"
                                    style="height: 250px; border-radius: 12px; border: 1px solid var(--glass-border);">
                                </div>
                                <div class="form-text">Klik pada map untuk menentukan koordinat lokasi device.</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-geo me-1"></i> Latitude</label>
                                    <input type="number" step="any" name="latitude" id="latitudeInput"
                                        class="form-control" placeholder="-6.9175" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-geo me-1"></i> Longitude</label>
                                    <input type="number" step="any" name="longitude" id="longitudeInput"
                                        class="form-control" placeholder="107.6191" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label"><i class="bi bi-broadcast me-1"></i> Alamat Topik MQTT</label>
                                <input type="text" name="mqtt_topic" class="form-control"
                                    placeholder="Contoh: sensor/kebun/data" required>
                                <div class="form-text">Device akan mengirim data ke topik ini.</div>
                            </div>

                            <!-- STEP 3: DAFTAR SENSOR -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-thermometer-half me-1"></i> Konfigurasi Sensor
                                    <span class="badge-count ms-2" id="sensorCount">0 sensor</span>
                                </label>
                                
                                <!-- Quick Add Sensors -->
                                <div class="mb-3">
                                    <label class="small text-white-50 mb-2 d-block">Quick Add (Klik untuk menambahkan):</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($availableSensors as $key => $sensor)
                                            <button type="button" class="btn btn-sm btn-outline-light bg-opacity-10" 
                                                    onclick="addSensorRow('{{ $key }}')"
                                                    style="border-color: var(--glass-border); background: rgba(255,255,255,0.05);">
                                                <i class="bi {{ $sensor['icon'] }} me-1"></i> {{ $sensor['label'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="alert alert-info-custom py-2 mb-3">
                                    <small><i class="bi bi-info-circle me-1"></i>
                                        Tambahkan sensor sesuai kebutuhan. Bisa menambah sensor dengan jenis yang sama.
                                    </small>
                                </div>

                                <div id="sensorContainer"></div>

                                <button type="button" class="btn btn-outline-add w-100" onclick="addSensorRow()">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Sensor Manual
                                </button>
                            </div>

                            <!-- STEP 3.5: KONFIGURASI OTOMASI (GRANULAR) -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-robot me-1"></i> Konfigurasi Otomasi (Opsional)
                                </label>
                                <div class="alert alert-info-custom py-2 mb-3">
                                    <small><i class="bi bi-info-circle me-1"></i>
                                        Pilih sensor dan output yang ingin ditambahkan untuk paket otomasi ini (Klik tombol).
                                    </small>
                                </div>
                                
                                <div class="row g-3">
                                    @foreach($automationPresets as $key => $preset)
                                        <div class="col-md-6">
                                            <div class="glass-card p-3 h-100 border-0" style="background: rgba(255,255,255,0.05);">
                                                <h6 class="text-white fw-bold mb-2">
                                                    <i class="bi {{ $preset['icon'] }} me-1"></i> {{ $preset['label'] }}
                                                </h6>
                                                <p class="small text-white-50 mb-3">{{ $preset['description'] }}</p>

                                                <!-- Sensors Group -->
                                                <div class="mb-2">
                                                    <strong class="d-block small text-white-50 mb-1">Rekomendasi Sensor:</strong>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($preset['sensors'] as $sensorKey => $qty)
                                                            @if(isset($availableSensors[$sensorKey]))
                                                                <button type="button" class="btn btn-sm btn-outline-info bg-opacity-10" 
                                                                        onclick="addSensorRow('{{ $sensorKey }}')"
                                                                        title="Tambah Sensor {{ $availableSensors[$sensorKey]['label'] }}">
                                                                    <i class="bi {{ $availableSensors[$sensorKey]['icon'] }}"></i> {{ $availableSensors[$sensorKey]['label'] }}
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Outputs Group -->
                                                <div>
                                                    <strong class="d-block small text-white-50 mb-1">Rekomendasi Output:</strong>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($preset['outputs'] as $outputKey => $qty)
                                                            @if(isset($availableOutputs[$outputKey]))
                                                                <button type="button" class="btn btn-sm btn-outline-warning bg-opacity-10" 
                                                                        onclick="addOutputRow('{{ $outputKey }}')"
                                                                        title="Tambah Output {{ $availableOutputs[$outputKey]['label'] }}">
                                                                    <i class="bi {{ $availableOutputs[$outputKey]['icon'] }}"></i> {{ $availableOutputs[$outputKey]['label'] }}
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- STEP 4: DAFTAR OUTPUT -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-toggle-on me-1"></i> Konfigurasi Output (Opsional)
                                    <span class="badge-count ms-2" id="outputCount">0 output</span>
                                </label>
                                
                                 <!-- Quick Add Outputs -->
                                <div class="mb-3">
                                    <label class="small text-white-50 mb-2 d-block">Quick Add (Klik untuk menambahkan):</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($availableOutputs as $key => $output)
                                            <!-- Filter out automation specific outputs to avoid clutter if too many, or show all -->
                                            <!-- Showing all for now as per "make output like that too" -->
                                            <button type="button" class="btn btn-sm btn-outline-light bg-opacity-10" 
                                                    onclick="addOutputRow('{{ $key }}')"
                                                    style="border-color: var(--glass-border); background: rgba(255,255,255,0.05);">
                                                <i class="bi {{ $output['icon'] }} me-1"></i> {{ $output['label'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="alert alert-info-custom py-2 mb-3">
                                    <small><i class="bi bi-info-circle me-1"></i>
                                        Output adalah aktuator yang dapat dikontrol via MQTT (relay, pompa, kipas, dll).
                                    </small>
                                </div>

                                <div id="outputContainer"></div>

                                <button type="button" class="btn btn-outline-add w-100 mt-3" onclick="addOutputRow()">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Output
                                </button>

                            </div>

                            <!-- STEP 5: TIPE PENJADWALAN (OPTIONAL) -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-clock me-1"></i> Tipe Penjadwalan (Opsional)
                                </label>
                                <div class="alert alert-info-custom py-2 mb-3">
                                    <small><i class="bi bi-info-circle me-1"></i>
                                        Pilih tipe penjadwalan jika device ini membutuhkan fitur jadwal otomatis.
                                        Kosongkan jika tidak perlu.
                                    </small>
                                </div>

                                <select class="form-select mb-3" name="schedule_type" id="scheduleType"
                                    onchange="toggleScheduleOptions(this.value)">
                                    <option value="">-- Tidak Ada Penjadwalan --</option>
                                    @foreach($scheduleTypes as $key => $info)
                                        <option value="{{ $key }}">{{ $info['label'] }} - {{ $info['description'] }}
                                        </option>
                                    @endforeach
                                </select>

                                <div id="scheduleOptions" style="display: none;">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small text-white-50">Jumlah Slot Jadwal</label>
                                            <input type="number" class="form-control" name="max_slots" value="8" min="1"
                                                max="20">
                                            <div class="form-text">Berapa banyak jadwal yang bisa disimpan</div>
                                        </div>
                                        <div class="col-md-6" id="sectorField" style="display: none;">
                                            <label class="form-label small text-white-50">Jumlah Sektor</label>
                                            <input type="number" class="form-control" name="max_sectors" value="1"
                                                min="1" max="10">
                                            <div class="form-text">Untuk mode multi-sektor/zona</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning-custom">
                                <strong><i class="bi bi-exclamation-triangle me-1"></i> Perhatian:</strong>
                                Sistem akan otomatis membuatkan <b>Token Unik</b> dan <b>Tabel Database Baru</b>.
                            </div>

                            <div class="d-flex gap-3 mt-4">
                                <a href="{{ route('admin.devices.index') }}" class="btn btn-glass">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-gradient flex-grow-1" id="submitBtn" disabled>
                                    <i class="bi bi-plus-circle me-1"></i> Generate Device & Tabel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const availableSensors = @json($availableSensors);
        const availableOutputs = @json($availableOutputs);
        const defaultSensors = @json($defaultSensors);
        const defaultOutputs = @json($defaultOutputs);
        const automationPresets = @json($automationPresets);
        let sensorCounter = 0;
        let outputCounter = 0;

        // Toggle schedule options visibility
        function toggleScheduleOptions(value) {
            const scheduleOptions = document.getElementById('scheduleOptions');
            const sectorField = document.getElementById('sectorField');

            if (value) {
                scheduleOptions.style.display = 'block';
                // Show sector field for any mode containing 'sector'
                if (value.includes('sector')) {
                    sectorField.style.display = 'block';
                } else {
                    sectorField.style.display = 'none';
                }
            } else {
                scheduleOptions.style.display = 'none';
            }
        }

        function getSensorOptions(selectedKey = '') {
            let options = '<option value="">-- Pilih Jenis Sensor --</option>';
            for (const [key, info] of Object.entries(availableSensors)) {
                const selected = key === selectedKey ? 'selected' : '';
                options += `<option value="${key}" ${selected}>${info.label} ${info.unit ? '(' + info.unit + ')' : ''}</option>`;
            }
            return options;
        }

        function getOutputOptions(selectedKey = '') {
            let options = '<option value="">-- Pilih Jenis Output --</option>';
            for (const [key, info] of Object.entries(availableOutputs)) {
                const selected = key === selectedKey ? 'selected' : '';
                const typeLabel = info.type === 'boolean' ? 'ON/OFF' : (info.type === 'percentage' ? '0-100%' : 'Angka');
                options += `<option value="${key}" ${selected}>${info.label} (${typeLabel})</option>`;
            }
            return options;
        }

        function addSensorRow(sensorKey = '', customLabel = '') {
            sensorCounter++;
            const container = document.getElementById('sensorContainer');
            const row = document.createElement('div');
            row.className = 'sensor-row';
            row.id = `sensorRow_${sensorCounter}`;
            row.innerHTML = `
            <div class="row align-items-center g-2">
                <div class="col-md-5">
                    <select class="form-select sensor-select" name="sensors[${sensorCounter}][type]" required onchange="updateSubmitButton()">
                        ${getSensorOptions(sensorKey)}
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control sensor-label-input" name="sensors[${sensorCounter}][label]" 
                           placeholder="Label custom (opsional)" value="${customLabel}">
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSensorRow(${sensorCounter})" style="border-radius: 50%;">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
            container.appendChild(row);
            updateSensorCount();
            container.appendChild(row);
            updateSensorCount();
            updateSubmitButton();
        }

        function addOutputRow(outputKey = '', customLabel = '') {
            outputCounter++;
            const container = document.getElementById('outputContainer');
            const row = document.createElement('div');
            row.className = 'sensor-row output-row';
            row.id = `outputRow_${outputCounter}`;

            row.innerHTML = `
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <label class="form-label small text-white-50">Output Type</label>
                    <select class="form-select output-select" name="outputs[${outputCounter}][type]" onchange="updateSubmitButton()">
                        ${getOutputOptions(outputKey)}
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small text-white-50">Label (opsional)</label>
                    <input type="text" class="form-control output-label-input" name="outputs[${outputCounter}][label]" 
                           placeholder="Label custom" value="${customLabel}">
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="removeOutputRow(${outputCounter})" style="border-radius: 12px;">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
            container.appendChild(row);
            updateOutputCount();
        }

        function removeSensorRow(id) {
            const row = document.getElementById(`sensorRow_${id}`);
            if (row) {
                row.remove();
                updateSensorCount();
                updateSubmitButton();
            }
        }

        function removeOutputRow(id) {
            const row = document.getElementById(`outputRow_${id}`);
            if (row) { row.remove(); updateOutputCount(); }
        }

        function updateSensorCount() {
            const count = document.querySelectorAll('.sensor-row:not(.output-row):not(.schedule-row)').length;
            document.getElementById('sensorCount').textContent = count + ' sensor';
        }

        function updateOutputCount() {
            const count = document.querySelectorAll('.output-row').length;
            document.getElementById('outputCount').textContent = count + ' output';
        }

        function updateScheduleCount() {
            // Deprecated - kept for safety if referenced but simplified not to fail
        }

        // Schedule Functions - Removed legacy code

        function updateSubmitButton() {
            const typeSelected = document.getElementById('deviceType').value !== '';
            const sensorCount = document.querySelectorAll('.sensor-row:not(.output-row)').length;
            const allSensorsSelected = Array.from(document.querySelectorAll('.sensor-select')).every(s => s.value !== '');
            document.getElementById('submitBtn').disabled = !(typeSelected && sensorCount > 0 && allSensorsSelected);
        }

        function selectDeviceType(type) {
            document.querySelectorAll('.type-card').forEach(card => card.classList.remove('selected'));
            document.querySelector(`[data-type="${type}"]`).classList.add('selected');
            document.getElementById('deviceType').value = type;

            // Reset sensors
            document.getElementById('sensorContainer').innerHTML = '';
            sensorCounter = 0;

            // Reset outputs
            document.getElementById('outputContainer').innerHTML = '';
            outputCounter = 0;

            // Reset schedule dropdown
            document.getElementById('scheduleType').value = '';
            toggleScheduleOptions('');

            // Add default sensors
            if (defaultSensors[type]) {
                for (const [sensorKey, count] of Object.entries(defaultSensors[type])) {
                    for (let i = 0; i < count; i++) {
                        const label = count > 1 ? `${availableSensors[sensorKey].label} ${i + 1}` : '';
                        addSensorRow(sensorKey, label);
                    }
                }
            }

            // Uncheck automation switches when changing type
            document.querySelectorAll('input[type="checkbox"][id^="auto_"]').forEach(el => el.checked = false);

            // Add default outputs
            if (defaultOutputs[type]) {
                for (const [outputKey, count] of Object.entries(defaultOutputs[type])) {
                    for (let i = 0; i < count; i++) {
                        const label = count > 1 ? `${availableOutputs[outputKey].label} ${i + 1}` : '';
                        addOutputRow(outputKey, label);
                    }
                }
            }

            updateSubmitButton();
        }

        document.getElementById('deviceForm').addEventListener('submit', function (e) {
            const type = document.getElementById('deviceType').value;
            const sensors = document.querySelectorAll('.sensor-row:not(.output-row)').length;
            if (!type) { e.preventDefault(); alert('Pilih tipe alat terlebih dahulu!'); return false; }
            if (sensors === 0) { e.preventDefault(); alert('Tambahkan minimal 1 sensor!'); return false; }
        });
    </script>


    <!-- Leaflet JS for Map Picker -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize Map Picker
        document.addEventListener('DOMContentLoaded', function () {
            // Default center: Indonesia
            const defaultLat = -6.9175;
            const defaultLng = 107.6191;

            const map = L.map('mapPicker').setView([defaultLat, defaultLng], 13);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            let marker = null;

            // Click event to place marker
            map.on('click', function (e) {
                const lat = e.latlng.lat.toFixed(7);
                const lng = e.latlng.lng.toFixed(7);

                // Update input fields
                document.getElementById('latitudeInput').value = lat;
                document.getElementById('longitudeInput').value = lng;

                // Add or move marker
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng, {
                        draggable: true
                    }).addTo(map);

                    // Drag event for marker
                    marker.on('dragend', function (event) {
                        const position = marker.getLatLng();
                        document.getElementById('latitudeInput').value = position.lat.toFixed(7);
                        document.getElementById('longitudeInput').value = position.lng.toFixed(7);
                    });
                }

                marker.bindPopup('<b>📍 Lokasi Device</b><br>Lat: ' + lat + '<br>Lng: ' + lng).openPopup();
            });

            // Fix map display issue when in tabs/hidden containers
            setTimeout(function () {
                map.invalidateSize();
            }, 100);
        });
    </script>
</body>

</html>