<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')
            ->orderByRaw("CASE status 
                WHEN 'open' THEN 1 
                WHEN 'in_progress' THEN 2 
                WHEN 'resolved' THEN 3 
                WHEN 'closed' THEN 4 
                ELSE 5 END")
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'replies.user']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_reply' => 'nullable|string',
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        if ($request->filled('admin_reply')) {
            $ticket->replies()->create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'message' => $request->admin_reply,
            ]);
        }

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'Tiket berhasil diupdate.');
    }
}
