<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Tiket Baru - {{ env('APP_NAME', 'Swaratani') }}</title>
    @include('partials.pwa-head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @include('partials.theme')

    <style>
        .page-title {
            color: var(--text-main);
            font-weight: 800;
            font-size: 1.75rem;
        }
        .form-control, .form-select {
            background: rgba(255,255,255,0.7);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 12px 15px;
        }
        .form-control:focus, .form-select:focus {
            background: #fff;
            box-shadow: 0 0 0 0.25rem rgba(14, 95, 138, 0.25);
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>

    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('tickets.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                <span class="fw-bold" style="color: var(--navbar_text, #333);">Kembali</span>
            </a>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="page-title mb-4">Buat Tiket Laporan</h2>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="glass-card" style="padding: 1.5rem; border-radius: 20px;">
                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subjek (Judul Singkat)</label>
                            <input type="text" name="subject" class="form-control" required placeholder="Contoh: Pompa tidak menyala">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category" class="form-select" id="categorySelect" required onchange="checkCategory()">
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Perangkat Hardware / IoT">Perangkat Hardware / IoT</option>
                                <option value="Aplikasi Web">Aplikasi Web</option>
                                <option value="Aplikasi Mobile">Aplikasi Mobile</option>
                                <option value="Lainnya">Lainnya (Tulis Manual)</option>
                            </select>
                            <input type="text" name="category_manual" id="categoryManual" class="form-control mt-2" placeholder="Sebutkan Kategori Lainnya" style="display: none;" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Detail Masalah</label>
                            <textarea name="description" class="form-control" rows="5" required placeholder="Ceritakan secara detail kronologi atau masalah yang Anda alami..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Bukti Screenshot (Opsional)</label>
                            <input type="file" name="attachment" class="form-control" accept="image/jpeg,image/png,image/jpg">
                            <div class="form-text">Batas maksimal: 2MB. Format: JPG, PNG.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" style="padding: 12px; border-radius: 12px; font-weight: 600;">
                                Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('partials.pwa-scripts')
    <script>
        function checkCategory() {
            var select = document.getElementById("categorySelect");
            var manualInput = document.getElementById("categoryManual");
            if (select.value === "Lainnya") {
                manualInput.style.display = "block";
                manualInput.disabled = false;
                select.name = ""; // Remove name so it's not submitted
                manualInput.name = "category"; // Replace it
            } else {
                manualInput.style.display = "none";
                manualInput.disabled = true;
                manualInput.name = "category_manual";
                select.name = "category";
            }
        }
    </script>
</body>
</html>
