<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDeviceController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\AutomationConfigController;
use App\Http\Controllers\ScheduleController;


Route::middleware(['auth'])->group(function () {

    // Grouping khusus URL awalan /admin
    Route::prefix('admin')->name('admin.')->group(function () {

        // List Semua Device
        Route::get('/devices', [AdminDeviceController::class, 'index'])->name('devices.index');

        // Create Device (Yg sudah dibuat sebelumnya)
        Route::get('/create-device', [AdminDeviceController::class, 'create'])->name('device.create');
        Route::post('/create-device', [AdminDeviceController::class, 'store'])->name('device.store');

        // Edit Device
        Route::get('/device/{id}/edit', [AdminDeviceController::class, 'edit'])->name('device.edit');
        Route::put('/device/{id}', [AdminDeviceController::class, 'update'])->name('device.update');

        // Delete Device
        Route::delete('/device/{id}', [AdminDeviceController::class, 'destroy'])->name('device.destroy');

        // Monitoring Device (Admin View)
        Route::get('/device/{id}/monitoring', [AdminDeviceController::class, 'showMonitoring'])->name('device.monitoring');

        // Toggle Output (Admin)
        Route::post('/device/{deviceId}/output/{outputId}/toggle', [AdminDeviceController::class, 'toggleOutput'])->name('device.output.toggle');

        // Status (Admin Polling)
        Route::get('/device/{id}/status', [AdminDeviceController::class, 'getStatus'])->name('device.status');
    });

    // === MONITORING ROUTES (untuk semua user yang login) ===
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/', [MonitoringController::class, 'index'])->name('index');
        Route::get('/add', [MonitoringController::class, 'create'])->name('create');
        Route::post('/add', [MonitoringController::class, 'store'])->name('store');
        Route::get('/device/{id}', [MonitoringController::class, 'show'])->name('show');
        Route::delete('/device/{id}', [MonitoringController::class, 'destroy'])->name('destroy');
        Route::post('/device/{id}/export', [MonitoringController::class, 'exportCsv'])->name('export');
        Route::post('/device/{id}/output/{outputId}/toggle', [MonitoringController::class, 'toggleOutput'])->name('output.toggle');
        Route::get('/device/{id}/status', [MonitoringController::class, 'getStatus'])->name('status');
    });

    // === AUTOMATION ROUTES (untuk user kelola automation) ===
    Route::prefix('device/{deviceId}/automation')->name('automation.')->group(function () {
        Route::get('/', [AutomationConfigController::class, 'index'])->name('index');
        Route::get('/create', [AutomationConfigController::class, 'create'])->name('create');
        Route::post('/', [AutomationConfigController::class, 'store'])->name('store');
    });

    Route::prefix('automation')->name('automation.')->group(function () {
        Route::get('/{id}/edit', [AutomationConfigController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AutomationConfigController::class, 'update'])->name('update');
        Route::delete('/{id}', [AutomationConfigController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [AutomationConfigController::class, 'toggle'])->name('toggle');
        Route::get('/device/{deviceId}/sensors', [AutomationConfigController::class, 'getSensorsForDevice'])->name('sensors');
    });

    // === AUTOMASI CUSTOM ROUTES ===
    Route::prefix('device/{id}/automasi')->name('automasi.')->group(function () {
        Route::get('/', [App\Http\Controllers\AutomasiController::class, 'index'])->name('index');
        Route::get('/fertilizer', [App\Http\Controllers\AutomasiController::class, 'fertilizer'])->name('fertilizer');
        Route::post('/fertilizer', [App\Http\Controllers\AutomasiController::class, 'storeFertilizer'])->name('fertilizer.store');
        Route::get('/climate', [App\Http\Controllers\AutomasiController::class, 'climate'])->name('climate');
        Route::post('/climate', [App\Http\Controllers\AutomasiController::class, 'storeClimate'])->name('climate.store');
    });

    // === SCHEDULE MANAGEMENT ROUTES (Real-time MQTT) ===
    Route::prefix('device/{userDeviceId}/schedule')->name('schedule.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::post('/time', [ScheduleController::class, 'storeTimeSchedules'])->name('time.store');
        Route::delete('/{slotId}', [ScheduleController::class, 'destroy'])->name('destroy');
        // Route::post('/sensor', [ScheduleController::class, 'storeSensorRule'])->name('sensor.store'); // Sensor rules might need rethink or move
    });
});

// Beranda (public)
Route::get('/', function () {
    return view('page.beranda');
})->name('home');

// --- LOGIN ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- REGISTER ---
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
