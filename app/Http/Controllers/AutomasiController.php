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

        // Load all settings needed for display
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

        return view('automasi.index', compact('device', 'hasClimate', 'hasFertilizer', 'settings'));
    }

    public function fertilizer($deviceId)
    {
        $device = $this->getDevice($deviceId);

        // Load existing settings
        $settings = DeviceSetting::where('device_id', $deviceId)
            ->whereIn('key', ['ats_tds', 'bwh_tds', 'ats_ph', 'bwh_ph'])
            ->pluck('value', 'key')
            ->toArray();

        return view('automasi.fertilizer', compact('device', 'settings'));
    }

    public function storeFertilizer(Request $request, $deviceId)
    {
        $device = $this->getDevice($deviceId);

        $data = $request->validate([
            'ats_tds' => 'required|numeric',
            'bwh_tds' => 'required|numeric',
            'ats_ph' => 'required|numeric',
            'bwh_ph' => 'required|numeric',
        ]);

        // Save to DB
        foreach ($data as $key => $value) {
            DeviceSetting::updateOrCreate(
                ['device_id' => $device->id, 'key' => $key],
                ['value' => $value]
            );
        }

        // Send to MQTT
        $this->mqttService->sendCustomAutomationConfig($device->mqtt_topic, $device->token, $data);

        return redirect()->route('automasi.fertilizer', $device->id)
            ->with('success', 'Setting pemupukan berhasil dikirim!');
    }

    public function climate($deviceId)
    {
        $device = $this->getDevice($deviceId);

        // Load existing settings
        $settings = DeviceSetting::where('device_id', $deviceId)
            ->whereIn('key', ['ats_suhu', 'bwh_suhu', 'ats_kelem', 'bwh_kelem'])
            ->pluck('value', 'key')
            ->toArray();

        return view('automasi.climate', compact('device', 'settings'));
    }

    public function storeClimate(Request $request, $deviceId)
    {
        $device = $this->getDevice($deviceId);

        $data = $request->validate([
            'ats_suhu' => 'required|numeric',
            'bwh_suhu' => 'required|numeric',
            'ats_kelem' => 'required|numeric',
            'bwh_kelem' => 'required|numeric',
        ]);

        // Save to DB
        foreach ($data as $key => $value) {
            DeviceSetting::updateOrCreate(
                ['device_id' => $device->id, 'key' => $key],
                ['value' => $value]
            );
        }

        // Send to MQTT
        $this->mqttService->sendCustomAutomationConfig($device->mqtt_topic, $device->token, $data);

        return redirect()->route('automasi.climate', $device->id)
            ->with('success', 'Setting climate berhasil dikirim!');
    }
}
