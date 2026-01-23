<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $scheduleConfig->schedule_label }} - {{ $device->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #450a0a 0%, #7f1d1d 50%, #991b1b 100%);
            min-height: 100vh;
            padding: 2rem 0;
            color: #fff;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            text-decoration: none;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
        }

        .day-checkbox {
            display: inline-block;
            margin-right: 0.5rem;
        }

        .day-checkbox input[type="checkbox"] {
            display: none;
        }

        .day-checkbox label {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .day-checkbox input[type="checkbox"]:checked+label {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-color: #ef4444;
        }

        .schedule-slot {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ef4444;
            color: #fff;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }

        .form-select option {
            background: #991b1b;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3><i class="bi bi-calendar-check me-2"></i>{{ $scheduleConfig->schedule_label }}</h3>
                    <p class="text-white-50 mb-0">Device: {{ $device->name }} | Target:
                        {{ $scheduleConfig->output_key }} | Mode:
                        {{ ucfirst(str_replace('_', ' + ', $scheduleConfig->schedule_mode)) }}
                    </p>
                </div>
                <a href="{{ route('monitoring.show', $userDevice->id) }}" class="btn-glass">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            @php
                $mode = $scheduleConfig->schedule_mode;
                $isDuration = str_contains($mode, 'duration');
                $isDays = str_contains($mode, 'days');
                $isSector = str_contains($mode, 'sector');
            @endphp

            <div class="glass-card">
                <h5 class="mb-3">
                    <i class="bi bi-clock me-2"></i>Schedule Config (Max: {{ $scheduleConfig->max_slots }} slots)
                    @if($isSector) | Sectors: {{ $scheduleConfig->max_sectors }} @endif
                </h5>
                <div id="scheduleContainer">
                    @for($i = 0; $i < $scheduleConfig->max_slots; $i++)
                        <div class="schedule-slot glass-card mb-3" id="slot-{{ $i }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-lg-1">
                                    <strong class="text-white-50">Slot {{ $i + 1 }}</strong>
                                </div>
                                <div class="col-6 col-lg-2">
                                    <label class="form-label small">Start Time</label>
                                    <input type="time" id="on_time_{{ $i }}" class="form-control">
                                </div>
                                <div class="col-6 col-lg-2">
                                    @if($isDuration)
                                        <label class="form-label small">Duration (Min)</label>
                                        <input type="number" id="duration_{{ $i }}" class="form-control" placeholder="5"
                                            min="1">
                                    @else
                                        <label class="form-label small">End Time</label>
                                        <input type="time" id="off_time_{{ $i }}" class="form-control">
                                    @endif
                                </div>

                                @if($isDays)
                                    <div class="col-12 col-lg-4">
                                        <label class="form-label small">Days</label>
                                        <div class="d-flex flex-wrap">
                                            @foreach([1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min'] as $val => $label)
                                                <div class="day-checkbox">
                                                    <input type="checkbox" id="day_{{ $i }}_{{ $val }}" value="{{ $val }}" checked>
                                                    <label for="day_{{ $i }}_{{ $val }}">{{ $label }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($isSector)
                                    <div class="col-6 col-lg-1">
                                        <label class="form-label small">Sector</label>
                                        <select id="sector_{{ $i }}" class="form-select">
                                            @for($s = 1; $s <= ($scheduleConfig->max_sectors ?? 1); $s++)
                                                <option value="{{ $s }}">{{ $s }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                @endif

                                <div class="col-6 col-lg-1">
                                    <button type="button" class="btn btn-primary w-100" onclick="sendSchedule({{ $i }})">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </div>
                                <div class="col-12 col-lg-2">
                                    <span id="status_{{ $i }}" class="badge bg-secondary">Belum dikirim</span>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle me-1"></i> Klik tombol kirim pada setiap slot untuk set jadwal.
            </div>

            <script>
                const mode = '{{ $mode }}';
                const isDuration = {{ $isDuration ? 'true' : 'false' }};
                const isDays = {{ $isDays ? 'true' : 'false' }};
                const isSector = {{ $isSector ? 'true' : 'false' }};
                // Route URL for store (without output ID now)
                const storeUrl = '{{ route("schedule.time.store", [$userDevice->id]) }}';

                async function sendSchedule(slotIndex) {
                    const onTime = document.getElementById(`on_time_${slotIndex}`).value;
                    let offTime = null;
                    let duration = null;

                    if (isDuration) {
                        duration = document.getElementById(`duration_${slotIndex}`).value;
                    } else {
                        offTime = document.getElementById(`off_time_${slotIndex}`).value;
                    }

                    const statusBadge = document.getElementById(`status_${slotIndex}`);

                    // Validation
                    if (!onTime) { alert('Mohon isi Start Time!'); return; }
                    if (isDuration && !duration) { alert('Mohon isi Durasi!'); return; }
                    if (!isDuration && !offTime) { alert('Mohon isi End Time!'); return; }

                    // Get days if applicable
                    let days = '';
                    if (isDays) {
                        for (let d = 1; d <= 7; d++) {
                            const checkbox = document.getElementById(`day_${slotIndex}_${d}`);
                            if (checkbox && checkbox.checked) days += d;
                        }
                        if (!days) { alert('Pilih minimal satu hari!'); return; }
                    }

                    // Get sector if applicable
                    let sector = null;
                    if (isSector) {
                        const sectorSelect = document.getElementById(`sector_${slotIndex}`);
                        sector = sectorSelect ? sectorSelect.value : 1;
                    }

                    statusBadge.className = 'badge bg-warning';
                    statusBadge.textContent = 'Mengirim...';

                    try {
                        const payload = {
                            slot_id: slotIndex + 1,
                            on_time: onTime,
                            off_time: offTime, // can be null if duration used
                            duration: duration,
                            days: days || null,
                            sector: sector
                        };

                        const response = await fetch(storeUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();

                        if (data.success) {
                            statusBadge.className = 'badge bg-success';
                            statusBadge.textContent = 'Terkirim ✓';
                            // Optional: Show calculated OFF Time if returned
                            if (data.message) {
                                // could show toast or alert
                            }
                        } else {
                            statusBadge.className = 'badge bg-danger';
                            statusBadge.textContent = 'Gagal';
                            alert(data.message || 'Gagal mengirim jadwal');
                        }
                    } catch (error) {
                        statusBadge.className = 'badge bg-danger';
                        statusBadge.textContent = 'Error';
                        alert('Error: ' + error.message);
                    }
                }
            </script>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>