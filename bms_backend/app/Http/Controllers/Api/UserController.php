<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of users/employees
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'branch', 'manager']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by branch
        if ($request->has('branch_id') && $request->branch_id !== '') {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by role/designation
        if ($request->has('designation') && $request->designation !== '') {
            $query->where('designation', $request->designation);
        }

        // Filter by performance rating
        if ($request->has('performance_rating') && $request->performance_rating !== '') {
            $query->where('performance_rating', $request->performance_rating);
        }

        // Filter by join date range
        if ($request->has('join_date_from') && $request->join_date_from !== '') {
            $query->where('join_date', '>=', $request->join_date_from);
        }
        if ($request->has('join_date_to') && $request->join_date_to !== '') {
            $query->where('join_date', '<=', $request->join_date_to);
        }

        // Search by name, email, or employee_id
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $users = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|unique:users',
            'designation' => 'nullable|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'manager_id' => 'nullable|exists:users,id',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,suspended,on_leave',
            'performance_rating' => 'nullable|in:weak,average,good,excellent',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'salary' => 'nullable|numeric|min:0',
            'grade' => 'nullable|string|max:50',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:roles,id'
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
            // Generate unique employee ID if not provided
            $employeeId = $request->employee_id ?: $this->generateEmployeeId();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'employee_id' => $employeeId,
                'designation' => $request->designation,
                'branch_id' => $request->branch_id,
                'manager_id' => $request->manager_id,
                'join_date' => $request->join_date,
                'status' => $request->status,
                'performance_rating' => $request->performance_rating ?? 'average',
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'salary' => $request->salary,
                'grade' => $request->grade,
            ]);

            // Assign roles if provided
            if ($request->has('role_ids') && is_array($request->role_ids)) {
                $user->roles()->attach($request->role_ids);
            }

            // Log the creation
            $this->logAudit($request->user(), 'create', 'users', $user->id, 'Employee created');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully',
                'data' => $user->load('roles', 'branch', 'manager')
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create employee'
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user->load(['roles', 'branch', 'managedBranch'])
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|unique:users,employee_id,' . $user->id,
            'branch_id' => 'nullable|exists:branches,id',
            'status' => 'sometimes|required|in:active,inactive,suspended',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldValues = $user->only(['name', 'email', 'phone', 'employee_id', 'branch_id', 'status']);

        DB::beginTransaction();
        try {
            $user->update($request->except(['role_ids']));

            // Update roles if provided
            if ($request->has('role_ids')) {
                $user->roles()->sync($request->role_ids);
            }

            // Log the update
            $this->logAudit(
                $request->user(),
                'update',
                'users',
                $user->id,
                'User updated',
                $oldValues,
                $user->only(['name', 'email', 'phone', 'employee_id', 'branch_id', 'status'])
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user->load('roles', 'branch')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user'
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(Request $request, User $user)
    {
        // Prevent deleting self
        if ($request->user()->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user->delete();

            // Log the deletion
            $this->logAudit($request->user(), 'delete', 'users', $user->id, 'User deleted');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user'
            ], 500);
        }
    }

    /**
     * Activate a user
     */
    public function activate(Request $request, User $user)
    {
        $oldStatus = $user->status;
        $user->update(['status' => 'active']);

        // Log the activation
        $this->logAudit(
            $request->user(),
            'activate',
            'users',
            $user->id,
            'User activated',
            ['status' => $oldStatus],
            ['status' => 'active']
        );

        return response()->json([
            'success' => true,
            'message' => 'User activated successfully',
            'data' => $user
        ]);
    }

    /**
     * Deactivate a user
     */
    public function deactivate(Request $request, User $user)
    {
        // Prevent deactivating self
        if ($request->user()->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot deactivate your own account'
            ], 422);
        }

        $oldStatus = $user->status;
        $user->update(['status' => 'inactive']);

        // Log the deactivation
        $this->logAudit(
            $request->user(),
            'deactivate',
            'users',
            $user->id,
            'User deactivated',
            ['status' => $oldStatus],
            ['status' => 'inactive']
        );

        return response()->json([
            'success' => true,
            'message' => 'User deactivated successfully',
            'data' => $user
        ]);
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$user->roles()->where('role_id', $request->role_id)->exists()) {
            $user->roles()->attach($request->role_id);

            // Log the role assignment
            $role = Role::find($request->role_id);
            $this->logAudit(
                $request->user(),
                'assign_role',
                'users',
                $user->id,
                "Role '{$role->name}' assigned to user"
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Role assigned successfully',
            'data' => $user->load('roles')
        ]);
    }

    /**
     * Remove role from user
     */
    public function removeRole(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($user->roles()->where('role_id', $request->role_id)->exists()) {
            $role = Role::find($request->role_id);
            $user->roles()->detach($request->role_id);

            // Log the role removal
            $this->logAudit(
                $request->user(),
                'remove_role',
                'users',
                $user->id,
                "Role '{$role->name}' removed from user"
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Role removed successfully',
            'data' => $user->load('roles')
        ]);
    }

    /**
     * Get employee statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_employees' => User::employees()->count(),
            'active_employees' => User::activeEmployees()->count(),
            'total_managers' => User::managers()->count(),
            'new_joins_this_month' => User::newJoinsThisMonth()->count(),
            'top_performer' => User::where('performance_rating', 'excellent')
                ->with('branch')
                ->first(),
            'performance_distribution' => [
                'excellent' => User::where('performance_rating', 'excellent')->count(),
                'good' => User::where('performance_rating', 'good')->count(),
                'average' => User::where('performance_rating', 'average')->count(),
                'weak' => User::where('performance_rating', 'weak')->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Generate unique employee ID
     */
    private function generateEmployeeId()
    {
        $year = date('Y');
        $count = User::whereYear('created_at', $year)->count() + 1;
        return 'EMP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
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
