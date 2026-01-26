<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttScheduleService
{
    private $host;
    private $port;
    private $username;
    private $password;

    public function __construct()
    {
        $this->host = config('mqtt.host', env('MQTT_HOST', 'smartagri.web.id'));
        $this->port = config('mqtt.port', env('MQTT_PORT', 1883));
        $this->username = config('mqtt.username', env('MQTT_USERNAME'));
        $this->password = config('mqtt.password', env('MQTT_PASSWORD'));
    }

    /**
     * Send time-based schedules to device
     * 
     * @param string $mqttTopic MQTT topic dari device (dari Admin Panel)
     * @param string $deviceToken Token device untuk identifikasi
     * @param string $outputName Nama output yang dijadwalkan
     * @param array $schedules Array jadwal
     */
    public function sendTimeSchedules(string $mqttTopic, string $deviceToken, string $outputName, array $schedules): bool
    {
        try {
            $mqtt = $this->connect();
            // Gunakan mqtt_topic dari device + /pub
            $topic = rtrim($mqttTopic, '/') . '/sub';

            $message = json_encode([
                'type' => 'time_schedule',
                'token' => $deviceToken,
                'output' => $outputName,
                'schedules' => $schedules,
                'timestamp' => now()->toIso8601String(),
            ]);

            $mqtt->publish($topic, $message, 1); // QoS 1
            $mqtt->disconnect();

            Log::info("Time schedules sent to device via {$topic}", [
                'token' => $deviceToken,
                'output' => $outputName,
                'schedules_count' => count($schedules),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send schedules via MQTT: " . $e->getMessage(), [
                'mqtt_topic' => $mqttTopic,
                'output' => $outputName,
            ]);
            return false;
        }
    }

    /**
     * Send single time-based schedule to device
     * Format ESP32: <sch{id}#{hour}#{minute}#{duration}#{sector}#{type}#{days}>
     * 
     * Example: <sch1#8#30#15#2#1#0111110>
     * - sch1 = Slot 1
     * - 8 = Hour (08:xx)
     * - 30 = Minute (xx:30)
     * - 15 = Duration in minutes
     * - 2 = Sector
     * - 1 = Type (1=BAKU, 2=PUPUK, 3=DRAIN)
     * - 0111110 = Days (Sun-Sat, 0=off, 1=on)
     * 
     * @param string $mqttTopic MQTT topic dari device (dari Admin Panel)
     * @param string $deviceToken Token device untuk identifikasi
     * @param string $outputName Nama output/jenis (BAKU, PUPUK, DRAIN)
     * @param array $schedule Single jadwal (id, on, off, days?, sector?, duration?)
     * @param string $automationMode Mode automation (time, time_days, time_days_sector)
     */
    public function sendSingleTimeSchedule(string $mqttTopic, string $deviceToken, string $outputName, array $schedule, string $automationMode = 'time'): bool
    {
        try {
            $mqtt = $this->connect();
            $topic = rtrim($mqttTopic, '/') . '/sub';

            // Parse time (HH:MM) into hour and minute
            $timeParts = explode(':', $schedule['on']);
            $hour = (int) ($timeParts[0] ?? 0);
            $minute = (int) ($timeParts[1] ?? 0);

            // Calculate duration from on/off time if not provided
            $duration = $schedule['duration'] ?? 5;
            if (!isset($schedule['duration']) && isset($schedule['off'])) {
                $onTime = \Carbon\Carbon::createFromFormat('H:i', $schedule['on']);
                $offTime = \Carbon\Carbon::createFromFormat('H:i', $schedule['off']);
                $duration = $onTime->diffInMinutes($offTime);
                if ($duration < 0)
                    $duration += 1440; // Handle overnight
            }

            // Get sector (default 1)
            $sector = $schedule['sector'] ?? 1;

            // Map type to numeric value: BAKU=1, PUPUK=2, DRAIN=3
            $typeMap = ['BAKU' => 1, 'PUPUK' => 2, 'DRAIN' => 3];
            $typeNum = $typeMap[strtoupper($outputName)] ?? 1;

            // Convert days to binary format (Sun-Sat: 0111110 = Mon-Fri)
            // Input: "12345" or "1234567" (1=Mon, 7=Sun) or already binary
            $daysInput = $schedule['days'] ?? '1234567';
            $daysBinary = $this->convertDaysToBinary($daysInput);

            // Build message: <sch{id}#{hour}#{minute}#{duration}#{sector}#{type}#{days}>
            $message = sprintf(
                '<sch%d#%d#%d#%d#%d#%d#%s>',
                $schedule['id'],
                $hour,
                $minute,
                $duration,
                $sector,
                $typeNum,
                $daysBinary
            );

            $mqtt->publish($topic, $message, 1); // QoS 1
            $mqtt->disconnect();

            Log::info("Schedule sent to device via {$topic}", [
                'message' => $message,
                'slot_id' => $schedule['id'],
                'type' => $outputName,
                'mode' => $automationMode,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send schedule via MQTT: " . $e->getMessage(), [
                'mqtt_topic' => $mqttTopic,
                'output' => $outputName,
            ]);
            return false;
        }
    }

    /**
     * Convert days format to binary (Sun-Sat)
     * Input: "12345" (1=Mon...7=Sun) or "1234567" 
     * Output: "0111110" (Sun-Sat, Mon-Fri active)
     */
    private function convertDaysToBinary(string $days): string
    {
        // If already binary format (7 chars of 0/1)
        if (preg_match('/^[01]{7}$/', $days)) {
            return $days;
        }

        // Convert numeric days (1=Mon...7=Sun) to binary (Sun-Sat)
        // Binary positions: [Sun, Mon, Tue, Wed, Thu, Fri, Sat] = [7, 1, 2, 3, 4, 5, 6]
        $binary = ['0', '0', '0', '0', '0', '0', '0']; // Sun-Sat

        for ($i = 0; $i < strlen($days); $i++) {
            $day = (int) $days[$i];
            if ($day >= 1 && $day <= 7) {
                // Map: 1(Mon)->index 1, 2(Tue)->2, ..., 6(Sat)->6, 7(Sun)->0
                $binaryIndex = ($day == 7) ? 0 : $day;
                $binary[$binaryIndex] = '1';
            }
        }

        return implode('', $binary);
    }

    /**
     * Send sensor-based rule to device
     * Format: <set#output#operator#threshold>
     * Contoh: <set#pump#>#30>
     * 
     * @param string $mqttTopic MQTT topic dari device (dari Admin Panel)
     * @param string $deviceToken Token device untuk identifikasi
     * @param string $outputName Nama output
     * @param array $rule Aturan sensor (operator, threshold)
     */
    public function sendSensorRule(string $mqttTopic, string $deviceToken, string $outputName, array $rule): bool
    {
        try {
            $mqtt = $this->connect();
            $topic = rtrim($mqttTopic, '/') . '/pub';

            // Format simple: <set#output#operator#threshold>
            $message = sprintf(
                '<set#%s#%s#%s>',
                $outputName,
                $rule['operator'],
                $rule['threshold']
            );

            $mqtt->publish($topic, $message, 1);
            $mqtt->disconnect();

            Log::info("Sensor rule sent to device via {$topic}", [
                'message' => $message,
                'output' => $outputName,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send sensor rule via MQTT: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Request current status from device
     * 
     * @param string $mqttTopic MQTT topic dari device
     * @param string $deviceToken Token device
     */
    public function requestStatus(string $mqttTopic, string $deviceToken): bool
    {
        try {
            $mqtt = $this->connect();
            $topic = env('MQTT_TOPIC_PUB', '/smartgh01/pub');

            $message = json_encode([
                'type' => 'status_request',
                'token' => $deviceToken,
                'timestamp' => now()->toIso8601String(),
            ]);

            $mqtt->publish($topic, $message, 1);
            $mqtt->disconnect();

            Log::info("Status request sent to topic {$topic}");

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to request status via MQTT: " . $e->getMessage());
            return false;
        }
    }

    private function connect(): MqttClient
    {
        $connectionSettings = new ConnectionSettings();

        if ($this->username && $this->password) {
            $connectionSettings = $connectionSettings
                ->setUsername($this->username)
                ->setPassword($this->password);
        }

        $connectionSettings = $connectionSettings
            ->setKeepAliveInterval(60)
            ->setConnectTimeout(10);

        $mqtt = new MqttClient($this->host, $this->port, 'laravel-schedule-' . uniqid());
        $mqtt->connect($connectionSettings, true);

        return $mqtt;
    }
}
