<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Device;
use App\Models\UserDevice;
use App\Models\DeviceSensor;
use App\Models\DeviceOutput;
use App\Models\DeviceSchedule;
use App\Models\User;

class AllSensorsDeviceSeeder extends Seeder
{
    public function run(): void
    {
        // Temukan user pertama (anggap ID 1 atau minimal ada 1 user)
        $user = User::first();
        if (!$user) {
            $this->command->error("Tidak ada user di database, silakan buat user terlebih dahulu.");
            return;
        }

        // Bersihkan device demo yang lama jika ada
        $existing = Device::where('mac_address', '00:11:22:33:44:55')->first();
        if ($existing) {
            UserDevice::where('device_id', $existing->id)->delete();
            DeviceSensor::where('device_id', $existing->id)->delete();
            DeviceOutput::where('device_id', $existing->id)->delete();
            DeviceSchedule::where('device_id', $existing->id)->delete();
            $existing->delete();
        }

        // 1. Buat Device
        $device = Device::create([
            'name' => 'Demo Super Device',
            'mac_address' => '00:11:22:33:44:55',
            'type' => 'aws',
            'token' => Str::random(32),
            'status' => 'online',
            'last_seen_at' => now(),
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'location' => 'Greenhouse Sentral Demo',
            'mqtt_topic' => 'swrtni/demo_super',
            'max_time_schedules' => 10,
            'max_sensor_automations' => 10,
        ]);

        // 2. Pair Device ke User (UserDevice)
        UserDevice::create([
            'user_id' => $user->id,
            'device_id' => $device->id,
            'custom_name' => 'Demo Semua Sensor & Output',
        ]);

        // 3. Tambah Sensors (Semua tipe yang ada warna logo di view monitoring)
        $sensorsData = [
            ['sensor_name' => 'temp1', 'sensor_label' => 'Suhu Udara', 'unit' => '°C'],
            ['sensor_name' => 'hum1', 'sensor_label' => 'Kelembapan Udara', 'unit' => '%'],
            ['sensor_name' => 'rain', 'sensor_label' => 'Curah Hujan', 'unit' => 'mm'],
            ['sensor_name' => 'wind_speed', 'sensor_label' => 'Kecepatan Angin', 'unit' => 'km/h'],
            ['sensor_name' => 'wind_dir', 'sensor_label' => 'Arah Angin', 'unit' => '°'],
            ['sensor_name' => 'press', 'sensor_label' => 'Tekanan Udara', 'unit' => 'hPa'],
            ['sensor_name' => 'uv', 'sensor_label' => 'Indeks UV', 'unit' => ''],
            ['sensor_name' => 'light', 'sensor_label' => 'Intensitas Cahaya', 'unit' => 'lux'],
            ['sensor_name' => 'soil_hum', 'sensor_label' => 'Kelembaban Tanah', 'unit' => '%'],
            ['sensor_name' => 'soil_temp', 'sensor_label' => 'Suhu Tanah', 'unit' => '°C'],
            ['sensor_name' => 'water_lvl', 'sensor_label' => 'Level Air', 'unit' => 'cm'],
            ['sensor_name' => 'co2', 'sensor_label' => 'Kadar CO2', 'unit' => 'ppm'],
            ['sensor_name' => 'ec', 'sensor_label' => 'Electrical Conductivity', 'unit' => 'mS/cm'],
            ['sensor_name' => 'tds', 'sensor_label' => 'TDS Nutrisi', 'unit' => 'ppm'],
            ['sensor_name' => 'ph_meter', 'sensor_label' => 'pH Meter', 'unit' => 'pH'],
        ];

        foreach ($sensorsData as $s) {
            DeviceSensor::create(array_merge($s, ['device_id' => $device->id]));
        }

        // 4. Tambah Outputs (Semua yang ada logo khusus di view monitoring)
        $outputsData = [
            ['output_name' => 'pump_air', 'output_label' => 'Pompa Air Utama', 'output_type' => 'boolean'],
            ['output_name' => 'dosing_ab', 'output_label' => 'Dosing Mix Nutrisi AB', 'output_type' => 'boolean'],
            ['output_name' => 'ph_up', 'output_label' => 'Pompa pH Up', 'output_type' => 'boolean'],
            ['output_name' => 'fan_exhaust', 'output_label' => 'Exhaust Fan Kipas', 'output_type' => 'boolean'],
            ['output_name' => 'grow_light', 'output_label' => 'LED Grow Light', 'output_type' => 'boolean'],
            ['output_name' => 'mist_maker', 'output_label' => 'Sistem Pengkabutan', 'output_type' => 'boolean'],
            ['output_name' => 'heater', 'output_label' => 'Pemanas Ruangan', 'output_type' => 'boolean'],
            ['output_name' => 'valve_1', 'output_label' => 'Katup Irigasi 1', 'output_type' => 'boolean'],
            ['output_name' => 'irrigation', 'output_label' => 'Pompa Irigasi Utama', 'output_type' => 'multi_zone', 'max_sectors' => 4],
            ['output_name' => 'st_bak', 'output_label' => 'Status Air Baku', 'output_type' => 'boolean', 'current_value' => 1],
            ['output_name' => 'st_ppk', 'output_label' => 'Status Air Pupuk', 'output_type' => 'boolean', 'current_value' => 1],
        ];

        foreach ($outputsData as $o) {
            DeviceOutput::create(array_merge($o, ['device_id' => $device->id]));
        }

        // 5. Tambah Schedule Config utk Irigasi
        DeviceSchedule::create([
            'device_id' => $device->id,
            'schedule_mode' => 'duration_days_sector_type',
            'max_slots' => 14,
            'max_sectors' => 4,
            'output_key' => 'irrigation'
        ]);

        $this->command->info("Demo Super Device dengan SEMUA sensor dan output berhasil dibuat!");
    }
}
