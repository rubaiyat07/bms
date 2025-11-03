<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeePerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PerformanceController extends Controller
{
    /**
     * Calculate and get performance for a specific employee
     */
    public function calculateEmployeePerformance($employeeId)
    {
        try {
            $employee = User::findOrFail($employeeId);

            $performance = $employee->calculatePerformanceScore();

            return response()->json([
                'success' => true,
                'data' => $performance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate performance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance data for a specific employee
     */
    public function getEmployeePerformance($employeeId)
    {
        try {
            $performance = EmployeePerformance::where('employee_id', $employeeId)
                ->with('employee')
                ->first();

            if (!$performance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Performance data not found. Please calculate performance first.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $performance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve performance data'
            ], 500);
        }
    }

    /**
     * Calculate performance for all employees
     */
    public function calculateAllPerformances()
    {
        try {
            $employees = User::employees()->get();
            $results = [];

            foreach ($employees as $employee) {
                $results[] = [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'performance' => $employee->calculatePerformanceScore()
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'Performance calculated for ' . count($results) . ' employees'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate performances: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance statistics for dashboard
     */
    public function getPerformanceStats()
    {
        try {
            $stats = [
                'total_employees' => User::employees()->count(),
                'performance_distribution' => [
                    'excellent' => EmployeePerformance::where('rating_label', 'excellent')->count(),
                    'strong' => EmployeePerformance::where('rating_label', 'strong')->count(),
                    'average' => EmployeePerformance::where('rating_label', 'average')->count(),
                    'weak' => EmployeePerformance::where('rating_label', 'weak')->count(),
                ],
                'average_score' => EmployeePerformance::avg('total_score'),
                'top_performers' => EmployeePerformance::with('employee')
                    ->orderBy('total_score', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function ($performance) {
                        return [
                            'employee_id' => $performance->employee_id,
                            'name' => $performance->employee->name,
                            'score' => $performance->total_score,
                            'rating' => $performance->rating_label,
                        ];
                    }),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load performance statistics'
            ], 500);
        }
    }

    /**
     * Update performance manually (admin only)
     */
    public function updatePerformance(Request $request, $employeeId)
    {
        $validator = Validator::make($request->all(), [
            'total_score' => 'required|numeric|min:0|max:100',
            'rating_label' => 'required|in:excellent,strong,average,weak',
            'breakdown' => 'nullable|array',
            'breakdown.projects' => 'nullable|numeric|min:0|max:100',
            'breakdown.tasks' => 'nullable|numeric|min:0|max:100',
            'breakdown.attendance' => 'nullable|numeric|min:0|max:100',
            'breakdown.punctuality' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $performance = EmployeePerformance::updateOrCreate(
                ['employee_id' => $employeeId],
                array_merge($request->all(), ['last_updated_at' => now()])
            );

            return response()->json([
                'success' => true,
                'data' => $performance,
                'message' => 'Performance updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update performance'
            ], 500);
        }
    }
}
