<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $tickets
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('tickets', 'public');
        }

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'description' => $request->description,
            'attachment_path' => $path,
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan tiket berhasil dibuat.',
            'data' => $ticket
        ], 201);
    }

    public function show($id)
    {
        $ticket = Ticket::with(['replies.user'])->where('user_id', Auth::id())->find($id);
        
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $ticket
        ]);
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::where('user_id', Auth::id())->find($id);
        
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('tickets', 'public');
        }

        $reply = $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachment_path' => $path,
        ]);

        if ($ticket->status !== 'open') {
            $ticket->update(['status' => 'open']);
        }

        $reply->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil dikirim.',
            'data' => $reply
        ], 201);
    }
}
