<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActivityController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::where('user_id', Auth::id())
            ->whereIn('action', ['device_control', 'pump_control', 'irrigation_control'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('riwayat.index', compact('logs'));
    }
}
