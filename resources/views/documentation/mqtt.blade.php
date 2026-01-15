<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MQTT Protocol Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/atom-one-dark.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #22c55e;
            --nature-gradient: linear-gradient(135deg, #134e4a 0%, #166534 50%, #14532d 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--nature-gradient);
            min-height: 100vh;
            padding: 2rem 0;
            color: #fff;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            color: #fff;
        }

        h2 {
            color: var(--primary-green);
            margin-top: 1.5rem;
        }

        h4 {
            color: #86efac;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .table {
            color: rgba(255, 255, 255, 0.9);
        }

        .table thead th {
            background: rgba(20, 83, 45, 0.8);
            color: #86efac;
            border-bottom: 1px solid var(--glass-border);
        }

        .table tbody td {
            border-bottom: 1px solid var(--glass-border);
        }

        pre {
            border-radius: 12px;
        }

        .alert-warning-custom {
            background: rgba(250, 204, 21, 0.2);
            border: 1px solid rgba(250, 204, 21, 0.3);
            color: #fde047;
            border-radius: 12px;
        }

        .alert-info-custom {
            background: rgba(14, 165, 233, 0.2);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: #7dd3fc;
            border-radius: 12px;
        }

        .alert-success-custom {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            border-radius: 12px;
        }

        .badge-counter {
            background: var(--primary-green);
            color: #000;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .data-type-card {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-green);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="glass-card">
            <h1><i class="bi bi-wifi me-2"></i>MQTT Protocol Documentation</h1>
            <p class="text-white-50">SmartAgri IoT - Format Data ESP32 ke Web Application</p>
        </div>

        <!-- Format Wrapper -->
        <div class="glass-card">
            <h2><i class="bi bi-code-slash me-2"></i>Format Wrapper Data</h2>

            <div class="alert alert-warning-custom mb-4">
                <h5><i class="bi bi-exclamation-triangle me-2"></i>FORMAT PENTING!</h5>
                <p class="mb-0">Semua data dari ESP32 dikirim dengan wrapper: <code>&lt;dat|{JSON}|&gt;</code></p>
            </div>

            <pre><code class="language-cpp">// Format pengiriman dari ESP32
payload1 = ("<dat|" + payload + "|");</code></pre>

            <p class="text-white-50 mt-3">Contoh payload lengkap:</p>
            <pre><code class="language-text">&lt;dat|{"ni_PH":6.8,"ni_EC":1200,"ni_TDS":850,"ni_LUX":1500,"ni_SUHU":28.5,"ni_KELEM":65}|&gt;</code></pre>
        </div>

        <!-- Data Types Overview -->
        <div class="glass-card">
            <h2><i class="bi bi-list-ol me-2"></i>7 Jenis Data (Counter Send)</h2>
            <p class="text-white-50">ESP32 mengirim data secara bergantian berdasarkan counter:</p>

            <table class="table">
                <thead>
                    <tr>
                        <th>Counter</th>
                        <th>Jenis Data</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge-counter">1</span></td>
                        <td>Sensor Data</td>
                        <td>PH, EC, TDS, LUX, Suhu, Kelembaban</td>
                    </tr>
                    <tr>
                        <td><span class="badge-counter">2</span></td>
                        <td>Schedule 1-7</td>
                        <td>Jadwal 1 sampai 7</td>
                    </tr>
                    <tr>
                        <td><span class="badge-counter">3</span></td>
                        <td>Schedule 8-14</td>
                        <td>Jadwal 8 sampai 14</td>
                    </tr>
                    <tr>
                        <td><span class="badge-counter">4</span></td>
                        <td>Threshold/Batas</td>
                        <td>Batas atas & bawah sensor untuk automation</td>
                    </tr>
                    <tr>
                        <td><span class="badge-counter">5</span></td>
                        <td>Mode</td>
                        <td>Mode dosing & climate control</td>
                    </tr>
                    <tr>
                        <td><span class="badge-counter">6</span></td>
                        <td>Status Output</td>
                        <td>Status semua relay/output</td>
                    </tr>
                    <tr>
                        <td><span class="badge-counter">7</span></td>
                        <td>Waktu</td>
                        <td>Timestamp dari device</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Counter 1: Sensor Data -->
        <div class="glass-card">
            <h2><i class="bi bi-thermometer-half me-2"></i><span class="badge-counter">1</span> Data Sensor</h2>
            <p class="text-white-50">Pembacaan sensor real-time</p>

            <div class="data-type-card">
                <pre><code class="language-json">{
    "ni_PH": 6.8,
    "ni_EC": 1200,
    "ni_TDS": 850,
    "ni_LUX": 1500,
    "ni_SUHU": 28.5,
    "ni_KELEM": 65
}</code></pre>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Deskripsi</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>ni_PH</code></td>
                        <td>Nilai pH air</td>
                        <td>pH (0-14)</td>
                    </tr>
                    <tr>
                        <td><code>ni_EC</code></td>
                        <td>Electrical Conductivity</td>
                        <td>µS/cm</td>
                    </tr>
                    <tr>
                        <td><code>ni_TDS</code></td>
                        <td>Total Dissolved Solids</td>
                        <td>ppm</td>
                    </tr>
                    <tr>
                        <td><code>ni_LUX</code></td>
                        <td>Intensitas cahaya</td>
                        <td>lux</td>
                    </tr>
                    <tr>
                        <td><code>ni_SUHU</code></td>
                        <td>Suhu udara</td>
                        <td>°C</td>
                    </tr>
                    <tr>
                        <td><code>ni_KELEM</code></td>
                        <td>Kelembaban udara</td>
                        <td>%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Counter 2 & 3: Schedule -->
        <div class="glass-card">
            <h2><i class="bi bi-calendar-check me-2"></i><span class="badge-counter">2</span><span
                    class="badge-counter">3</span> Data Jadwal (Schedule)</h2>
            <p class="text-white-50">Konfigurasi 14 jadwal output</p>

            <h4>Counter 2 - Jadwal 1-7</h4>
            <div class="data-type-card">
                <pre><code class="language-json">{
    "sch1": 60600,
    "sch2": 61800,
    "sch3": 63000,
    "sch4": 64200,
    "sch5": 65400,
    "sch6": 66600,
    "sch7": 67800
}</code></pre>
            </div>

            <h4>Counter 3 - Jadwal 8-14</h4>
            <div class="data-type-card">
                <pre><code class="language-json">{
    "sch8": 69000,
    "sch9": 70200,
    "sch10": 71400,
    "sch11": 72600,
    "sch12": 73800,
    "sch13": 75000,
    "sch14": 76200
}</code></pre>
            </div>

            <div class="alert alert-info-custom mt-3">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Format Jadwal:</strong> Nilai jadwal dalam format encoded (biasanya detik dari midnight atau
                format custom)
            </div>
        </div>

        <!-- Counter 4: Threshold -->
        <div class="glass-card">
            <h2><i class="bi bi-sliders me-2"></i><span class="badge-counter">4</span> Batas Threshold (Automation)</h2>
            <p class="text-white-50">Batas atas & bawah untuk automation sensor</p>

            <div class="data-type-card">
                <pre><code class="language-json">{
    "bts_ats_suhu": 35,
    "bts_bwh_suhu": 20,
    "bts_ats_kelem": 80,
    "bts_bwh_kelem": 40,
    "bts_ats_ph": 7.5,
    "bts_bwh_ph": 5.5,
    "bts_ats_tds": 1500,
    "bts_bwh_tds": 500
}</code></pre>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>bts_ats_suhu</code></td>
                        <td>Batas ATAS suhu (trigger kipas ON)</td>
                    </tr>
                    <tr>
                        <td><code>bts_bwh_suhu</code></td>
                        <td>Batas BAWAH suhu (trigger pemanas ON)</td>
                    </tr>
                    <tr>
                        <td><code>bts_ats_kelem</code></td>
                        <td>Batas ATAS kelembaban</td>
                    </tr>
                    <tr>
                        <td><code>bts_bwh_kelem</code></td>
                        <td>Batas BAWAH kelembaban (trigger misting)</td>
                    </tr>
                    <tr>
                        <td><code>bts_ats_ph</code></td>
                        <td>Batas ATAS pH (trigger pH down)</td>
                    </tr>
                    <tr>
                        <td><code>bts_bwh_ph</code></td>
                        <td>Batas BAWAH pH (trigger pH up)</td>
                    </tr>
                    <tr>
                        <td><code>bts_ats_tds</code></td>
                        <td>Batas ATAS TDS</td>
                    </tr>
                    <tr>
                        <td><code>bts_bwh_tds</code></td>
                        <td>Batas BAWAH TDS (trigger dosing)</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Counter 5: Mode -->
        <div class="glass-card">
            <h2><i class="bi bi-gear me-2"></i><span class="badge-counter">5</span> Mode Operasi</h2>
            <p class="text-white-50">Mode dosing dan climate control</p>

            <div class="data-type-card">
                <pre><code class="language-json">{
    "mode_dos": 1,
    "mode_clim": 1
}</code></pre>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Nilai</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>mode_dos</code></td>
                        <td>0 = OFF, 1 = ON</td>
                        <td>Mode Dosing (nutrisi otomatis)</td>
                    </tr>
                    <tr>
                        <td><code>mode_clim</code></td>
                        <td>0 = OFF, 1 = ON</td>
                        <td>Mode Climate Control (suhu/kelembaban)</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Counter 6: Status Output -->
        <div class="glass-card">
            <h2><i class="bi bi-toggles me-2"></i><span class="badge-counter">6</span> Status Output</h2>
            <p class="text-white-50">Status semua relay/output device</p>

            <div class="data-type-card">
                <pre><code class="language-json">{
    "sts_air_input": 0,
    "sts_mixing": 1,
    "sts_pompa": 1,
    "sts_fan": 0,
    "sts_misting": 0,
    "sts_lampu": 1,
    "sts_dosing": 0,
    "sts_ph_up": 0,
    "sts_air_baku": 1,
    "sts_air_pupuk": 0,
    "sts_ph_down": 0
}</code></pre>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Deskripsi</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>sts_air_input</code></td>
                        <td>Valve air masuk</td>
                        <td>0=OFF, 1=ON</td>
                    </tr>
                    <tr>
                        <td><code>sts_mixing</code></td>
                        <td>Mixer nutrisi</td>
                        <td>0=OFF, 1=ON</td>
                    </tr>
                    <tr>
                        <td><code>sts_pompa</code></td>
                        <td>Pompa utama</td>
                        <td>0=OFF, 1=ON</td>
                    </tr>
                    <tr>
                        <td><code>sts_fan</code></td>
                        <td>Kipas/exhaust</td>
                        <td>0=OFF, 1=ON</td>
                    </tr>
                    <tr>
                        <td><code>sts_misting</code></td>
                        <td>Sistem misting</td>
                        <td>0=OFF, 1=ON</td>
                    </tr>
                    <tr>
                        <td><code>sts_lampu</code></td>
                        <td>Lampu grow light</td>
                        <td>0=OFF, 1=ON</td>
                    </tr>
                    <tr>
                        <td><code>sts_dosing</code></td>
                        <td>Pompa dosing nutrisi</td>
                        <td>0=OFF, 1=ON</td>
                    </tr>
                    <tr>
                        <td><code>sts_ph_up</code></td>
                        <td>Pompa pH Up</td>
                        <td>0=OFF, 1=ON</td>
                    </tr>
                    <tr>
                        <td><code>sts_air_baku</code></td>
                        <td>Sensor air baku</td>
                        <td>0=KOSONG, 1=ADA</td>
                    </tr>
                    <tr>
                        <td><code>sts_air_pupuk</code></td>
                        <td>Sensor air pupuk</td>
                        <td>0=KOSONG, 1=ADA</td>
                    </tr>
                    <tr>
                        <td><code>sts_ph_down</code></td>
                        <td>Pompa pH Down</td>
                        <td>0=OFF, 1=ON</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Counter 7: Time -->
        <div class="glass-card">
            <h2><i class="bi bi-clock me-2"></i><span class="badge-counter">7</span> Waktu Device</h2>
            <p class="text-white-50">Timestamp dari RTC device</p>

            <div class="data-type-card">
                <pre><code class="language-json">{
    "waktu": 1705296000
}</code></pre>
            </div>

            <div class="alert alert-info-custom">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Format:</strong> Unix timestamp (detik sejak 1 Jan 1970) atau format custom sesuai kebutuhan
            </div>
        </div>

        <!-- ESP32 Code Example -->
        <div class="glass-card">
            <h2><i class="bi bi-cpu me-2"></i>Contoh Kode ESP32</h2>
            <p class="text-white-50">Fungsi untuk mengirim data sensor:</p>

            <pre><code class="language-cpp">void sendSensorData() {
    String payload = "{";
    payload += "\"ni_PH\":"; payload += PH1_; payload += ",";
    payload += "\"ni_EC\":"; payload += EC1; payload += ",";
    payload += "\"ni_TDS\":"; payload += TDS1; payload += ",";
    payload += "\"ni_LUX\":"; payload += luxAve; payload += ",";
    payload += "\"ni_SUHU\":"; payload += suhuAve; payload += ",";
    payload += "\"ni_KELEM\":"; payload += kelemAve;
    payload += "}";
    
    String payload1 = "&lt;dat|" + payload + "|&gt;";
    
    // Kirim via MQTT
    mqttClient.publish(mqtt_topic, payload1.c_str());
}</code></pre>
        </div>

        <!-- Web Processing -->
        <div class="glass-card">
            <h2><i class="bi bi-server me-2"></i>Pemrosesan di Web (Laravel)</h2>
            <p class="text-white-50">MqttListener memproses data dengan format wrapper</p>

            <pre><code class="language-php">// MqttListener.php
public function processMessage($topic, $message)
{
    // Extract JSON dari wrapper &lt;dat|{...}|&gt;
    if (preg_match('/&lt;dat\|(.*?)\|&gt;/', $message, $matches)) {
        $jsonData = $matches[1];
        $data = json_decode($jsonData, true);
        
        // Process based on data type
        if (isset($data['ni_PH'])) {
            // Sensor data
            $this->saveSensorData($data);
        } elseif (isset($data['sch1'])) {
            // Schedule data
            $this->saveScheduleData($data);
        } elseif (isset($data['bts_ats_suhu'])) {
            // Threshold data
            $this->saveThresholdData($data);
        } elseif (isset($data['sts_pompa'])) {
            // Status output
            $this->updateOutputStatus($data);
        }
    }
}</code></pre>
        </div>

        <!-- Control Format (Web to Device) -->
        <div class="glass-card">
            <h2><i class="bi bi-arrow-right-circle me-2"></i>Format Kontrol (Web → Device)</h2>
            <p class="text-white-50">Web mengirim perintah ke topic <code>{mqtt_topic}/control</code></p>

            <h4>Manual Control Output</h4>
            <div class="data-type-card">
                <pre><code class="language-json">{
    "type": "manual_control",
    "output": "sts_pompa",
    "value": 1
}</code></pre>
            </div>

            <h4>Update Threshold</h4>
            <div class="data-type-card">
                <pre><code class="language-json">{
    "type": "threshold",
    "bts_ats_suhu": 35,
    "bts_bwh_suhu": 20
}</code></pre>
            </div>

            <h4>Update Schedule</h4>
            <div class="data-type-card">
                <pre><code class="language-json">{
    "type": "schedule",
    "sch1": 60600,
    "sch2": 61800
}</code></pre>
            </div>
        </div>

        <!-- Testing -->
        <div class="glass-card">
            <h2><i class="bi bi-terminal me-2"></i>Testing</h2>

            <h5>1. Jalankan MQTT Listener</h5>
            <pre><code class="language-bash">php artisan mqtt:listen --host=broker.hivemq.com</code></pre>

            <h5 class="mt-3">2. Kirim Data Test (Python)</h5>
            <pre><code class="language-python">import paho.mqtt.client as mqtt
import json

client = mqtt.Client()
client.connect("broker.hivemq.com", 1883)

# Format data sensor
data = {"ni_PH": 6.8, "ni_EC": 1200, "ni_TDS": 850, "ni_LUX": 1500, "ni_SUHU": 28.5, "ni_KELEM": 65}
payload = "&lt;dat|" + json.dumps(data) + "|&gt;"

client.publish("smartagri/device1", payload)
print("Sent:", payload)</code></pre>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('documentation.esp32') }}" class="btn btn-outline-light">
                <i class="bi bi-cpu me-1"></i> ESP32 Setup Guide
            </a>
            <a href="{{ route('admin.devices.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Admin
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script>hljs.highlightAll();</script>
</body>

</html>