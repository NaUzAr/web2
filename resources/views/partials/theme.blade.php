{{--
THEME CONFIGURATION - SmartAgri IoT
Ubah tema di sini untuk mengubah seluruh website

Tema tersedia:
- green: Tema hijau (Agriculture/Nature)
- red: Tema merah
- blue: Tema biru (Ocean/Tech)
- purple: Tema ungu (Modern/Creative)
--}}

@php
    // === GANTI TEMA DI SINI ===
    $theme = 'red';  // Pilihan: 'green', 'red', 'blue', 'purple'
    // ===========================

    $themes = [
        'green' => [
            'primary' => '#22c55e',
            'primary_dark' => '#166534',
            'primary_light' => '#86efac',
            'secondary' => '#0ea5e9',
            'secondary_light' => '#7dd3fc',
            'secondary_dark' => '#0369a1',
            'gradient_primary' => 'linear-gradient(135deg, #22c55e 0%, #16a34a 50%, #0ea5e9 100%)',
            'gradient_secondary' => 'linear-gradient(135deg, #86efac 0%, #22c55e 100%)',
            'gradient_bg' => 'linear-gradient(135deg, #134e4a 0%, #166534 50%, #14532d 100%)',
            'navbar_bg' => 'rgba(20, 83, 45, 0.95)',
            'navbar_bg_light' => 'rgba(20, 83, 45, 0.9)',
            'glow_1' => 'rgba(34, 197, 94, 0.3)',
            'glow_2' => 'rgba(14, 165, 233, 0.3)',
            'glow_3' => 'rgba(134, 239, 172, 0.2)',
            'mobile_nav_dark' => 'rgba(15, 60, 35, 0.95)',
        ],
        'red' => [
            'primary' => '#ef4444',
            'primary_dark' => '#991b1b',
            'primary_light' => '#fca5a5',
            'secondary' => '#f97316',
            'secondary_light' => '#fdba74',
            'secondary_dark' => '#c2410c',
            'gradient_primary' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #f97316 100%)',
            'gradient_secondary' => 'linear-gradient(135deg, #fca5a5 0%, #ef4444 100%)',
            'gradient_bg' => 'linear-gradient(135deg, #450a0a 0%, #7f1d1d 50%, #991b1b 100%)',
            'navbar_bg' => 'rgba(127, 29, 29, 0.95)',
            'navbar_bg_light' => 'rgba(127, 29, 29, 0.9)',
            'glow_1' => 'rgba(239, 68, 68, 0.3)',
            'glow_2' => 'rgba(249, 115, 22, 0.3)',
            'glow_3' => 'rgba(252, 165, 165, 0.2)',
            'mobile_nav_dark' => 'rgba(69, 10, 10, 0.95)',
        ],
        'blue' => [
            'primary' => '#3b82f6',
            'primary_dark' => '#1e40af',
            'primary_light' => '#93c5fd',
            'secondary' => '#06b6d4',
            'secondary_light' => '#67e8f9',
            'secondary_dark' => '#0e7490',
            'gradient_primary' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #06b6d4 100%)',
            'gradient_secondary' => 'linear-gradient(135deg, #93c5fd 0%, #3b82f6 100%)',
            'gradient_bg' => 'linear-gradient(135deg, #0c1929 0%, #1e3a5f 50%, #1e40af 100%)',
            'navbar_bg' => 'rgba(30, 64, 175, 0.95)',
            'navbar_bg_light' => 'rgba(30, 64, 175, 0.9)',
            'glow_1' => 'rgba(59, 130, 246, 0.3)',
            'glow_2' => 'rgba(6, 182, 212, 0.3)',
            'glow_3' => 'rgba(147, 197, 253, 0.2)',
            'mobile_nav_dark' => 'rgba(12, 25, 41, 0.95)',
        ],
        'purple' => [
            'primary' => '#a855f7',
            'primary_dark' => '#6b21a8',
            'primary_light' => '#d8b4fe',
            'secondary' => '#ec4899',
            'secondary_light' => '#f9a8d4',
            'secondary_dark' => '#be185d',
            'gradient_primary' => 'linear-gradient(135deg, #a855f7 0%, #9333ea 50%, #ec4899 100%)',
            'gradient_secondary' => 'linear-gradient(135deg, #d8b4fe 0%, #a855f7 100%)',
            'gradient_bg' => 'linear-gradient(135deg, #1a0a2e 0%, #3b0764 50%, #6b21a8 100%)',
            'navbar_bg' => 'rgba(107, 33, 168, 0.95)',
            'navbar_bg_light' => 'rgba(107, 33, 168, 0.9)',
            'glow_1' => 'rgba(168, 85, 247, 0.3)',
            'glow_2' => 'rgba(236, 72, 153, 0.3)',
            'glow_3' => 'rgba(216, 180, 254, 0.2)',
            'mobile_nav_dark' => 'rgba(26, 10, 46, 0.95)',
        ],
    ];

    $t = $themes[$theme] ?? $themes['green'];
@endphp

