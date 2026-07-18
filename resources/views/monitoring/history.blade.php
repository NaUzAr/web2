@extends(isset($isAdminView) && $isAdminView ? 'admin.layouts.app' : 'layouts.app')

@section('title', 'Riwayat Data - ' . $device->name)

@section('content')
<div class="container py-4">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div class="d-flex align-items-center">
            <a href="{{ isset($isAdminView) && $isAdminView ? route('admin.device.monitoring', $device->id) : route('monitoring.show', $device->id) }}" class="btn btn-glass me-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div>
                <h2 class="mb-0 text-white fw-bold d-flex align-items-center">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Data
                </h2>
                <p class="text-white-50 mb-0 mt-1">
                    Device: <strong>{{ $device->name }}</strong>
                </p>
            </div>
        </div>
    </div>

    @if($logData->count() > 0 || request()->has('start_date'))
        
        <!-- Grafik Data -->
        <div class="glass-card mb-4">
            <h5 class="card-title mb-4"><i class="bi bi-graph-up me-2"></i>Grafik Sensor</h5>
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div class="d-flex align-items-center w-100 w-md-auto">
                    <label class="me-2 text-nowrap" style="color: var(--text-main);"><i class="bi bi-bar-chart-line me-1"></i>Pilih Sensor:</label>
                    <select id="chartSensorSelect" class="form-select form-select-sm w-100 w-md-auto" style="background: #ffffff; color: var(--text-main); border: 1px solid var(--glass-border);">
                        @foreach($sensors as $index => $sensor)
                            <option value="{{ $index }}" style="color: #333; background-color: #ffffff;">
                                {{ $sensor->sensor_label }} ({{ $sensor->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="position: relative; height: 50vh; min-height: 300px; max-height: 500px; width: 100%;">
                <canvas id="sensorChart"></canvas>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="glass-card">
            <h5 class="card-title mb-4"><i class="bi bi-table me-2"></i>Tabel Data ({{ $logData->total() }} records)</h5>
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div class="d-flex align-items-center w-100 w-md-auto">
                    <label class="me-2 text-nowrap" style="color: var(--text-main);"><i class="bi bi-filter me-1"></i>Filter Sensor:</label>
                    <select id="tableSensorSelect" class="form-select form-select-sm w-100 w-md-auto" style="background: #ffffff; color: var(--text-main); border: 1px solid var(--glass-border);">
                        <option value="all" style="color: #333; background-color: #ffffff;">Semua Sensor</option>
                        @foreach($sensors as $index => $sensor)
                            <option value="{{ $index }}" style="color: #333; background-color: #ffffff;">
                                {{ $sensor->sensor_label }} ({{ $sensor->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date Filter -->
                <form action="{{ url()->current() }}" method="GET" class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2 m-0 ms-md-auto w-100" style="max-width: 100%; flex: 1; justify-content: flex-end;">
                    <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 w-100 w-md-auto">
                        <div class="date-pill d-flex align-items-center px-3 py-2 rounded-pill shadow-sm w-100" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-calendar-event text-primary me-2"></i>
                            <input type="datetime-local" class="flatpickr-datetime form-control border-0 shadow-none bg-transparent p-0 w-100" name="start_date" value="{{ request('start_date') }}" style="min-width: 140px; outline: none; box-shadow: none; font-size: 0.9rem;" placeholder="Dari Waktu...">
                        </div>
                        <div class="d-none d-sm-flex align-items-center justify-content-center text-white-50">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                        <div class="date-pill d-flex align-items-center px-3 py-2 rounded-pill shadow-sm w-100" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-calendar-check text-primary me-2"></i>
                            <input type="datetime-local" class="flatpickr-datetime form-control border-0 shadow-none bg-transparent p-0 w-100" name="end_date" value="{{ request('end_date') }}" style="min-width: 140px; outline: none; box-shadow: none; font-size: 0.9rem;" placeholder="Sampai Waktu...">
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-2 mt-md-0">
                        <button type="submit" class="btn btn-primary flex-grow-1 flex-md-grow-0 px-3 rounded-pill shadow-sm d-flex align-items-center justify-content-center" style="height: 42px;" title="Terapkan Filter"><i class="bi bi-search me-1"></i> <span class="d-md-none">Filter</span></button>
                        @if(request()->has('start_date') || request()->has('end_date'))
                            <a href="{{ url()->current() }}" class="btn btn-secondary text-white rounded-pill shadow-sm d-flex align-items-center justify-content-center px-3" style="height: 42px;" title="Reset Filter"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-glass mb-0" id="sensorDataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Waktu</th>
                            @foreach($sensors as $sensorIndex => $sensor)
                                <th class="sensor-col" data-sensor-index="{{ $sensorIndex }}">
                                    {{ $sensor->sensor_label }} <br><small>({{ $sensor->unit }})</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logData as $index => $row)
                            <tr>
                                <td>{{ $logData->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->recorded_at)->format('d/m/Y H:i:s') }}</td>
                                @foreach($sensors as $sensorIndex => $sensor)
                                    <td class="sensor-col" data-sensor-index="{{ $sensorIndex }}">
                                        @if(isset($row->{$sensor->sensor_name}))
                                            {{ number_format($row->{$sensor->sensor_name}, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logData->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <nav>
                        <ul class="pagination pagination-glass mb-0">
                            @if($logData->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">«</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $logData->previousPageUrl() }}">«</a></li>
                            @endif

                            @foreach($logData->getUrlRange(max(1, $logData->currentPage() - 2), min($logData->lastPage(), $logData->currentPage() + 2)) as $page => $url)
                                @if($page == $logData->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if($logData->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $logData->nextPageUrl() }}">»</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">»</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                <p class="text-center mt-2 small text-white-50">
                    Showing {{ $logData->firstItem() }} - {{ $logData->lastItem() }} of {{ $logData->total() }} records
                </p>
            @endif
        </div>
    @else
        <!-- No Data -->
        <div class="glass-card text-center py-5">
            <i class="bi bi-inbox text-white-50 mb-3" style="font-size: 4rem;"></i>
            <h4 class="text-white mb-2">Belum Ada Riwayat Data</h4>
            <p class="text-white-50">Data sensor belum terekam atau tidak ditemukan pada rentang tanggal tersebut.</p>
            @if(request()->has('start_date') || request()->has('end_date'))
                <a href="{{ url()->current() }}" class="btn btn-primary rounded-pill px-4 mt-3">Reset Filter</a>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr for datetime inputs
        flatpickr(".flatpickr-datetime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            allowInput: true,
            altInput: true,
            altFormat: "d M Y H:i",
            disableMobile: false
        });

        const canvas = document.getElementById('sensorChart');
        if(canvas) {
            const ctx = canvas.getContext('2d');
            const chartData = @json($chartData ?? []);
            const sensors = @json($sensors ?? []);

            const colors = [
                { border: '#22c55e', bg: 'rgba(34, 197, 94, 0.3)' },
                { border: '#0ea5e9', bg: 'rgba(14, 165, 233, 0.3)' },
                { border: '#f59e0b', bg: 'rgba(245, 158, 11, 0.3)' },
                { border: '#8b5cf6', bg: 'rgba(139, 92, 246, 0.3)' },
                { border: '#ef4444', bg: 'rgba(239, 68, 68, 0.3)' },
                { border: '#06b6d4', bg: 'rgba(6, 182, 212, 0.3)' },
                { border: '#84cc16', bg: 'rgba(132, 204, 22, 0.3)' },
                { border: '#ec4899', bg: 'rgba(236, 72, 153, 0.3)' },
            ];

            function getFilteredData(sensorIndex) {
                const sensor = sensors[sensorIndex];
                if(!sensor) return { labels: [], dataset: {} };
                
                const sensorName = sensor.sensor_name;
                const filteredRows = chartData.filter(row => row[sensorName] !== null && row[sensorName] !== undefined);
                
                const filteredLabels = filteredRows.map(row => {
                    const date = new Date(row.recorded_at);
                    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                });
                const filteredData = filteredRows.map(row => row[sensorName]);
                
                const colorIndex = sensorIndex % colors.length;
                return {
                    labels: filteredLabels,
                    dataset: {
                        label: sensor.sensor_label + (sensor.unit ? ` (${sensor.unit})` : ''),
                        data: filteredData,
                        borderColor: colors[colorIndex].border,
                        backgroundColor: colors[colorIndex].bg,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: colors[colorIndex].border,
                        pointHoverRadius: 6,
                    }
                };
            }

            if(chartData.length > 0 && sensors.length > 0) {
                const initialData = getFilteredData(0);
                let sensorChart = new Chart(ctx, {
                    type: 'line',
                    data: { labels: initialData.labels, datasets: [initialData.dataset] },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { color: 'rgba(255,255,255,0.7)' }, grid: { color: 'rgba(255,255,255,0.1)' } },
                            y: { ticks: { color: 'rgba(255,255,255,0.7)' }, grid: { color: 'rgba(255,255,255,0.1)' } }
                        }
                    }
                });

                document.getElementById('chartSensorSelect')?.addEventListener('change', function () {
                    const selectedIndex = parseInt(this.value);
                    const filteredData = getFilteredData(selectedIndex);
                    sensorChart.data.labels = filteredData.labels;
                    sensorChart.data.datasets = [filteredData.dataset];
                    sensorChart.update();
                });
            }
        }

        // Table column filter listener
        document.getElementById('tableSensorSelect')?.addEventListener('change', function () {
            const selectedValue = this.value;
            const sensorCols = document.querySelectorAll('.sensor-col');
            const tableRows = document.querySelectorAll('#sensorDataTable tbody tr');

            sensorCols.forEach(col => {
                if (selectedValue === 'all') {
                    col.style.display = '';
                } else {
                    col.style.display = col.dataset.sensorIndex === selectedValue ? '' : 'none';
                }
            });

            tableRows.forEach(row => {
                if (selectedValue === 'all') {
                    row.style.display = '';
                } else {
                    const sensorCell = row.querySelector(`.sensor-col[data-sensor-index="${selectedValue}"]`);
                    if (sensorCell) {
                        row.style.display = sensorCell.textContent.trim() === '-' ? 'none' : '';
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection
