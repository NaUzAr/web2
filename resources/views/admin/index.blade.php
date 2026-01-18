<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Devices - Smart Agriculture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @include('partials.theme')

    <style>
        /* Page Title */
        .page-title {
            color: #fff;
            font-weight: 700;
        }

        .page-title i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Table Styles */
        .table-dark-custom {
            background: var(--navbar-bg) !important;
        }

        .table-dark-custom th {
            color: var(--primary-light);
            font-weight: 600;
            border-bottom: 1px solid var(--glass-border) !important;
            padding: 1rem;
        }

        .table tbody tr {
            background: transparent;
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .table tbody td {
            color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem;
            vertical-align: middle;
        }

        /* Badges */
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
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            margin: 2px;
            font-size: 0.75rem;
            display: inline-block;
        }

        .badge-token {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-family: monospace;
            padding: 0.35rem 0.6rem;
            border-radius: 8px;
        }

        /* Action Buttons */
        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-action-edit {
            background: rgba(250, 204, 21, 0.2);
            color: #facc15;
        }

        .btn-action-edit:hover {
            background: rgba(250, 204, 21, 0.4);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-action-delete {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .btn-action-delete:hover {
            background: rgba(239, 68, 68, 0.4);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Empty State */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.3);
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.6);
            margin-top: 1rem;
        }

        .empty-state a {
            color: var(--primary-light);
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
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="bi bi-house me-1"></i> Beranda
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">
                <i class="bi bi-cpu-fill me-2"></i>Device Management
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.device.create') }}" class="btn btn-gradient">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Device
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success-custom mb-4">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="glass-card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-dark-custom">
                        <tr>
                            <th>#</th>
                            <th>Nama Device</th>
                            <th>Tipe</th>
                            <th>Sensors</th>
                            <th>Outputs</th>
                            <th>MQTT Topic</th>
                            <th>Token</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($devices as $device)
                            <tr>
                                <td class="fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('admin.device.monitoring', $device->id) }}"
                                        class="text-decoration-none">
                                        <div class="fw-bold text-white">{{ $device->name }}</div>
                                        <small class="text-white-50">{{ $device->table_name }}</small>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge-type">
                                        <i
                                            class="bi {{ $device->type === 'aws' ? 'bi-cloud-sun' : 'bi-flower1' }} me-1"></i>
                                        {{ strtoupper($device->type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    @if($device->sensors->count() > 0)
                                        @foreach($device->sensors->take(4) as $sensor)
                                            <span class="badge-sensor" title="{{ $sensor->sensor_label }}">
                                                {{ $sensor->sensor_name }}
                                            </span>
                                        @endforeach
                                        @if($device->sensors->count() > 4)
                                            <span class="badge-sensor">+{{ $device->sensors->count() - 4 }}</span>
                                        @endif
                                    @else
                                        <span class="text-white-50">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($device->outputs->count() > 0)
                                        @foreach($device->outputs->take(3) as $output)
                                            <span class="badge-output" title="{{ $output->output_label }}">
                                                <i class="bi bi-toggle-on me-1"></i>{{ $output->output_name }}
                                            </span>
                                        @endforeach
                                        @if($device->outputs->count() > 3)
                                            <span class="badge-output">+{{ $device->outputs->count() - 3 }}</span>
                                        @endif
                                    @else
                                        <span class="text-white-50">-</span>
                                    @endif
                                </td>
                                <td>
                                    <code class="text-info">{{ $device->mqtt_topic }}</code>
                                </td>
                                <td>
                                    <span class="badge-token">{{ $device->token }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.device.edit', $device->id) }}"
                                        class="btn-action btn-action-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.device.destroy', $device->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('⚠️ BAHAYA: Menghapus device akan MENGHAPUS TABEL {{ $device->table_name }} secara permanen!\n\nLanjutkan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>Belum ada device. <a href="{{ route('admin.device.create') }}">Tambah device
                                                pertama!</a></p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


</body>

</html>