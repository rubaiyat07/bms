<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Branch;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index(Request $request)
    {
        $query = Department::with(['branch', 'manager', 'users']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by branch
        if ($request->has('branch_id') && $request->branch_id !== '') {
            $query->where('branch_id', $request->branch_id);
        }

        // Search by name or code
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $departments = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $departments
        ]);
    }

    /**
     * Store a newly created department
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments',
            'description' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive,suspended'
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
            $department = Department::create($request->all());

            // Log the creation
            $this->logAudit($request->user(), 'create', 'departments', $department->id, 'Department created');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully',
                'data' => $department->load('branch', 'manager')
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create department'
            ], 500);
        }
    }

    /**
     * Display the specified department
     */
    public function show(Department $department)
    {
        return response()->json([
            'success' => true,
            'data' => $department->load(['branch', 'manager', 'users', 'projects'])
        ]);
    }

    /**
     * Update the specified department
     */
    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'branch_id' => 'sometimes|required|exists:branches,id',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'sometimes|required|in:active,inactive,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldValues = $department->only(['name', 'code', 'description', 'branch_id', 'manager_id', 'status']);

        DB::beginTransaction();
        try {
            $department->update($request->all());

            // Log the update
            $this->logAudit(
                $request->user(),
                'update',
                'departments',
                $department->id,
                'Department updated',
                $oldValues,
                $department->only(['name', 'code', 'description', 'branch_id', 'manager_id', 'status'])
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully',
                'data' => $department->load('branch', 'manager')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update department'
            ], 500);
        }
    }

    /**
     * Remove the specified department
     */
    public function destroy(Request $request, Department $department)
    {
        DB::beginTransaction();
        try {
            $department->delete();

            // Log the deletion
            $this->logAudit($request->user(), 'delete', 'departments', $department->id, 'Department deleted');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete department'
            ], 500);
        }
    }

    /**
     * Activate a department
     */
    public function activate(Request $request, Department $department)
    {
        $oldStatus = $department->status;
        $department->update(['status' => 'active']);

        // Log the activation
        $this->logAudit(
            $request->user(),
            'activate',
            'departments',
            $department->id,
            'Department activated',
            ['status' => $oldStatus],
            ['status' => 'active']
        );

        return response()->json([
            'success' => true,
            'message' => 'Department activated successfully',
            'data' => $department
        ]);
    }

    /**
     * Deactivate a department
     */
    public function deactivate(Request $request, Department $department)
    {
        $oldStatus = $department->status;
        $department->update(['status' => 'inactive']);

        // Log the deactivation
        $this->logAudit(
            $request->user(),
            'deactivate',
            'departments',
            $department->id,
            'Department deactivated',
            ['status' => $oldStatus],
            ['status' => 'inactive']
        );

        return response()->json([
            'success' => true,
            'message' => 'Department deactivated successfully',
            'data' => $department
        ]);
    }

    /**
     * Get departments by branch
     */
    public function getByBranch(Branch $branch)
    {
        $departments = $branch->departments()->active()->get();

        return response()->json([
            'success' => true,
            'data' => $departments
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
