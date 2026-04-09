<?php

namespace App\Http\Controllers;

use App\Models\GlobalSensorRule;
use App\Models\Device;
use Illuminate\Http\Request;

class AdminSensorRuleController extends Controller
{
    public function index()
    {
        $rules = GlobalSensorRule::all();
        $availableSensors = Device::getAvailableSensors();
        return view('admin.sensor_rules.index', compact('rules', 'availableSensors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sensor_key' => 'required|string|unique:global_sensor_rules',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
        ]);

        GlobalSensorRule::create([
            'sensor_key' => $request->sensor_key,
            'min_value' => $request->min_value,
            'max_value' => $request->max_value,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Aturan sensor berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $rule = GlobalSensorRule::findOrFail($id);
        
        $request->validate([
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
        ]);

        $rule->update([
            'min_value' => $request->min_value,
            'max_value' => $request->max_value,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Aturan sensor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        GlobalSensorRule::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Aturan dihapus.');
    }

    public function testNotification(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->fcm_token) {
            return redirect()->back()->withErrors(['Akun web Anda saat ini belum tertaut dengan FCM Token dari Flutter. Silakan login ke aplikasi HP menggunakan akun ini terlebih dahulu agar HP Anda memancarkan token ke server!']);
        }

        $firebase = app(\App\Services\FirebaseService::class);
        $title = "🔔 Test Swaratani FCM";
        $body = "Hore! Sistem notifikasi ke HP Anda dari server Laravel bekerja dengan sangat sempurna!";
        
        $success = $firebase->sendToToken($user->fcm_token, $title, $body);

        if ($success) {
            return redirect()->back()->with('success', 'Ping dikirim! Coba periksa notifikasi di HP Anda sekarang.');
        } else {
            return redirect()->back()->withErrors(['Gagal mengirim notifikasi. Pastikan file firebase-adminsdk.json benar dan terbaca oleh sistem.']);
        }
    }
}
