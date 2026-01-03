<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorDataController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes untuk menerima data sensor dari device IoT
| Dan routes untuk authentication Flutter app
|
*/

// ==================== AUTH ROUTES (Flutter) ====================
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Device routes untuk Flutter
    Route::get('/devices', function () {
        return response()->json([
            'success' => true,
            'data' => auth()->user()->userDevices()->with('device')->get()
        ]);
    });
});

// ==================== SENSOR DATA ROUTES (IoT Device) ====================
// POST /api/sensor-data - Terima data sensor baru
Route::post('/sensor-data', [SensorDataController::class, 'store']);

// GET /api/sensor-data/{token} - Ambil data terbaru
Route::get('/sensor-data/{token}', [SensorDataController::class, 'show']);
