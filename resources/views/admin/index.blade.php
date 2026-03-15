<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Devices - Swaratani</title>
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
            color: #1f2937;
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
            background: #fbbf24;
            color: #1f2937;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            margin: 2px;
            font-size: 0.75rem;
            display: inline-block;
        }

        .badge-token {
            background: rgba(100, 116, 139, 0.15);
            color: #475569;
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

        .btn-action-qr {
            background: rgba(14, 165, 233, 0.2);
            color: #0ea5e9;
        }

        .btn-action-qr:hover {
            background: rgba(14, 165, 233, 0.4);
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

        /* QR Modal */
        .qr-modal .modal-content {
            background: var(--glass-bg, #fff);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border, #e2e8f0);
            border-radius: 24px;
        }

        .qr-modal .modal-header {
            border-bottom: 1px solid var(--glass-border, #e2e8f0);
            padding: 1.25rem 1.5rem;
        }

        .qr-modal .modal-body {
            padding: 2rem;
            text-align: center;
        }

        .qr-display {
            display: inline-block;
            padding: 1rem;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        .qr-token-text {
            font-family: monospace;
            font-size: 1.1rem;
            letter-spacing: 2px;
            color: #475569;
            background: rgba(100, 116, 139, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
            margin-top: 1rem;
        }

        .btn-download-qr {
            background: var(--primary-gradient, linear-gradient(135deg, #0ea5e9, #0369a1));
            border: none;
            color: #fff;
            padding: 0.65rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-download-qr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(14, 165, 233, 0.3);
            color: #fff;
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

        /* ========= Mobile Responsive ========= */
        @media (max-width: 768px) {
            .container.py-5 {
                padding: 1.5rem 0.75rem !important;
            }

            .page-title {
                font-size: 1.25rem;
            }

            .d-flex.justify-content-between.align-items-center.mb-4 {
                flex-direction: column;
                gap: 0.75rem;
                align-items: stretch !important;
            }

            .glass-card {
                border-radius: 16px;
                padding: 0.5rem;
            }

            /* Table → Card Layout */
            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                background: var(--glass-bg);
                border: 1px solid var(--glass-border);
                border-radius: 12px;
                padding: 0.75rem;
                margin-bottom: 0.75rem;
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.35rem 0.5rem;
                border: none;
                font-size: 0.85rem;
            }

            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                font-size: 0.75rem;
                color: var(--text-secondary);
                margin-right: 0.75rem;
                flex-shrink: 0;
            }

            .table tbody td:last-child {
                justify-content: flex-end;
                padding-top: 0.5rem;
                border-top: 1px solid var(--glass-border);
                margin-top: 0.25rem;
            }

            /* Hide less important on small screens */
            .table tbody td.d-mobile-none {
                display: none;
            }

            .btn-action {
                width: 40px;
                height: 40px;
            }
        }

        @media (max-width: 400px) {
            .container.py-5 {
                padding: 1rem 0.5rem !important;
            }
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Swaratani" height="40" class="me-2">
                <span class="fw-bold" style="color: var(--navbar_text, #333);">Swaratani IoT</span>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.activity-logs') }}">
                    <i class="bi bi-journal-text me-1"></i> Activity Logs
                </a>
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
                <a href="{{ route('admin.activity-logs') }}" class="btn btn-outline-light">
                    <i class="bi bi-journal-text me-1"></i> Logs
                </a>
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
                                <td data-label="#" class="fw-semibold">{{ $loop->iteration }}</td>
                                <td data-label="Device">
                                    <a href="{{ route('admin.device.monitoring', $device->id) }}"
                                        class="text-decoration-none">
                                        <div class="fw-bold" style="color: #1f2937;">{{ $device->name }}</div>
                                        <small style="color: #64748b;">{{ $device->table_name }}</small>
                                    </a>
                                </td>
                                <td data-label="Tipe">
                                    <span class="badge-type">
                                        <i
                                            class="bi {{ $device->type === 'aws' ? 'bi-cloud-sun' : 'bi-flower1' }} me-1"></i>
                                        {{ strtoupper($device->type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td data-label="Sensors">
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
                                        <span style="color: var(--text-secondary);">-</span>
                                    @endif
                                </td>
                                <td data-label="Outputs">
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
                                        <span style="color: var(--text-secondary);">-</span>
                                    @endif
                                </td>
                                <td data-label="MQTT" class="d-mobile-none">
                                    <code class="text-info">{{ $device->mqtt_topic }}</code>
                                </td>
                                <td data-label="Token" class="d-mobile-none">
                                    <span class="badge-token">{{ $device->token }}</span>
                                </td>
                                <td data-label="" class="text-center">
                                    <button type="button" class="btn-action btn-action-qr" title="QR Code"
                                        onclick="showQrModal('{{ $device->token }}', '{{ $device->name }}')">
                                        <i class="bi bi-qr-code"></i>
                                    </button>
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

    <!-- QR Code Modal - QRIS Style -->
    <div class="modal fade qr-modal" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <!-- Close button -->
                    <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal"
                        style="top: 12px; right: 12px; z-index: 10; filter: invert(1);"></button>

                    <!-- QRIS-style Card -->
                    <div class="qris-card" id="qrisCard">
                        <!-- Header gradient band -->
                        <div class="qris-header">
                            <div class="qris-logo-row">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="qris-header-logo">
                                <div class="qris-header-text">
                                    <div class="qris-brand">SWARATANI</div>
                                    <div class="qris-sub">Smart Agriculture IoT</div>
                                </div>
                            </div>
                        </div>

                        <!-- Device name -->
                        <div class="qris-device-name" id="qrisDeviceName">Device Name</div>

                        <!-- QR Code area with gradient border -->
                        <div class="qris-qr-wrapper">
                            <div class="qris-qr-border">
                                <canvas id="qrCanvas" width="280" height="280"></canvas>
                            </div>
                        </div>

                        <!-- Token -->
                        <div class="qris-token" id="qrisToken">XXXXXXXXXXXXXXXX</div>

                        <!-- Footer -->
                        <div class="qris-footer">
                            <span><i class="bi bi-shield-check me-1"></i>Scan untuk tambah device</span>
                        </div>
                    </div>

                    <!-- Download button (outside card for clean export) -->
                    <div class="text-center p-3">
                        <button class="btn btn-download-qr" onclick="downloadQr()">
                            <i class="bi bi-download me-2"></i>Download QR Card
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- QRCode.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <!-- html2canvas for card download -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script>
        let currentDeviceName = '';
        const logoImg = new Image();
        logoImg.crossOrigin = 'anonymous';
        logoImg.src = '{{ asset("images/logo.png") }}';

        function showQrModal(token, deviceName) {
            currentDeviceName = deviceName;
            document.getElementById('qrisDeviceName').textContent = deviceName;
            document.getElementById('qrisToken').textContent = token;

            generateQrisQr(token);

            const modal = new bootstrap.Modal(document.getElementById('qrModal'));
            modal.show();
        }

        function generateQrisQr(text) {
            const canvas = document.getElementById('qrCanvas');
            const size = 280;
            canvas.width = size;
            canvas.height = size;
            const ctx = canvas.getContext('2d');

            // Create temp container for QR generation
            const tempDiv = document.createElement('div');
            tempDiv.style.cssText = 'position:absolute;left:-9999px;top:-9999px;';
            document.body.appendChild(tempDiv);

            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = size;
            tempCanvas.height = size;
            tempDiv.appendChild(tempCanvas);

            const qr = new QRCode(tempDiv, {
                text: text,
                width: size,
                height: size,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H,
            });

            setTimeout(function() {
                const qrSource = tempDiv.querySelector('canvas') || tempDiv.querySelector('img');
                if (!qrSource) {
                    document.body.removeChild(tempDiv);
                    return;
                }

                // Get QR data from the generated image
                const tempCtx = document.createElement('canvas').getContext('2d');
                const tempC = tempCtx.canvas;
                tempC.width = size;
                tempC.height = size;
                tempCtx.drawImage(qrSource, 0, 0, size, size);
                const imageData = tempCtx.getImageData(0, 0, size, size);

                // Clear main canvas
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, size, size);

                // Draw QR modules as rounded dots
                const moduleCount = 33; // QR version for 16 chars + H correction
                const moduleSize = size / moduleCount;
                const dotRadius = moduleSize * 0.38;

                // Gradient colors for dots
                const gradient1 = ctx.createLinearGradient(0, 0, size, size);
                gradient1.addColorStop(0, '#0369a1');
                gradient1.addColorStop(0.5, '#0ea5e9');
                gradient1.addColorStop(1, '#0d9488');

                for (let row = 0; row < moduleCount; row++) {
                    for (let col = 0; col < moduleCount; col++) {
                        const px = Math.floor((col + 0.5) * (size / moduleCount));
                        const py = Math.floor((row + 0.5) * (size / moduleCount));

                        // Sample pixel from QR image
                        const idx = (py * size + px) * 4;
                        const isDark = imageData.data[idx] < 128;

                        if (isDark) {
                            const cx = col * moduleSize + moduleSize / 2;
                            const cy = row * moduleSize + moduleSize / 2;

                            // Check if this is part of finder pattern (corners)
                            const isFinderPattern =
                                (row < 7 && col < 7) ||
                                (row < 7 && col >= moduleCount - 7) ||
                                (row >= moduleCount - 7 && col < 7);

                            if (isFinderPattern) {
                                // Draw finder patterns as squares with rounded corners
                                ctx.fillStyle = '#0369a1';
                                roundRect(ctx, col * moduleSize + 0.5, row * moduleSize + 0.5,
                                    moduleSize - 1, moduleSize - 1, 1.5);
                                ctx.fill();
                            } else {
                                // Draw regular modules as circles
                                ctx.fillStyle = '#1e3a5f';
                                ctx.beginPath();
                                ctx.arc(cx, cy, dotRadius, 0, Math.PI * 2);
                                ctx.fill();
                            }
                        }
                    }
                }

                // Draw logo in center
                if (logoImg.complete && logoImg.naturalWidth > 0) {
                    drawCenteredLogo(ctx, size);
                } else {
                    logoImg.onload = function() { drawCenteredLogo(ctx, size); };
                }

                document.body.removeChild(tempDiv);
            }, 400);
        }

        function drawCenteredLogo(ctx, size) {
            const logoSize = size * 0.2;
            const cx = size / 2;
            const cy = size / 2;
            const padding = 8;
            const totalSize = logoSize + padding * 2;

            // White circle background
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(cx, cy, totalSize / 2 + 2, 0, Math.PI * 2);
            ctx.fill();

            // Gradient border ring
            ctx.strokeStyle = '#0ea5e9';
            ctx.lineWidth = 2.5;
            ctx.beginPath();
            ctx.arc(cx, cy, totalSize / 2 + 2, 0, Math.PI * 2);
            ctx.stroke();

            // Draw logo
            ctx.drawImage(logoImg, cx - logoSize / 2, cy - logoSize / 2, logoSize, logoSize);
        }

        function roundRect(ctx, x, y, width, height, radius) {
            ctx.beginPath();
            ctx.moveTo(x + radius, y);
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            ctx.lineTo(x + width, y + height - radius);
            ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
            ctx.lineTo(x + radius, y + height);
            ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
            ctx.closePath();
        }

        function downloadQr() {
            const card = document.getElementById('qrisCard');
            html2canvas(card, {
                scale: 3,
                backgroundColor: '#ffffff',
                borderRadius: '20px',
                useCORS: true,
            }).then(function(canvas) {
                const link = document.createElement('a');
                const safeName = currentDeviceName.replace(/[^a-zA-Z0-9]/g, '_');
                link.download = 'QR_Swaratani_' + safeName + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>

</body>

</html>