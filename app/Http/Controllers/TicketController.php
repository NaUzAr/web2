<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('tickets', 'public');
        }

        Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'description' => $request->description,
            'attachment_path' => $path,
            'status' => 'open',
        ]);

        return redirect()->route('tickets.index')->with('success', 'Laporan tiket berhasil dibuat. Admin akan segera merespon.');
    }

    public function show(Ticket $ticket)
    {
        // Ensure user owns ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['replies.user']);

        // Check if admin has replied (any reply not by user)
        $hasAdminReplied = $ticket->replies()->where('user_id', '!=', Auth::id())->exists();

        return view('tickets.show', compact('ticket', 'hasAdminReplied'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        // Ensure user owns ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('tickets', 'public');
        }

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachment_path' => $path,
        ]);

        // Status reverts to open, or wait for admin
        if ($ticket->status !== 'open') {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Balasan berhasil dikirim.');
    }
}
