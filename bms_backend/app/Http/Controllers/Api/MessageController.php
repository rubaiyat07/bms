<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::with(['sender', 'receiver', 'branch']);

        // Filter messages based on user role
        if ($request->has('type')) {
            if ($request->type === 'sent') {
                $query->where('sender_id', Auth::id());
            } elseif ($request->type === 'received') {
                $query->where('receiver_id', Auth::id());
            }
        } else {
            $query->where(function ($q) {
                $q->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
            });
        }

        // Filter by read status
        if ($request->has('read')) {
            $query->where('is_read', $request->boolean('read'));
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'branch_id' => 'nullable|exists:branches,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'branch_id' => $request->branch_id,
            'subject' => $request->subject,
            'content' => $request->content,
            'is_read' => false,
        ]);

        return response()->json($message->load(['sender', 'receiver', 'branch']), 201);
    }

    public function show(Message $message)
    {
        // Check if user can view this message
        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Mark as read if receiver is viewing
        if ($message->receiver_id === Auth::id() && !$message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json($message->load(['sender', 'receiver', 'branch']));
    }

    public function update(Request $request, Message $message)
    {
        // Only sender can update
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message->update($request->only(['subject', 'content']));

        return response()->json($message->load(['sender', 'receiver', 'branch']));
    }

    public function destroy(Message $message)
    {
        // Only sender can delete
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->delete();

        return response()->json(['message' => 'Message deleted successfully']);
    }

    public function markAsRead(Request $request, Message $message)
    {
        // Only receiver can mark as read
        if ($message->receiver_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json($message->load(['sender', 'receiver', 'branch']));
    }

    public function unreadCount(Request $request)
    {
        $count = Message::where('receiver_id', Auth::id())
                       ->where('is_read', false)
                       ->count();

        return response()->json(['unread_count' => $count]);
    }
}
