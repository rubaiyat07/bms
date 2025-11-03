<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $query = Project::with(['branch', 'creator', 'manager', 'members', 'tasks']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by branch
        if ($request->has('branch_id') && $request->branch_id !== '') {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by manager
        if ($request->has('manager_id') && $request->manager_id !== '') {
            $query->where('manager_id', $request->manager_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== '') {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Search by title or description
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $projects = $query->paginate(15);

        // Add calculated fields to each project
        $projects->through(function ($project) {
            $project->tasks_count = $project->tasks->count();
            $project->completed_tasks_count = $project->tasks->where('status', 'completed')->count();
            $project->progress_percentage = $project->progress_percentage;
            $project->performance_score = $project->performance_score;
            $project->overdue_days = $project->overdue_days;
            $project->team_efficiency = $project->team_efficiency;
            $project->priority = $project->priority;
            $project->performance_remark = $project->performance_remark;
            return $project;
        });

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'budget' => 'required|numeric|min:0',
            'spent' => 'sometimes|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $project = Project::create([
                'branch_id' => $request->branch_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
                'budget' => $request->budget,
                'spent' => $request->spent ?? 0,
                'created_by' => $request->user()->id,
            ]);

            // Log the creation
            $this->logAudit($request->user(), 'create', 'projects', $project->id, 'Project created');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project created successfully',
                'data' => $project->load('branch', 'creator')
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create project'
            ], 500);
        }
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        return response()->json([
            'success' => true,
            'data' => $project->load(['branch', 'creator', 'transactions', 'inventoryItems'])
        ]);
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'sometimes|required|exists:branches,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'sometimes|required|in:pending,in_progress,completed,cancelled',
            'budget' => 'sometimes|required|numeric|min:0',
            'spent' => 'sometimes|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldValues = $project->only(['branch_id', 'title', 'description', 'start_date', 'end_date', 'status', 'budget', 'spent']);

        DB::beginTransaction();
        try {
            $project->update($request->all());

            // Log the update
            $this->logAudit(
                $request->user(),
                'update',
                'projects',
                $project->id,
                'Project updated',
                $oldValues,
                $project->only(['branch_id', 'title', 'description', 'start_date', 'end_date', 'status', 'budget', 'spent'])
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully',
                'data' => $project->load('branch', 'creator')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update project'
            ], 500);
        }
    }

    /**
     * Remove the specified project
     */
    public function destroy(Request $request, Project $project)
    {
        // Check if project has transactions
        if ($project->transactions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete project with associated transactions'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $project->delete();

            // Log the deletion
            $this->logAudit($request->user(), 'delete', 'projects', $project->id, 'Project deleted');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete project'
            ], 500);
        }
    }

    /**
     * Complete a project
     */
    public function complete(Request $request, Project $project)
    {
        if ($project->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Project is already completed'
            ], 422);
        }

        $oldStatus = $project->status;
        $project->update(['status' => 'completed']);

        // Log the completion
        $this->logAudit(
            $request->user(),
            'complete',
            'projects',
            $project->id,
            'Project completed',
            ['status' => $oldStatus],
            ['status' => 'completed']
        );

        return response()->json([
            'success' => true,
            'message' => 'Project completed successfully',
            'data' => $project
        ]);
    }

    /**
     * Cancel a project
     */
    public function cancel(Request $request, Project $project)
    {
        if ($project->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Project is already cancelled'
            ], 422);
        }

        $oldStatus = $project->status;
        $project->update(['status' => 'cancelled']);

        // Log the cancellation
        $this->logAudit(
            $request->user(),
            'cancel',
            'projects',
            $project->id,
            'Project cancelled',
            ['status' => $oldStatus],
            ['status' => 'cancelled']
        );

        return response()->json([
            'success' => true,
            'message' => 'Project cancelled successfully',
            'data' => $project
        ]);
    }

    /**
     * Helper method to log audit
     */
    private function logAudit($user, $action, $table, $recordId, $description, $oldValues = null, $newValues = null)
    {
        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'table_name' => $table,
            'record_id' => $recordId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
