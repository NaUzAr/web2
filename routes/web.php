<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDeviceController;
use App\Http\Controllers\MonitoringController;

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
