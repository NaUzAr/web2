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
        $host = $this->option('host');
        $port = (int) $this->option('port');
        $username = $this->option('username');
        $password = $this->option('password');

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

            // Get all devices and subscribe to their topics
            $devices = Device::all();

            if ($devices->isEmpty()) {
                $this->warn("⚠️  No devices found. Create devices first via admin panel.");
                $this->info("   Listening for wildcard topic: sensor/#");

                $mqtt->subscribe('sensor/#', function ($topic, $message) {
                    $this->processMessage($topic, $message);
                }, 0);
            } else {
                foreach ($devices as $device) {
                    $topic = $device->mqtt_topic;
                    $this->info("📡 Subscribed to: {$topic}");

                    $mqtt->subscribe($topic, function ($topic, $message) {
                        $this->processMessage($topic, $message);
                    }, 0);
                }

                // Also subscribe to wildcard for new devices
                $mqtt->subscribe('sensor/#', function ($topic, $message) {
                    $this->processMessage($topic, $message);
                }, 0);
            }

            // Subscribe to device status topic (device-as-master)
            $this->info("📡 Subscribed to: devices/+/status (Device Status)");
            $mqtt->subscribe('devices/+/status', function ($topic, $message) {
                $this->processDeviceStatus($topic, $message);
            }, 1);

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
            // Extract JSON from wrapper <dat|{JSON}|>
            $data = null;
            if (preg_match('/<dat\|(.*?)\|>/', $message, $matches)) {
                $jsonString = $matches[1];
                $data = json_decode($jsonString, true);
                $this->line("           Parsed: {$jsonString}");
            } else {
                // Fallback: try direct JSON parse
                $data = json_decode($message, true);
            }

            if (!$data) {
                $this->warn("           ⚠️  Invalid data format");
                return;
            }

            // Cari device berdasarkan topic ATAU token
            $device = Device::where('mqtt_topic', $topic)->first();

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
    private function processDataByType($device, $data)
    {
        // Counter 1: Sensor Data (ni_PH, ni_EC, ni_TDS, ni_LUX, ni_SUHU, ni_KELEM)
        if (isset($data['ni_PH']) || isset($data['ni_SUHU']) || isset($data['ni_EC'])) {
            $this->saveSensorData($device, $data);
            return;
        }

        // Counter 2 & 3: Schedule Data (sch1-sch14)
        if (isset($data['sch1']) || isset($data['sch8'])) {
            $this->logScheduleData($device, $data);
            return;
        }

        // Counter 4: Threshold/Batas Data (bts_ats_*, bts_bwh_*)
        if (isset($data['bts_ats_suhu']) || isset($data['bts_bwh_suhu'])) {
            $this->logThresholdData($device, $data);
            return;
        }

        // Counter 5: Mode Data (mode_dos, mode_clim)
        if (isset($data['mode_dos']) || isset($data['mode_clim'])) {
            $this->logModeData($device, $data);
            return;
        }

        // Counter 6: Status Output (sts_*)
        if (isset($data['sts_pompa']) || isset($data['sts_fan']) || isset($data['sts_air_input'])) {
            $this->logStatusData($device, $data);
            return;
        }

        // Counter 7: Time (waktu)
        if (isset($data['waktu'])) {
            $this->logTimeData($device, $data);
            return;
        }

        // Unknown data type - try legacy sensor format
        $this->saveSensorData($device, $data);
    }

    /**
     * Save sensor data to database (Counter 1)
     * Uses mqtt_key from device_sensors for flexible ESP32 key mapping
     */
    private function saveSensorData($device, $data)
    {
        $this->info("           📊 Type: SENSOR DATA");

        $tableName = $device->table_name;

        if (!\Schema::hasTable($tableName)) {
            $this->warn("           ⚠️  Table {$tableName} does not exist");
            return;
        }

        $insertData = ['recorded_at' => now()];

        // Get sensor columns from device config and match by mqtt_key
        $sensors = $device->sensors;
        foreach ($sensors as $sensor) {
            // mqtt_key adalah key yang dikirim dari ESP32 (contoh: ni_PH)
            // sensor_name adalah nama kolom di database (contoh: ph atau ni_PH)
            $mqttKey = $sensor->mqtt_key ?? $sensor->sensor_name;
            $dbColumn = $sensor->sensor_name;

            if (isset($data[$mqttKey])) {
                $insertData[$dbColumn] = (float) $data[$mqttKey];
                $this->line("           • {$mqttKey} → {$dbColumn}: " . $data[$mqttKey]);
            }
        }

        // Only insert if we have data beyond recorded_at
        if (count($insertData) > 1) {
            DB::table($tableName)->insert($insertData);
            $this->info("           ✅ Sensor data saved to {$tableName}");
        } else {
            $this->warn("           ⚠️  No matching sensor data found");
            $this->line("           Available keys in data: " . implode(', ', array_keys($data)));
        }
    }

    /**
     * Log schedule data (Counter 2 & 3) - display only, device is master
     */
    private function logScheduleData($device, $data)
    {
        $this->info("           📅 Type: SCHEDULE DATA");

        $schedules = [];
        for ($i = 1; $i <= 14; $i++) {
            $key = "sch{$i}";
            if (isset($data[$key])) {
                $schedules[$key] = $data[$key];
            }
        }

        $this->line("           Schedules: " . json_encode($schedules));
        $this->info("           ✅ Schedule received (device is master)");
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
    private function logStatusData($device, $data)
    {
        $this->info("           🔌 Type: STATUS OUTPUT");

        $outputs = [
            'sts_air_input' => 'Air Input',
            'sts_mixing' => 'Mixing',
            'sts_pompa' => 'Pompa',
            'sts_fan' => 'Fan',
            'sts_misting' => 'Misting',
            'sts_lampu' => 'Lampu',
            'sts_dosing' => 'Dosing',
            'sts_ph_up' => 'pH Up',
            'sts_air_baku' => 'Air Baku',
            'sts_air_pupuk' => 'Air Pupuk',
            'sts_ph_down' => 'pH Down',
        ];

        foreach ($outputs as $key => $label) {
            if (isset($data[$key])) {
                $status = $data[$key] ? "🟢 ON" : "🔴 OFF";
                $this->line("           • {$label}: {$status}");
            }
        }

        $this->info("           ✅ Status received");
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
