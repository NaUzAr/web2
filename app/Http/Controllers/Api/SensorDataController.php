<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller
{
    /**
     * Terima data sensor via HTTP POST
     * 
     * Endpoint: POST /api/sensor-data
     * 
     * Format Body JSON:
     * {
     *     "token": "XXXXXXXXXXXXXXXX",
     *     "temperature": 25.5,
     *     "humidity": 80,
     *     "rainfall": 0.5,
     *     ...sensor lainnya sesuai konfigurasi
     * }
     */
    public function store(Request $request)
    {
        try {
            // Validasi token
            $request->validate([
                'token' => 'required|string|size:16',
            ]);

            $token = $request->input('token');

            // Cari device berdasarkan token
            $device = Device::with('sensors')->where('token', $token)->first();

            if (!$device) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device not found. Invalid token.',
                ], 404);
            }

            $tableName = $device->table_name;

            // Pastikan tabel ada
            if (!\Schema::hasTable($tableName)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device table not found.',
                ], 500);
            }

            // Prepare data untuk insert
            $insertData = ['recorded_at' => now()];
            $receivedSensors = [];

            foreach ($device->sensors as $sensor) {
                $sensorName = $sensor->sensor_name;
                if ($request->has($sensorName)) {
                    $insertData[$sensorName] = (float) $request->input($sensorName);
                    $receivedSensors[] = $sensorName;
                }
            }

            // Pastikan ada data sensor yang diterima
            if (count($receivedSensors) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid sensor data received.',
                    'expected_sensors' => $device->sensors->pluck('sensor_name'),
                ], 400);
            }

            // Insert ke database
            DB::table($tableName)->insert($insertData);

            // Update last_seen_at for connection status
            $device->last_seen_at = now();
            $device->save();

            // CHECK GLOBAL RULES & SEND FCM NOTIFICATION IF DANGEROUS
            foreach ($receivedSensors as $rSensor) {
                $val = $insertData[$rSensor];
                $rule = \App\Models\GlobalSensorRule::where('sensor_key', $rSensor)->where('is_active', true)->first();
                
                if ($rule) {
                    $isDanger = false;
                    $reason = '';
                    
                    if ($rule->max_value !== null && $val > $rule->max_value) {
                        $isDanger = true;
                        $reason = "melewati batas " . $rule->max_value;
                    } elseif ($rule->min_value !== null && $val < $rule->min_value) {
                        $isDanger = true;
                        $reason = "di bawah batas " . $rule->min_value;
                    }

                    if ($isDanger) {
                        // Temukan semua user yang memiliki device ini
                        $userIds = \App\Models\UserDevice::where('device_id', $device->id)->pluck('user_id');
                        $users = \App\Models\User::whereIn('id', $userIds)->whereNotNull('fcm_token')->get();
                        
                        if ($users->count() > 0) {
                            $firebase = app(\App\Services\FirebaseService::class);
                            $title = "⚠️ CRITICAL: " . strtoupper(str_replace('ni_', '', $rSensor));
                            $body = "Nilai terkini {$val} {$reason} pada perangkat {$device->name}!";
                            
                            foreach ($users as $userToNotify) {
                                $firebase->sendToToken($userToNotify->fcm_token, $title, $body);
                            }
                        }
                    }
                }
            }

            Log::info("Sensor data received", [
                'device' => $device->name,
                'token' => $token,
                'sensors' => $receivedSensors,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data saved successfully.',
                'device' => $device->name,
                'sensors_received' => $receivedSensors,
                'recorded_at' => $insertData['recorded_at'],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Sensor Data API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get latest sensor data untuk device
     * 
     * Endpoint: GET /api/sensor-data/{token}
     */
    public function show($token)
    {
        try {
            $device = Device::with('sensors')->where('token', $token)->first();

            if (!$device) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device not found.',
                ], 404);
            }

            $tableName = $device->table_name;

            if (!\Schema::hasTable($tableName)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data available.',
                ], 404);
            }

            // Ambil data terbaru
            $latestData = DB::table($tableName)
                ->orderBy('recorded_at', 'desc')
                ->first();

            if (!$latestData) {
                return response()->json([
                    'success' => true,
                    'device' => $device->name,
                    'type' => $device->type,
                    'sensors' => $device->sensors,
                    'latest_data' => null,
                    'message' => 'No data recorded yet.',
                ]);
            }

            return response()->json([
                'success' => true,
                'device' => $device->name,
                'type' => $device->type,
                'sensors' => $device->sensors,
                'latest_data' => $latestData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
