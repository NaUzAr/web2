<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MqttListener extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'mqtt:listen 
                            {--host=localhost : MQTT Broker Host}
                            {--port=1883 : MQTT Broker Port}
                            {--username= : MQTT Username (optional)}
                            {--password= : MQTT Password (optional)}';

    /**
     * The console command description.
     */
    protected $description = 'Listen to MQTT broker for sensor data and device status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Baca dari .env dulu, jika tidak ada gunakan option/default
        $host = $this->option('host') !== 'localhost'
            ? $this->option('host')
            : env('MQTT_HOST', 'localhost');
        $port = (int) ($this->option('port') !== 1883
            ? $this->option('port')
            : env('MQTT_PORT', 1883));
        $username = $this->option('username') ?: env('MQTT_USERNAME');
        $password = $this->option('password') ?: env('MQTT_PASSWORD');

        $this->info("🚀 Starting MQTT Listener...");
        $this->info("   Broker: {$host}:{$port}");

        try {
            // Setup connection
            $connectionSettings = new ConnectionSettings();

            if ($username && $password) {
                $connectionSettings = $connectionSettings
                    ->setUsername($username)
                    ->setPassword($password);
            }

            $connectionSettings = $connectionSettings
                ->setKeepAliveInterval(60)
                ->setConnectTimeout(10);

            // Create MQTT client
            $mqtt = new MqttClient($host, $port, 'laravel-listener-' . uniqid());
            $mqtt->connect($connectionSettings, true);

            $this->info("✅ Connected to MQTT Broker!");

            // Get all devices and subscribe to their topics + /sub
            $devices = Device::all();

            if ($devices->isEmpty()) {
                $this->warn("⚠️  No devices found. Create devices first via admin panel.");
            } else {
                foreach ($devices as $device) {
                    // Subscribe to {mqtt_topic}/pub (mesin publish ke topic ini, server listen)
                    $subTopic = rtrim($device->mqtt_topic, '/') . '/pub';
                    $this->info("📡 Subscribed to: {$subTopic} (Device: {$device->name})");

                    $mqtt->subscribe($subTopic, function ($topic, $message) {
                        $this->processMessage($topic, $message);
                    }, 0);
                }
            }

            $this->info("");
            $this->info("👂 Listening for messages... (Press Ctrl+C to stop)");
            $this->info("─────────────────────────────────────────────────");

            // Loop forever
            $mqtt->loop(true);

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            Log::error('MQTT Listener Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Process incoming MQTT message (sensor data from ESP32)
     * Format: <dat|{JSON}|>
     */
    private function processMessage($topic, $message)
    {
        $timestamp = now()->format('H:i:s');
        $this->line("[{$timestamp}] 📨 Topic: {$topic}");
        $this->line("           Raw: {$message}");

        try {
            // Try to parse JSON
            $data = json_decode($message, true);

            // AUTO-FIX: Attempt to fix common malformed JSON from Arduino (unquoted strings)
            // Example: {"waktu":Jumat...} -> {"waktu":"Jumat..."}
            if (!$data) {
                // AUTO-FIX 1: Remove trailing commas in objects (e.g. {"a":1,})
                // Look for comma followed immediately by }
                $fixedMessage = preg_replace('/,(\s*})/', '$1', $message);
                $data = json_decode($fixedMessage, true);

                if (!$data) {
                    // AUTO-FIX 2: Handle unquoted strings + trailing commas combined
                    // First regex: wrap unquoted values (alphanumeric+symbols) in quotes
                    $fixedMessage = preg_replace('/:\s*([a-zA-Z0-9_\-\.\:\s]+?)\s*([,}])/', ':"$1"$2', $message);
                    // Second regex: remove trailing commas again (in case they existed)
                    $fixedMessage = preg_replace('/,(\s*})/', '$1', $fixedMessage);
                    $data = json_decode($fixedMessage, true);
                }

                if ($data) {
                    $this->line("           🔧 Auto-fixed JSON");
                }
            }

            if (!$data) {
                // Fallback: Try wrapper <dat|...|>
                if (preg_match('/<dat\|(.*?)\|>/', $message, $matches)) {
                    $data = json_decode($matches[1], true);
                }
            }

            if (!$data) {
                $this->warn("           ⚠️  Invalid data format");
                return;
            }

            // Cari device berdasarkan topic ATAU token
            // Topic yang diterima: {mqtt_topic}/sub, tapi di DB hanya {mqtt_topic}
            $baseTopic = preg_replace('/\/pub$/', '', $topic);
            $device = Device::where('mqtt_topic', $baseTopic)->first();

            // Fallback: cari dengan topic asli
            if (!$device) {
                $device = Device::where('mqtt_topic', $topic)->first();
            }

            if (!$device && isset($data['token'])) {
                $device = Device::where('token', $data['token'])->first();
            }

            if (!$device) {
                $this->warn("           ⚠️  Device not found for topic: {$topic}");
                return;
            }

            // Determine data type and process accordingly
            $this->processDataByType($device, $data);

        } catch (\Exception $e) {
            $this->error("           ❌ Error: " . $e->getMessage());
            Log::error('MQTT Process Error: ' . $e->getMessage());
        }
    }

    /**
     * Process data based on type (counter 1-7 from ESP32)
     */
    /**
     * Process data based on type (counter 1-7 from ESP32)
     */
    private function processDataByType($device, $data)
    {
        $keys = array_keys($data);

        // Helper to check if any key starts with prefix
        $hasPrefix = function ($prefix) use ($keys) {
            foreach ($keys as $key) {
                if (str_starts_with($key, $prefix))
                    return true;
            }
            return false;
        };

        // Counter 2 & 3: Schedule Data (sch*)
        if ($hasPrefix('sch')) {
            $this->logScheduleData($device, $data);
            return;
        }

        // Counter 6: Status Output (sts_*)
        // Check for sts_ prefix OR known status keys
        if ($hasPrefix('sts_')) {
            $this->logStatusData($device, $data);
            return;
        }

        // Counter 1: Sensor Data (ni_*, rainfall, etc)
        // Check if typical sensor keys exist
        if ($hasPrefix('ni_') || isset($data['rainfall']) || isset($data['soil_moisture'])) {
            $this->saveSensorData($device, $data);
            return;
        }

        // Counter 4: Threshold/Batas Data (bts_*)
        if ($hasPrefix('bts_')) {
            $this->logThresholdData($device, $data);
            return;
        }

        // Counter 5: Mode Data (mode_*)
        if ($hasPrefix('mode_')) {
            $this->logModeData($device, $data);
            return;
        }

        // Counter 7: Time (waktu)
        if (isset($data['waktu'])) {
            $this->logTimeData($device, $data);
            return;
        }

        // Unknown data type - try legacy sensor format as fallback
        $this->saveSensorData($device, $data);
    }

    /**
     * Save sensor data to database (Counter 1)
     * Uses hardcoded mapping for ESP32 keys to database columns
     */
    private function saveSensorData($device, $data)
    {
        $this->info("           📊 Type: SENSOR DATA");

        $tableName = $device->table_name;

        if (!\Schema::hasTable($tableName)) {
            $this->warn("           ⚠️  Table {$tableName} does not exist");
            return;
        }

        // Hardcoded mapping: ESP32 key => DB column
        // Tambahkan mapping baru disini jika ada sensor baru
        $keyMapping = [
            // === SENSOR DATA (Counter 1 dari ESP32) ===
            // Primary sensors
            'ni_PH' => 'ni_PH',
            'ni_EC' => 'ni_EC',
            'ni_TDS' => 'ni_TDS',
            'ni_LUX' => 'ni_LUX',
            'ni_SUHU' => 'ni_SUHU',
            'ni_KELEM' => 'ni_KELEM',

            // Weather sensors
            'rainfall' => 'rainfall',
            'wind_speed' => 'wind_speed',
            'wind_direction' => 'wind_direction',
            'pressure' => 'pressure',
            'uv_index' => 'uv_index',

            // Soil sensors
            'soil_moisture' => 'soil_moisture',
            'soil_temperature' => 'soil_temperature',
            'soil_ph' => 'soil_ph',

            // Other sensors
            'water_level' => 'water_level',
            'co2' => 'co2',

            // === ALIAS untuk multiple sensor (tambahkan angka di belakang) ===
            'ni_SUHU_2' => 'ni_SUHU_2',
            'ni_KELEM_2' => 'ni_KELEM_2',
            'ni_PH_2' => 'ni_PH_2',
            'ni_EC_2' => 'ni_EC_2',
            'ni_TDS_2' => 'ni_TDS_2',
            'ni_LUX_2' => 'ni_LUX_2',

            // === Legacy/Alternative keys (jika ESP32 pakai format lain) ===
            'temperature' => 'ni_SUHU',
            'temperature_1' => 'ni_SUHU',
            'temperature_2' => 'ni_SUHU_2',
            'humidity' => 'ni_KELEM',
            'humidity_1' => 'ni_KELEM',
            'humidity_2' => 'ni_KELEM_2',
            'ph' => 'ni_PH',
            'ec' => 'ni_EC',
            'tds' => 'ni_TDS',
            'light' => 'ni_LUX',
            'light_intensity' => 'ni_LUX',
            'lux' => 'ni_LUX',
        ];

        $insertData = ['recorded_at' => now()];

        // Cek setiap key di data, mapping ke DB column
        foreach ($data as $espKey => $value) {
            // Cari mapping, jika tidak ada pakai key asli
            $dbColumn = $keyMapping[$espKey] ?? $espKey;

            // Cek apakah kolom ada di tabel
            if (\Schema::hasColumn($tableName, $dbColumn)) {
                $insertData[$dbColumn] = (float) $value;
                $this->line("           • {$espKey} → {$dbColumn}: {$value}");
            }
        }

        // Only insert if we have data beyond recorded_at
        if (count($insertData) > 1) {
            DB::table($tableName)->insert($insertData);
            $this->info("           ✅ Sensor data saved to {$tableName}");
        } else {
            $this->warn("           ⚠️  No matching sensor data found");
            $this->line("           Available keys in data: " . implode(', ', array_keys($data)));
            $this->line("           Available mappings: " . implode(', ', array_keys($keyMapping)));
        }
    }

    /**
     * Log schedule data (Counter 2 & 3) - parse and save to database
     */
    private function logScheduleData($device, $data)
    {
        $this->info("           📅 Type: SCHEDULE DATA");

        $savedCount = 0;

        // Process sch1 through sch14
        for ($i = 1; $i <= 14; $i++) {
            $key = "sch{$i}";
            if (isset($data[$key])) {
                $rawValue = $data[$key];

                // Parse using model method
                $parsed = \App\Models\DeviceScheduleData::parseFromDevice($rawValue);

                // Save or update
                \App\Models\DeviceScheduleData::updateOrCreate(
                    [
                        'device_id' => $device->id,
                        'slot_key' => $key,
                    ],
                    [
                        'on_time' => $parsed['on_time'],
                        'duration' => $parsed['duration'],
                        'sector' => $parsed['sector'],
                        'name' => $parsed['name'],
                        'days' => $parsed['days'],
                        'is_active' => $parsed['is_active'],
                    ]
                );

                if ($parsed['is_active']) {
                    $this->line("           • {$key}: {$parsed['name']} @ {$parsed['on_time']} ({$parsed['duration']}min, Sektor {$parsed['sector']})");
                    $savedCount++;
                }
            }
        }

        if ($savedCount > 0) {
            $this->info("           ✅ Saved {$savedCount} active schedules to database");
        } else {
            $this->info("           ✅ Schedule received (no active schedules)");
        }
    }

    /**
     * Log threshold data (Counter 4) - display only
     */
    private function logThresholdData($device, $data)
    {
        $this->info("           ⚙️ Type: THRESHOLD DATA");

        $thresholds = [];
        $keys = [
            'bts_ats_suhu',
            'bts_bwh_suhu',
            'bts_ats_kelem',
            'bts_bwh_kelem',
            'bts_ats_ph',
            'bts_bwh_ph',
            'bts_ats_tds',
            'bts_bwh_tds'
        ];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $thresholds[$key] = $data[$key];
            }
        }

        $this->line("           Thresholds: " . json_encode($thresholds));
        $this->info("           ✅ Threshold received (device is master)");
    }

    /**
     * Log mode data (Counter 5)
     */
    private function logModeData($device, $data)
    {
        $this->info("           🎛️ Type: MODE DATA");

        $dosing = isset($data['mode_dos']) ? ($data['mode_dos'] ? 'ON' : 'OFF') : 'N/A';
        $climate = isset($data['mode_clim']) ? ($data['mode_clim'] ? 'ON' : 'OFF') : 'N/A';

        $this->line("           Mode Dosing: {$dosing}");
        $this->line("           Mode Climate: {$climate}");
        $this->info("           ✅ Mode received");
    }

    /**
     * Log status output data (Counter 6)
     */
    /**
     * Log status output data (Counter 6)
     */
    private function logStatusData($device, $data)
    {
        $this->info("           🔌 Type: STATUS OUTPUT");

        $updatesCount = 0;

        // Iterate any key starting with sts_
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'sts_')) {
                $label = ucwords(str_replace('sts_', '', str_replace('_', ' ', $key)));

                // Allow integer/boolean values
                $status = $value ? "🟢 ON" : "🔴 OFF";
                $this->line("           • {$label} ({$key}): {$status}");

                // Save to Database
                $output = \App\Models\DeviceOutput::where('device_id', $device->id)
                    ->where('output_name', $key)
                    ->first();

                if ($output) {
                    $output->current_value = (float) $value;
                    $output->save();
                    $updatesCount++;
                }
            }
        }

        if ($updatesCount > 0) {
            $this->info("           ✅ Updated {$updatesCount} outputs in database");
        } else {
            $this->info("           ✅ Status received (No DB updates)");
        }
    }

    /**
     * Log time data (Counter 7)
     */
    private function logTimeData($device, $data)
    {
        $this->info("           🕐 Type: TIME DATA");

        $waktu = $data['waktu'];
        // Try to format as datetime if it's a timestamp
        if (is_numeric($waktu) && $waktu > 1000000000) {
            $formatted = date('Y-m-d H:i:s', $waktu);
            $this->line("           Device Time: {$formatted} (ts: {$waktu})");
        } else {
            $this->line("           Device Time: {$waktu}");
        }

        $this->info("           ✅ Time received");
    }


    /**
     * Process device status message (device-as-master architecture)
     * Device sends its output states and schedules, web only displays
     */
    private function processDeviceStatus($topic, $message)
    {
        $timestamp = now()->format('H:i:s');
        $this->line("");
        $this->line("[{$timestamp}] 🔔 Device Status Received!");
        $this->line("           Topic: {$topic}");

        try {
            $data = json_decode($message, true);

            if (!$data) {
                $this->warn("           ⚠️  Invalid JSON format");
                return;
            }

            $token = $data['token'] ?? 'unknown';

            // Find device by token
            $device = Device::where('token', $token)->first();
            $deviceName = $device ? $device->name : "Unknown Device";

            $this->info("           📱 Device: {$deviceName} ({$token})");

            // Display outputs
            if (isset($data['outputs']) && is_array($data['outputs'])) {
                $this->line("           📊 Output States:");
                foreach ($data['outputs'] as $name => $output) {
                    $value = is_array($output) ? ($output['value'] ?? 0) : $output;
                    $label = is_array($output) ? ($output['label'] ?? $name) : $name;
                    $status = $value ? "ON 🟢" : "OFF 🔴";
                    $this->line("              • {$label}: {$status}");
                }
            }

            // Display sensor values
            if (isset($data['sensors']) && is_array($data['sensors'])) {
                $this->line("           🌡️ Sensor Values:");
                foreach ($data['sensors'] as $name => $value) {
                    $this->line("              • {$name}: {$value}");
                }
            }

            // Display schedules count
            if (isset($data['schedules']) && is_array($data['schedules'])) {
                $count = count($data['schedules']);
                $enabled = count(array_filter($data['schedules'], fn($s) => $s['enabled'] ?? true));
                $this->line("           📅 Schedules: {$count} total, {$enabled} enabled");
            }

            $this->info("           ✅ Status received (not saved - device is master)");

        } catch (\Exception $e) {
            $this->error("           ❌ Error: " . $e->getMessage());
            Log::error('MQTT Device Status Error: ' . $e->getMessage());
        }
    }
}