<style>
    :root {
        /* Primary Colors */
        --primary:
            {{ $t['primary'] }}
        ;
        --primary-dark:
            {{ $t['primary_dark'] }}
        ;
        --primary-light:
            {{ $t['primary_light'] }}
        ;

        /* Secondary Colors */
        --secondary:
            {{ $t['secondary'] }}
        ;
        --secondary-light:
            {{ $t['secondary_light'] }}
        ;
        --secondary-dark:
            {{ $t['secondary_dark'] }}
        ;

        /* Gradients */
        --primary-gradient:
            {{ $t['gradient_primary'] }}
        ;
        --secondary-gradient:
            {{ $t['gradient_secondary'] }}
        ;
        --nature-gradient:
            {{ $t['gradient_bg'] }}
        ;

        /* Navbar */
        --navbar-bg:
            {{ $t['navbar_bg'] }}
        ;
        --navbar-bg-light:
            {{ $t['navbar_bg_light'] }}
        ;
        --mobile-nav-dark:
            {{ $t['mobile_nav_dark'] }}
        ;

        /* Glow Effects */
        --glow-1:
            {{ $t['glow_1'] }}
        ;
        --glow-2:
            {{ $t['glow_2'] }}
        ;
        --glow-3:
            {{ $t['glow_3'] }}
        ;

        /* Glass */
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

    /* Background Animation */
    .bg-animation {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
    }

    .bg-animation::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 20% 80%, var(--glow-1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, var(--glow-2) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, var(--glow-3) 0%, transparent 40%);
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translate(0, 0) rotate(0deg);
        }

        33% {
            transform: translate(30px, -30px) rotate(5deg);
        }

        66% {
            transform: translate(-20px, 20px) rotate(-5deg);
        }
    }

    /* Navbar Glass */
    .navbar-glass {
        background: var(--navbar-bg) !important;
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--glass-border);
    }

    .navbar-brand {
        font-weight: 700;
        color: var(--primary-light) !important;
    }

    .navbar-brand i {
        color: var(--primary);
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        color: var(--primary-light) !important;
    }

    /* Glass Cards */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 2rem;
        transition: all 0.4s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        border-color: var(--primary);
    }

    /* Buttons */
    .btn-gradient {
        background: var(--primary-gradient);
        border: none;
        color: #fff;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-gradient:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        color: #fff;
    }

    .btn-glass {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        color: #fff;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        transform: translateY(-3px);
    }

    /* Form Controls */
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
        border-color: var(--primary);
        color: #fff;
        box-shadow: 0 0 0 3px rgba(var(--primary), 0.2);
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .form-label {
        color: var(--primary-light);
        font-weight: 600;
    }

    /* Live Indicator */
    .live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(0, 0, 0, 0.2);
        color: var(--primary-light);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background: var(--primary);
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.5;
            transform: scale(1.2);
        }
    }

    /* Tables */
    .table-glass thead th {
        background: var(--navbar-bg);
        color: var(--primary-light);
        font-weight: 600;
        border-bottom: 1px solid var(--glass-border);
    }

    .table-glass tbody td {
        border-bottom: 1px solid var(--glass-border);
        color: rgba(255, 255, 255, 0.9);
    }

    /* Dropdown */
    .dropdown-menu {
        background: var(--navbar-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
    }

    .dropdown-item {
        color: rgba(255, 255, 255, 0.8);
    }

    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--primary-light);
    }

    /* Footer */
    .footer-glass {
        background: var(--navbar-bg-light);
        backdrop-filter: blur(20px);
        border-top: 1px solid var(--glass-border);
        padding: 2rem 0;
    }

    .footer-link {
        color: var(--primary-light);
        text-decoration: none;
        font-weight: 600;
    }

    /* Admin Badge */
    .admin-badge {
        background: var(--primary-gradient);
        color: #fff;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
    }

    /* Sensor Card */
    .sensor-card:hover {
        border-color: var(--primary);
    }

    .sensor-unit {
        color: var(--primary-light);
    }

    /* Tabs */
    .nav-tabs-glass .nav-link.active {
        background: transparent;
        color: var(--primary-light);
        border-bottom: 3px solid var(--primary);
    }

    /* Toggle Switch */
    .toggle-switch input:checked+.toggle-slider {
        background: var(--primary-gradient);
    }

    /* Range Slider */
    .range-slider::-webkit-slider-thumb {
        background: var(--primary-gradient);
    }

    /* Alerts */
    .alert-success-custom {
        background: rgba(0, 0, 0, 0.15);
        border: 1px solid var(--primary);
        color: var(--primary-light);
        border-radius: 12px;
    }

    /* Badge Sensor */
    .badge-sensor {
        background: rgba(0, 0, 0, 0.15);
        color: var(--primary-light);
    }

    /* Btn View */
    .btn-view {
        background: rgba(0, 0, 0, 0.15);
        color: var(--primary-light);
        border: 1px solid var(--primary);
    }

    .btn-view:hover {
        background: var(--primary);
        color: #fff;
    }

    /* Mobile Navbar */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: var(--navbar-bg);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid var(--glass-border);
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .navbar-nav .dropdown-menu {
            background: var(--mobile-nav-dark);
        }
    }

    /* Navbar Toggler */
    .navbar-toggler:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
    }

    /* Link Styles */
    .link-primary {
        color: var(--primary-light);
    }

    .link-primary:hover {
        color: var(--primary);
    }
</style>