<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jadwal Otomatis - {{ $device->name }}</title>
    @include('partials.pwa-head')
    @include('partials.theme')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            color: var(--text-main);
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
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

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: #fff;
        }

        .table-glass {
            color: var(--text-main);
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        .table-glass th,
        .table-glass td {
            border-bottom: 1px dashed var(--glass-border);
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }
        
        .table-glass tbody tr {
            transition: all 0.2s ease;
        }

        .table-glass tbody tr:hover {
            background: rgba(14, 95, 138, 0.03);
        }
        
        .table-glass thead th {
            border-bottom: 2px solid var(--glass-border);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
        }

        .badge-sector {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
        }

        .modal-content-glass {
            background: rgba(255, 255, 255, 0.05); /* slightly whiter/lighter */
            backdrop-filter: blur(24px);
            border: 2px solid #ffffff; /* pure white frame/list */
            border-radius: 24px;
            color: var(--text-main);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.1);
            padding: 1.5rem;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border-top: 1px solid rgba(255,255,255,0.05);
            padding: 1.5rem;
        }
        
        .form-control-dark, .form-select-dark {
            background-color: rgba(0,0,0,0.2) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: var(--text-main) !important;
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }
        
        .form-control-dark:focus, .form-select-dark:focus {
            background-color: rgba(0,0,0,0.3) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(14, 95, 138, 0.25) !important;
        }

        .schedule-day-check {
            display: none;
        }
        
        .schedule-day-label {
            display: inline-block;
            width: 42px;
            height: 42px;
            line-height: 40px;
            text-align: center;
            border-radius: 12px;
            border: 1px solid var(--glass-border);
            background: rgba(255,255,255,0.05);
            cursor: pointer;
            margin-right: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            user-select: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .schedule-day-check:checked + .schedule-day-label {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(14, 95, 138, 0.4);
            transform: translateY(-2px);
        }

        /* ========= Mobile Responsive ========= */
        @media (max-width: 768px) {
            .glass-card {
                padding: 1.25rem;
                border-radius: 16px;
            }

            .glass-card h4 {
                font-size: 1.15rem;
            }

            /* Table → Card Layout */
            .table-glass thead {
                display: none;
            }

            .table-glass tbody tr {
                display: block;
                background: var(--glass-bg);
                border: 1px solid var(--glass-border);
                border-radius: 16px;
                padding: 1rem;
                margin-bottom: 1rem;
                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            }

            .table-glass tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.4rem 0.5rem;
                border: none;
                font-size: 0.9rem;
            }

            .table-glass tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                font-size: 0.8rem;
                color: var(--text-secondary);
                margin-right: 1rem;
                flex-shrink: 0;
            }

            .table-glass tbody td:last-child {
                justify-content: flex-end;
                padding-top: 0.5rem;
                border-top: 1px solid var(--glass-border);
                margin-top: 0.25rem;
            }

            /* Day selector bigger for touch */
            .schedule-day-label {
                width: 44px;
                height: 44px;
                line-height: 42px;
                font-size: 0.85rem;
                margin-right: 4px;
                margin-bottom: 4px;
            }

            /* Modal form touch-friendly */
            .form-control-dark, .form-select-dark {
                font-size: 16px;
                min-height: 50px;
            }

            /* Alert compact */
            .alert {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 400px) {
            .glass-card {
                padding: 1rem;
            }

            .schedule-day-label {
                width: 38px;
                height: 38px;
                line-height: 36px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Swaratani" height="40" class="me-2">
                <span class="fw-bold" style="color: var(--navbar_text, #333);">Swaratani IoT</span>
            </a>
            <div class="navbar-nav ms-auto flex-row align-items-center gap-4 gap-sm-3">
                <a class="nav-link px-0 text-decoration-none" href="{{ route('monitoring.show', $userDevice->id) }}" title="Kembali ke Device" style="color: var(--navbar-text);">
                    <i class="bi bi-arrow-left fs-5 me-2 me-sm-1" style="-webkit-text-stroke: 1px currentColor;"></i>
                    <i class="bi bi-display fs-5 me-sm-1"></i><span class="d-none d-sm-inline"> Tampilan Device</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1"><i class="bi bi-calendar-check me-2"></i>Jadwal Otomatis</h4>
                    <p class="mb-0 small" style="color: var(--text-secondary);">Device: {{ $device->name }} | Target:
                        {{ $scheduleConfig->output_key }}
                    </p>
                </div>
            </div>

            @php
                $mode = $scheduleConfig->schedule_mode;
                $isDuration = str_contains($mode, 'duration');
                $isDays = str_contains($mode, 'days');
                $isSector = str_contains($mode, 'sector');
                $isType = str_contains($mode, 'type');
            @endphp
            
            {{-- Remove Add Button, use Fixed Slots --}}
            
            <div class="table-responsive">
                <table class="table table-glass">
                    <thead>
                        <tr>
                            <th>Jadwal</th>
                            <th>Waktu Mulai</th>
                            @if($isDuration) 
                                <th>Durasi</th> 
                            @else
                                <th>Waktu Selesai</th>
                            @endif
                            @if($isSector) <th>Output</th> @endif
                            @if($isType) <th>Input</th> @endif
                            @if($isDays) <th>Hari</th> @endif
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 1; $i <= ($scheduleConfig->max_slots ?? 14); $i++)
                            @php 
                                $key = "sch{$i}";
                                $sch = $cachedSchedules[$key] ?? null;
                                $isActive = $sch && ($sch['is_active'] ?? false);
                                
                                // Format days if exists
                                $days = '-';
                                if ($isActive && !empty($sch['days'])) {
                                    $days = is_array($sch['days']) ? implode(', ', $sch['days']) : $sch['days'];
                                }
                            @endphp
                            <tr id="row-slot-{{ $i }}">
                                <td data-label="Jadwal">
                                    <span class="badge rounded-pill" style="background: rgba(14, 95, 138, 0.1); color: var(--primary); border: 1px solid rgba(14, 95, 138, 0.2); padding: 6px 12px; font-weight: 700;">
                                        Jadwal {{ $i }}
                                    </span>
                                </td>
                                
                                <td data-label="Waktu Mulai" class="fw-bold">{{ $isActive ? substr($sch['on_time'], 0, 5) : '-' }}</td>
                                
                                @if($isDuration) 
                                    <td data-label="Durasi">{{ $isActive ? $sch['duration'] . ' Menit' : '-' }}</td> 
                                @else
                                    <td data-label="Waktu Selesai" class="fw-bold">{{ $isActive ? ($sch['off_time'] ?? '-') : '-' }}</td>
                                @endif
                                
                                @if($isSector) 
                                    <td data-label="Output">
                                        @if($isActive)
                                            <span class="badge rounded-pill" style="background: rgba(14, 95, 138, 0.05); color: var(--text-main); border: 1px solid rgba(14, 95, 138, 0.2);">
                                                <i class="bi bi-outlet me-1" style="color: var(--primary);"></i> Output {{ $sch['sector'] }}
                                            </span>
                                        @else
                                            <span style="color: var(--text-secondary);">-</span>
                                        @endif
                                    </td> 
                                @endif

                                @if($isType)
                                    <td data-label="Input">
                                        @if($isActive)
                                            @if(($sch['name'] ?? '') == 'PUPUK')
                                                <span class="badge rounded-pill" style="background: rgba(234, 179, 8, 0.1); color: #ca8a04; border: 1px solid rgba(234, 179, 8, 0.2);"><i class="bi bi-droplet-half me-1"></i>Air Pupuk</span>
                                            @elseif(($sch['name'] ?? '') == 'BAKU')
                                                <span class="badge rounded-pill" style="background: rgba(34, 197, 94, 0.1); color: #16a34a; border: 1px solid rgba(34, 197, 94, 0.2);"><i class="bi bi-water me-1"></i>Air Baku</span>
                                            @else
                                                <span class="badge rounded-pill bg-secondary">{{ $sch['name'] ?? '-' }}</span>
                                            @endif
                                        @else
                                            <span style="color: var(--text-secondary);">-</span>
                                        @endif
                                    </td>
                                @endif
                                
                                @if($isDays) 
                                    <td data-label="Hari" style="color: var(--text-secondary);">{{ $isActive ? ($days ?: 'Setiap Hari') : '-' }}</td> 
                                @endif
                                
                                <td data-label="Status">
                                    @if($isActive)
                                        <span class="badge rounded-pill shadow-sm" style="background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3); padding: 6px 12px;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge rounded-pill" style="background: rgba(107, 114, 128, 0.1); color: var(--text-secondary); border: 1px solid rgba(107, 114, 128, 0.2); padding: 6px 12px;">
                                            Kosong
                                        </span>
                                    @endif
                                </td>
                                
                                <td data-label="">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm" style="background: transparent; border: 1px solid #ffffff; color: #ffffff; border-radius: 50px; padding: 6px 14px; font-weight: 500;" onclick='openScheduleModal({{ $i }}, @json($sch))' title="Edit Jadwal">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </button>
                                        @if($isActive)
                                            <button class="btn btn-sm" style="background: transparent; border: 1px solid #ffffff; color: #ffffff; border-radius: 50px; padding: 6px 12px;" onclick="deleteSchedule({{ $i }})" title="Hapus Jadwal">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-info mt-3 d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                <div>
                    Data di atas adalah sinkronisasi terakhir dari device. 
                    <br>Jika Anda mengirim jadwal baru atau menghapus, data akan terupdate setelah device merespons.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-glass">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title" id="modalTitle">Set Jadwal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="slot_id">
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label" style="color: var(--text-secondary);">Waktu Mulai</label>
                            <input type="time" id="on_time" class="form-control form-control-dark">
                        </div>
                        <div class="col-6">
                            @if($isDuration)
                                <label class="form-label" style="color: var(--text-secondary);">Durasi (Menit)</label>
                                <input type="number" id="duration" class="form-control form-control-dark" min="1" value="5">
                            @else
                                <label class="form-label" style="color: var(--text-secondary);">Waktu Selesai</label>
                                <input type="time" id="off_time" class="form-control form-control-dark">
                            @endif
                        </div>
                    </div>
                    
                    @if($isSector)
                    <div class="mb-3 mt-3">
                        <label class="form-label" style="color: var(--text-secondary);">Output</label>
                        <select id="sector" class="form-select form-select-dark">
                            @for($s = 1; $s <= ($scheduleConfig->max_sectors ?? 1); $s++)
                                <option value="{{ $s }}">Output {{ $s }}</option>
                            @endfor
                        </select>
                    </div>
                    @endif
                    
                    @if($isType)
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--text-secondary);">Input</label>
                        <select id="schedule_type" class="form-select form-select-dark">
                            <option value="BAKU">Air Baku</option>
                            <option value="PUPUK">Air Pupuk</option>
                        </select>
                    </div>
                    @endif
                    
                    @if($isDays)
                    <div class="mb-3 mt-3">
                        <label class="form-label d-block" style="color: var(--text-secondary);">Hari Aktif</label>
                        <div class="d-flex flex-wrap">
                            @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $key => $day)
                                <div class="me-2 mb-2">
                                    <input type="checkbox" id="day_{{ $key }}" class="schedule-day-check" value="{{ $key + 1 }}"> <!-- Device usually 1=Sun or 1=Mon, need to confirm. Based on MqttService convertDaysToBinary map: 1=Mon...7=Sun. View labels: Min(0?), Sen(1?).. -->
                                    <!-- Let's map View labels to standard 1=Mon..7=Sun or just correct the loop values. -->
                                    <!-- MqttService expects "12345" where 1=Mon. View array was Min(0),Sen(1).. -->
                                    <!-- Let's fix days: 1=Mon, 2=Tue, ..., 7=Sun. Min in array should be 7 -->
                                    @php 
                                        $val = ($day == 'Min') ? 7 : ($key); // Standardize to 1=Mon...7=Sun
                                        if($day == 'Sen') $val = 1;
                                        if($day == 'Sel') $val = 2;
                                        if($day == 'Rab') $val = 3;
                                        if($day == 'Kam') $val = 4;
                                        if($day == 'Jum') $val = 5;
                                        if($day == 'Sab') $val = 6;
                                    @endphp
                                    <label for="day_{{ $key }}" class="schedule-day-label">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn border-0 text-danger px-3 py-2 rounded-pill d-none" style="background: rgba(239,68,68,0.1);" id="btnDeleteModal" onclick="deleteScheduleFromModal()">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-link text-decoration-none" style="color: var(--text-secondary);" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary rounded-pill px-4 py-2" onclick="sendSchedule()">
                            <span id="btnText">Kirim Jadwal</span>
                            <div id="btnLoading" class="spinner-border spinner-border-sm ms-2 d-none"></div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const storeUrl = '{{ route("schedule.time.store", [$userDevice->id], false) }}';
        const deleteUrlBase = '/device/{{ $userDevice->id }}/schedule';
        const csrfToken = '{{ csrf_token() }}';
        
        // PHP configs to JS
        const isDuration = {{ $isDuration ? 'true' : 'false' }};
        const isDays = {{ $isDays ? 'true' : 'false' }};
        const isSector = {{ $isSector ? 'true' : 'false' }};
        const isType = {{ $isType ? 'true' : 'false' }};
        const maxSlots = {{ $scheduleConfig->max_slots ?? 14 }};

        const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));

        function openScheduleModal(slotId, data = null) {
            document.getElementById('slot_id').value = slotId;
            document.getElementById('modalTitle').innerText = `Edit Jadwal ${slotId}`;
            
            const btnDelete = document.getElementById('btnDeleteModal');
            
            // Default values
            document.getElementById('on_time').value = '';
            if(isDuration) document.getElementById('duration').value = 5;
            else document.getElementById('off_time').value = '';
            
            if(isSector) document.getElementById('sector').value = 1;
            if(isType) document.getElementById('schedule_type').value = 'BAKU';
            
            if(isDays) {
                document.querySelectorAll('.schedule-day-check').forEach(el => el.checked = false);
            }
            
            // Fill data if editing existing schedule
            if (data && data.is_active) {
                btnDelete.classList.remove('d-none');
                
                document.getElementById('on_time').value = data.on_time ? data.on_time.substring(0, 5) : '';
                
                if(isDuration) document.getElementById('duration').value = data.duration || 5;
                else document.getElementById('off_time').value = data.off_time ? data.off_time.substring(0, 5) : '';
                
                if(isSector) document.getElementById('sector').value = data.sector || 1;
                if(isType) document.getElementById('schedule_type').value = data.name || 'BAKU'; 
                
                if(isDays && data.days) {
                    let daysArr = Array.isArray(data.days) ? data.days : (data.days ? data.days.split(',') : []);
                    let map = {'Sen':1, 'Sel':2, 'Rab':3, 'Kam':4, 'Jum':5, 'Sab':6, 'Min':7};
                    daysArr.forEach(d => {
                        let dt = d.trim();
                        if(map[dt]) {
                            let el = document.querySelector(`.schedule-day-check[value="${map[dt]}"]`);
                            if(el) el.checked = true;
                        }
                    });
                }
            } else {
                btnDelete.classList.add('d-none');
            }
            
            modal.show();
        }

        async function deleteSchedule(slotId) {
            if(!confirm(`Yakin ingin menghapus Jadwal ${slotId}?`)) return;
            
            try {
                const res = await fetch(`${deleteUrlBase}/${slotId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await res.json();
                
                if(data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal: ' + data.message);
                }
            } catch (e) {
                alert('Error: ' + e.message);
            }
        }

        function deleteScheduleFromModal() {
            const slotId = document.getElementById('slot_id').value;
            deleteSchedule(slotId);
            modal.hide();
        }

        async function sendSchedule() {
            const slotId = parseInt(document.getElementById('slot_id').value);
            const onTime = document.getElementById('on_time').value;
            
            if(!onTime) { alert('Waktu Mulai harus diisi'); return; }

            let payload = {
                slot_id: slotId,
                on_time: onTime,
                _token: csrfToken
            };

            // Slot ID is always fixed now
            
            if(isDuration) payload.duration = document.getElementById('duration').value;
            else payload.off_time = document.getElementById('off_time').value;

            if(isSector) payload.sector = document.getElementById('sector').value;
            if(isType) payload.schedule_type = document.getElementById('schedule_type').value;

            if(isDays) {
                let days = [];
                document.querySelectorAll('.schedule-day-check:checked').forEach(el => days.push(el.value));
                if(days.length === 0) { alert('Pilih minimal 1 hari'); return; }
                payload.days = days.join('');
            }

            // UX
            const btn = document.querySelector('.modal-footer .btn-primary');
            const btnText = document.getElementById('btnText');
            const loader = document.getElementById('btnLoading');
            
            btn.disabled = true;
            btnText.innerText = 'Mengirim...';
            loader.classList.remove('d-none');

            try {
                const res = await fetch(storeUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                const data = await res.json();
                
                if(data.success) {
                    alert(data.message);
                    modal.hide();
                    location.reload(); 
                } else {
                    alert('Gagal: ' + data.message);
                }
            } catch (e) {
                alert('Error: ' + e.message);
            } finally {
                btn.disabled = false;
                btnText.innerText = 'Kirim ke Device';
                loader.classList.add('d-none');
            }
        }
    </script>
</body>
</html>