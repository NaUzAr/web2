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

        .table-glass {
            color: #fff;
        }
        
        .table-glass th,
        .table-glass td {
            border-color: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            vertical-align: middle;
        }
        
        .table-glass thead th {
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .badge-sector {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal-content-glass {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .form-control-dark, .form-select-dark {
            background-color: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .form-control-dark:focus, .form-select-dark:focus {
            background-color: rgba(0, 0, 0, 0.4);
            border-color: #ef4444;
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(239, 68, 68, 0.25);
        }

        .schedule-day-check {
            display: none;
        }
        
        .schedule-day-label {
            display: inline-block;
            width: 36px;
            height: 36px;
            line-height: 34px;
            text-align: center;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            margin-right: 5px;
            font-size: 0.8rem;
            user-select: none;
            transition: all 0.2s;
        }
        
        .schedule-day-check:checked + .schedule-day-label {
            background-color: #ef4444;
            border-color: #ef4444;
            color: white;
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
                        {{ $scheduleConfig->output_key }}
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
                $isType = str_contains($mode, 'type');
            @endphp
            
            {{-- Remove Add Button, use Fixed Slots --}}
            
            <div class="table-responsive">
                <table class="table table-glass">
                    <thead>
                        <tr>
                            <th>Slot</th>
                            @if($isType) <th>Jenis</th> @endif
                            <th>Waktu Mulai</th>
                            @if($isDuration) 
                                <th>Durasi</th> 
                            @else
                                <th>Waktu Selesai</th>
                            @endif
                            @if($isSector) <th>Sektor</th> @endif
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
                                <td><span class="badge bg-secondary">Slot {{ $i }}</span></td>
                                
                                @if($isType)
                                    <td>
                                        @if($isActive)
                                            @if(($sch['name'] ?? '') == 'PUPUK')
                                                <span class="badge bg-warning text-dark">Pupuk</span>
                                            @elseif(($sch['name'] ?? '') == 'BAKU')
                                                <span class="badge bg-success">Air Baku</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $sch['name'] ?? '-' }}</span>
                                            @endif
                                        @else
                                            <span class="text-white-50">-</span>
                                        @endif
                                    </td>
                                @endif
                                
                                <td>{{ $isActive ? substr($sch['on_time'], 0, 5) : '-' }}</td>
                                
                                @if($isDuration) 
                                    <td>{{ $isActive ? $sch['duration'] . ' Menit' : '-' }}</td> 
                                @else
                                    <td>{{ $isActive ? ($sch['off_time'] ?? '-') : '-' }}</td>
                                @endif
                                
                                @if($isSector) 
                                    <td>
                                        @if($isActive)
                                            <span class="badge badge-sector">Sektor {{ $sch['sector'] }}</span>
                                        @else
                                            -
                                        @endif
                                    </td> 
                                @endif
                                
                                @if($isDays) 
                                    <td><small>{{ $isActive ? ($days ?: 'Setiap Hari') : '-' }}</small></td> 
                                @endif
                                
                                <td>
                                    @if($isActive)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Kosong</span>
                                    @endif
                                </td>
                                
                                <td>
                                    <button class="btn btn-sm btn-outline-light me-1" onclick='openScheduleModal({{ $i }}, @json($sch))'>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    @if($isActive)
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteSchedule({{ $i }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
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
                    
                    @if($isType)
                    <div class="mb-3">
                        <label class="form-label text-white-50">Jenis</label>
                        <select id="schedule_type" class="form-select form-select-dark">
                            <option value="BAKU">Air Baku</option>
                            <option value="PUPUK">Pupuk</option>
                        </select>
                    </div>
                    @endif
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label text-white-50">Waktu Mulai</label>
                            <input type="time" id="on_time" class="form-control form-control-dark">
                        </div>
                        <div class="col-6">
                            @if($isDuration)
                                <label class="form-label text-white-50">Durasi (Menit)</label>
                                <input type="number" id="duration" class="form-control form-control-dark" min="1" value="5">
                            @else
                                <label class="form-label text-white-50">Waktu Selesai</label>
                                <input type="time" id="off_time" class="form-control form-control-dark">
                            @endif
                        </div>
                    </div>
                    
                    @if($isSector)
                    <div class="mb-3 mt-3">
                        <label class="form-label text-white-50">Sektor</label>
                        <select id="sector" class="form-select form-select-dark">
                            @for($s = 1; $s <= ($scheduleConfig->max_sectors ?? 1); $s++)
                                <option value="{{ $s }}">Sektor {{ $s }}</option>
                            @endfor
                        </select>
                    </div>
                    @endif
                    
                    @if($isDays)
                    <div class="mb-3 mt-3">
                        <label class="form-label text-white-50 d-block">Hari Aktif</label>
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
                <div class="modal-footer border-top border-secondary justify-content-between">
                    <button type="button" class="btn btn-outline-danger d-none" id="btnDeleteModal" onclick="deleteScheduleFromModal()">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                    <div>
                        <button type="button" class="btn btn-link text-white-50 text-decoration-none" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="sendSchedule()">
                            <span id="btnText">Kirim ke Device</span>
                            <div id="btnLoading" class="spinner-border spinner-border-sm ms-2 d-none"></div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const storeUrl = '{{ route("schedule.time.store", [$userDevice->id]) }}';
        const deleteUrlBase = '{{ url("/device/" . $userDevice->id . "/schedule") }}';
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
            document.getElementById('modalTitle').innerText = `Edit Slot ${slotId}`;
            
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
            if(!confirm(`Yakin ingin menghapus jadwal Slot ${slotId}?`)) return;
            
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