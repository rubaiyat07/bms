<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with(['targetBranch', 'postedBy']);

        // Filter by branch
        if ($request->has('branch_id')) {
            $query->where('target_branch_id', $request->branch_id);
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        // Only show active announcements that haven't expired
        $query->where('is_active', true)
              ->where(function ($q) {
                  $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
              });

        $announcements = $query->orderBy('priority', 'desc')
                               ->orderBy('created_at', 'desc')
                               ->paginate(15);

        return response()->json($announcements);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'target_branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean',
            'priority' => 'required|in:low,medium,high,urgent',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $announcement = Announcement::create([
            'title' => $request->title,
            'body' => $request->body,
            'target_branch_id' => $request->target_branch_id,
            'posted_by' => Auth::id(),
            'is_active' => $request->is_active ?? true,
            'priority' => $request->priority,
            'expires_at' => $request->expires_at,
        ]);

        return response()->json($announcement->load(['targetBranch', 'postedBy']), 201);
    }

    public function show(Announcement $announcement)
    {
        return response()->json($announcement->load(['targetBranch', 'postedBy']));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'target_branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $announcement->update($request->all());

        return response()->json($announcement->load(['targetBranch', 'postedBy']));
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted successfully']);
    }

    public function activeAnnouncements(Request $request)
    {
        $query = Announcement::with(['targetBranch', 'postedBy'])
                            ->where('is_active', true)
                            ->where(function ($q) {
                                $q->whereNull('expires_at')
                                  ->orWhere('expires_at', '>', now());
                            });

        // Filter by branch if specified
        if ($request->has('branch_id')) {
            $query->where(function ($q) use ($request) {
                $q->whereNull('target_branch_id')
                  ->orWhere('target_branch_id', $request->branch_id);
            });
        }

        $announcements = $query->orderBy('priority', 'desc')
                               ->orderBy('created_at', 'desc')
                               ->get();

        return response()->json($announcements);
    }
}
