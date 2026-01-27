<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceSetting;
use App\Models\UserDevice;
use App\Services\MqttAutomationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutomasiController extends Controller
{
    protected $mqttService;

    public function __construct(MqttAutomationService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    private function getDevice($userDeviceId)
    {
        $userDevice = UserDevice::where('user_id', Auth::id())
            ->where('id', $userDeviceId)
            ->firstOrFail();

        return $userDevice->device;
    }

    public function index($deviceId)
    {
        $device = $this->getDevice($deviceId);

        $hasClimate = $device->hasAutomationType('climate');
        $hasFertilizer = $device->hasAutomationType('fertilizer');

        // Load all settings from Cache
        $cacheKey = "device_automation_{$device->id}";
        $settings = \Cache::get($cacheKey, []);

        // Fallback: If cache invalid/empty, load from DB and reset cache
        if (empty($settings)) {
            $settings = DeviceSetting::where('device_id', $device->id)
                ->whereIn('key', [
                    'ats_suhu',
                    'bwh_suhu',
                    'ats_kelem',
                    'bwh_kelem',
                    'ats_tds',
                    'bwh_tds',
                    'ats_ph',
                    'bwh_ph'
                ])
                ->pluck('value', 'key')
                ->toArray();
            \Cache::put($cacheKey, $settings, now()->addDays(1));
        }

        return view('automasi.index', compact('device', 'hasClimate', 'hasFertilizer', 'settings'));
    }

    public function updateSingle(Request $request, $deviceId)
    {
        $device = $this->getDevice($deviceId);

        $validated = $request->validate([
            'sensor_type' => 'required|in:suhu,kelem,tds,ph',
            'ats_val' => 'required|numeric',
            'bwh_val' => 'required|numeric'
        ]);

        $sensorType = $validated['sensor_type'];
        $atsKey = "ats_{$sensorType}";
        $bwhKey = "bwh_{$sensorType}";

        $newData = [
            $atsKey => (float) $validated['ats_val'],
            $bwhKey => (float) $validated['bwh_val']
        ];

        // 1. Update Cache
        $cacheKey = "device_automation_{$device->id}";
        $currentSettings = \Cache::get($cacheKey, []);
        $updatedSettings = array_merge($currentSettings, $newData);
        \Cache::put($cacheKey, $updatedSettings, now()->addDays(1));

        // 2. Publish to MQTT
        $this->mqttService->sendCustomAutomationConfig($device->mqtt_topic, $device->token, $newData);

        // 3. Update DB (As Backup/Persistence) - Optional but recommended for reboot persistence
        foreach ($newData as $key => $value) {
            DeviceSetting::updateOrCreate(
                ['device_id' => $device->id, 'key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', "Setting {$sensorType} berhasil diperbarui!");
    }

}
