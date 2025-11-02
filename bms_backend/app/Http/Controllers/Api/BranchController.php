<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    /**
     * Display a listing of branches
     */
    public function index(Request $request)
    {
        try {
            $query = Branch::with(['manager', 'users', 'projects']);

            // Filter by status
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Search by name or code
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            }

            $branches = $query->paginate(15);

            // Calculate performance for each branch
            $branches->getCollection()->transform(function ($branch) {
                $userCount = $branch->users ? $branch->users->count() : 0;
                $projectCount = $branch->projects ? $branch->projects->count() : 0;
                
                // Safely get transaction count and revenue
                $transactionCount = 0;
                $totalRevenue = 0;
                
                try {
                    if (method_exists($branch, 'transactions')) {
                        $transactions = $branch->transactions;
                        $transactionCount = $transactions ? $transactions->count() : 0;
                        $totalRevenue = $transactions ? $transactions->sum('amount') : 0;
                    }
                } catch (\Exception $e) {
                    // If transactions relationship doesn't exist, just use 0
                }

                // Calculate performance score based on various metrics
                $performanceScore = 0;
                if ($userCount > 0) $performanceScore += min($userCount * 10, 40);
                if ($projectCount > 0) $performanceScore += min($projectCount * 15, 30);
                if ($transactionCount > 0) $performanceScore += min($transactionCount * 5, 20);
                if ($totalRevenue > 0) $performanceScore += min($totalRevenue / 1000, 10);

                $branch->performance_score = min($performanceScore, 100);

                // Determine performance level
                if ($performanceScore >= 80) {
                    $branch->performance_level = 'best';
                } elseif ($performanceScore >= 60) {
                    $branch->performance_level = 'good';
                } elseif ($performanceScore >= 40) {
                    $branch->performance_level = 'average';
                } elseif ($performanceScore >= 20) {
                    $branch->performance_level = 'bad';
                } else {
                    $branch->performance_level = 'weak';
                }

                return $branch;
            });

            return response()->json([
                'success' => true,
                'data' => $branches
            ]);
        } catch (\Exception $e) {
            Log::error('Branch index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch branches',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created branch
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:branches,code',
                'address' => 'nullable|string',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'manager_id' => 'nullable|exists:users,id',
                'status' => 'nullable|in:active,inactive,suspended',
                'opening_date' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Prepare data
            $data = $request->only([
                'name', 
                'code', 
                'address', 
                'contact_email', 
                'contact_phone', 
                'manager_id', 
                'opening_date'
            ]);
            
            $data['status'] = $request->input('status', 'active');

            $branch = Branch::create($data);

            // Log the creation
            $this->logAudit($request->user(), 'create', 'branches', $branch->id, 'Branch created');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Branch created successfully',
                'data' => $branch->load('manager')
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Branch store error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified branch
     */
    public function show($id)
    {
        try {
            $branch = Branch::with(['manager', 'users', 'projects', 'inventoryItems'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $branch
            ]);
        } catch (\Exception $e) {
            Log::error('Branch show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Branch not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified branch
     */
    public function update(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'code' => 'sometimes|required|string|max:50|unique:branches,code,' . $branch->id,
                'address' => 'nullable|string',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'manager_id' => 'nullable|exists:users,id',
                'status' => 'sometimes|required|in:active,inactive,suspended',
                'opening_date' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $oldValues = $branch->only(['name', 'code', 'address', 'contact_email', 'contact_phone', 'manager_id', 'status', 'opening_date']);

            DB::beginTransaction();

            $branch->update($request->all());

            // Log the update
            $this->logAudit(
                $request->user(),
                'update',
                'branches',
                $branch->id,
                'Branch updated',
                $oldValues,
                $branch->only(['name', 'code', 'address', 'contact_email', 'contact_phone', 'manager_id', 'status', 'opening_date'])
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Branch updated successfully',
                'data' => $branch->load('manager')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Branch update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified branch
     */
    public function destroy(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);

            // Check if branch has users or projects
            if ($branch->users()->count() > 0 || $branch->projects()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete branch with associated users or projects'
                ], 422);
            }

            DB::beginTransaction();

            $branch->delete();

            // Log the deletion
            $this->logAudit($request->user(), 'delete', 'branches', $branch->id, 'Branch deleted');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Branch deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Branch destroy error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activate a branch
     */
    public function activate(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $oldStatus = $branch->status;
            
            $branch->update(['status' => 'active']);

            // Log the activation
            $this->logAudit(
                $request->user(),
                'activate',
                'branches',
                $branch->id,
                'Branch activated',
                ['status' => $oldStatus],
                ['status' => 'active']
            );

            return response()->json([
                'success' => true,
                'message' => 'Branch activated successfully',
                'data' => $branch
            ]);

        } catch (\Exception $e) {
            Log::error('Branch activate error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deactivate a branch
     */
    public function deactivate(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $oldStatus = $branch->status;
            
            $branch->update(['status' => 'inactive']);

            // Log the deactivation
            $this->logAudit(
                $request->user(),
                'deactivate',
                'branches',
                $branch->id,
                'Branch deactivated',
                ['status' => $oldStatus],
                ['status' => 'inactive']
            );

            return response()->json([
                'success' => true,
                'message' => 'Branch deactivated successfully',
                'data' => $branch
            ]);

        } catch (\Exception $e) {
            Log::error('Branch deactivate error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to log audit
     */
    private function logAudit($user, $action, $table, $recordId, $description, $oldValues = null, $newValues = null)
    {
        try {
            if ($user) {
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
        } catch (\Exception $e) {
            Log::error('Audit log error: ' . $e->getMessage());
            // Don't throw exception, just log it
        }
    }
}