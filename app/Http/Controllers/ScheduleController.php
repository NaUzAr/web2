<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\UserDevice;
use App\Models\DeviceOutput;
use App\Services\MqttScheduleService;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    private $mqttService;

    public function __construct(MqttScheduleService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    /**
     * Show schedule management page for specific device
     */
    public function index($userDeviceId)
    {
        $userDevice = UserDevice::where('id', $userDeviceId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $device = $userDevice->device;

        // Check if device has schedule functionality
        $scheduleConfig = \App\Models\DeviceSchedule::where('device_id', $device->id)->first();

        if (!$scheduleConfig) {
            return redirect()->route('monitoring.show', $userDevice->id)
                ->with('error', 'Device ini tidak memiliki konfigurasi penjadwalan.');
        }

        return view('schedule.index', compact('userDevice', 'device', 'scheduleConfig'));
    }

    /**
     * Send schedule to device
     * Supports modes: time, time_days, time_days_sector, time_duration, time_days_duration
     */
    public function storeTimeSchedules(Request $request, $userDeviceId)
    {
        $userDevice = UserDevice::where('id', $userDeviceId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $device = $userDevice->device;
        $scheduleConfig = \App\Models\DeviceSchedule::where('device_id', $device->id)->firstOrFail();

        // Validation rules based on mode
        $rules = [
            'slot_id' => 'required|integer|min:1',
            'on_time' => 'required|date_format:H:i',
        ];

        // Determine if we need off_time or duration
        $isDurationMode = str_contains($scheduleConfig->schedule_mode, 'duration');

        if ($isDurationMode) {
            $rules['duration'] = 'required|integer|min:1|max:1440'; // Max 24 hours
        } else {
            $rules['off_time'] = 'required|date_format:H:i';
        }

        if (str_contains($scheduleConfig->schedule_mode, 'days')) {
            $rules['days'] = 'nullable|string|max:7';
        }

        if (str_contains($scheduleConfig->schedule_mode, 'sector')) {
            $rules['sector'] = 'nullable|integer|min:1';
        }

        $validated = $request->validate($rules);

        // Prepare schedule payload
        $onTime = $validated['on_time'];
        $offTime = null;

        if ($isDurationMode) {
            // Calculate off_time based on duration
            // Note: This logic assumes simple same-day calculation or wrapping
            // For now, simpler implementation: PHP calculates end time string
            $startTime = \Carbon\Carbon::createFromFormat('H:i', $onTime);
            $endTime = $startTime->copy()->addMinutes((int) $validated['duration']);
            $offTime = $endTime->format('H:i');
        } else {
            $offTime = $validated['off_time'];
        }

        $schedule = [
            'id' => $validated['slot_id'],
            'on' => $onTime,
            'off' => $offTime,
        ];

        if (isset($validated['days'])) {
            $schedule['days'] = $validated['days'];
        }

        if (isset($validated['sector'])) {
            $schedule['sector'] = $validated['sector'];
        }

        // Send to MQTT
        // Note: output_key from config is used as the target output name
        $success = $this->mqttService->sendSingleTimeSchedule(
            $device->mqtt_topic,
            $device->token,
            $scheduleConfig->output_key ?? 'general', // fallback if empty
            $schedule,
            $scheduleConfig->schedule_mode
        );

        if ($success) {
            $msg = 'Jadwal slot ' . $validated['slot_id'] . ' berhasil dikirim!';
            if ($isDurationMode) {
                $msg .= " (Selesai pukul {$offTime})";
            }

            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim jadwal ke device via MQTT.',
        ], 500);
    }

    // storeSensorRule removed for now as per route update or kept if needed but updated
    // Keeping it commented or empty to avoid errors if called, 
    // but routes commented it out. I will just omit it for now or return error 
    // to strictly clean up.
}
