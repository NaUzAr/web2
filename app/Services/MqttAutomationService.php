<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttAutomationService
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
     * Send automation config to device
     * 
     * @param string $mqttTopic MQTT topic dari device (dari Admin Panel)
     * @param string $deviceToken Token device untuk identifikasi
     * @param array $configs Array konfigurasi automation
     */
    public function sendAutomationConfig(string $mqttTopic, string $deviceToken, array $configs): bool
    {
        try {
            $mqtt = $this->connect();
            $topic = rtrim($mqttTopic, '/') . '/sub';

            $configPayloads = array_map(function ($config) {
                return is_object($config) ? $config->toMqttPayload() : $config;
            }, $configs);

            $message = json_encode([
                'type' => 'automation_config',
                'token' => $deviceToken,
                'configs' => $configPayloads,
                'timestamp' => now()->toIso8601String(),
            ]);

            $mqtt->publish($topic, $message, 1);
            $mqtt->disconnect();

            Log::info("Automation config sent to topic {$topic}", [
                'token' => $deviceToken,
                'configs_count' => count($configs),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send automation config via MQTT: " . $e->getMessage(), [
                'mqtt_topic' => $mqttTopic,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Delete automation config from device
     * 
     * @param string $mqttTopic MQTT topic dari device
     * @param string $deviceToken Token device
     * @param int $configId ID konfigurasi yang akan dihapus
     */
    public function deleteAutomationConfig(string $mqttTopic, string $deviceToken, int $configId): bool
    {
        try {
            $mqtt = $this->connect();
            $topic = rtrim($mqttTopic, '/') . '/sub';

            $message = json_encode([
                'type' => 'automation_delete',
                'token' => $deviceToken,
                'config_id' => $configId,
                'timestamp' => now()->toIso8601String(),
            ]);

            $mqtt->publish($topic, $message, 1);
            $mqtt->disconnect();

            Log::info("Automation delete command sent to topic {$topic}", [
                'config_id' => $configId,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send delete command via MQTT: " . $e->getMessage(), [
                'mqtt_topic' => $mqttTopic,
                'config_id' => $configId,
            ]);
            return false;
        }
    }

    /**
     * Request device status
     * 
     * @param string $mqttTopic MQTT topic dari device
     * @param string $deviceToken Token device
     */
    public function requestDeviceStatus(string $mqttTopic, string $deviceToken): bool
    {
        try {
            $mqtt = $this->connect();
            $topic = rtrim($mqttTopic, '/') . '/sub';

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
            Log::error("Failed to request device status via MQTT: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send custom automation settings (key-value pairs) with specific string format
     */
    public function sendCustomAutomationConfig(string $mqttTopic, string $deviceToken, array $settings): bool
    {
        try {
            $mqtt = $this->connect();
            $topic = rtrim($mqttTopic, '/') . '/sub';

            $message = '';

            // Determine format based on keys
            if (isset($settings['ats_tds']) && isset($settings['bwh_tds'])) {
                // Fertilizer (TDS): <PPKAUT#ats#bwh#>
                $message = "<PPKAUT#" . $settings['ats_tds'] . "#" . $settings['bwh_tds'] . "#>";
            } elseif (isset($settings['ats_ph']) && isset($settings['bwh_ph'])) {
                // pH: <PPKPH#ats#bwh#>
                $message = "<PPKPH#" . $settings['ats_ph'] . "#" . $settings['bwh_ph'] . "#>";
            } elseif (isset($settings['ats_suhu']) && isset($settings['bwh_suhu'])) {
                // Fan (Temperature): <SUH#ats#bwh#>
                $message = "<SUH#" . $settings['ats_suhu'] . "#" . $settings['bwh_suhu'] . "#>";
            } elseif (isset($settings['ats_kelem']) && isset($settings['bwh_kelem'])) {
                // Misting (Humidity): <KEL#ats#bwh#>
                $message = "<KEL#" . $settings['ats_kelem'] . "#" . $settings['bwh_kelem'] . "#>";
            } else {
                Log::warning("Unknown automation setting keys: " . json_encode(array_keys($settings)));
                return false;
            }

            $mqtt->publish($topic, $message, 1);
            $mqtt->disconnect();

            Log::info("Custom automation settings sent to topic {$topic}: {$message}", [
                'token' => $deviceToken,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send custom automation settings: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send output control command to device
     * 
     * @param string $mqttTopic MQTT topic dari device
     * @param string $deviceToken Token device
     * @param string $outputName Nama output yang dikontrol
     * @param mixed $value Nilai output (true/false atau angka)
     */
    public function sendOutputControl(string $mqttTopic, string $deviceToken, string $outputName, $value): bool
    {
        try {
            $mqtt = $this->connect();
            $topic = rtrim($mqttTopic, '/') . '/sub';

            // Custom format based on output name
            $val = $value ? '1' : '0';
            $name = strtolower($outputName);

            // 1. Specific Pumps (Dosing & pH)
            if (str_contains($name, 'pump_ab') || str_contains($name, 'dosing')) {
                $message = "<pmpAB#{$val}#>";
            } elseif (str_contains($name, 'ph_up') || str_contains($name, 'ph1')) {
                $message = "<pmpPH#{$val}#>";
            } elseif (str_contains($name, 'ph_down') || str_contains($name, 'ph2')) {
                $message = "<pmpPH2#{$val}#>";
            }
            // 2. Main Pump (Pompa Utama / Irigasi)
            elseif (str_contains($name, 'pompa') || str_contains($name, 'pump')) {
                // Special case for main pump
                if ($value) {
                    // Format: <PMP_ON#waterType#zone#>
                    $message = "<PMP_ON#0#0#>";
                } else {
                    $message = "<PMP_OFF#>";
                }
            }
            // 3. Components
            elseif (str_contains($name, 'air_input')) {
                $message = "<AIR#{$val}#>";
            } elseif (str_contains($name, 'mix')) {
                $message = "<MIX#{$val}#>";
            } elseif (str_contains($name, 'fan')) {
                $message = "<FAN#{$val}#>";
            } elseif (str_contains($name, 'mist')) {
                $message = "<MIS#{$val}#>";
            } elseif (str_contains($name, 'lamp')) {
                $message = "<LAM#{$val}#>";
            }
            // 4. Fallback
            else {
                // Generic fallback
                $message = sprintf('<%s#%s#>', $outputName, $val);
            }

            $mqtt->publish($topic, $message, 1);
            $mqtt->disconnect();

            Log::info("Output control sent to topic {$topic}", [
                'output' => $outputName,
                'value' => $value,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send output control via MQTT: " . $e->getMessage());
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

        $mqtt = new MqttClient($this->host, $this->port, 'laravel-automation-' . uniqid());
        $mqtt->connect($connectionSettings, true);

        return $mqtt;
    }
}
