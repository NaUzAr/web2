<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Admin: show all activity logs
     */
    public function index(Request $request)
    {
        $this->checkAdmin();

        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->action) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(30);

        // Get distinct actions for filter dropdown
        $actions = ActivityLog::distinct()->pluck('action')->sort();

        return view('admin.activity_logs', compact('logs', 'actions'));
    }

    private function checkAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Halaman ini khusus Admin.');
        }
    }
}
