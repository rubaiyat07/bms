<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard overview data
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get basic stats
        $stats = $this->getBasicStats();

        // Get recent activities
        $activities = $this->getRecentActivities();

        // Get branch performance if user has a branch
        $branchStats = null;
        if ($user->branch_id) {
            $branchStats = $this->getBranchStats($user->branch);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'activities' => $activities,
                'branch_stats' => $branchStats
            ]
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function stats(Request $request)
    {
        $stats = $this->getBasicStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get branch-specific statistics
     */
    public function branchStats(Branch $branch)
    {
        $stats = $this->getBranchStats($branch);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get basic dashboard statistics
     */
    private function getBasicStats()
    {
        $totalProjects = Project::count();
        $completedProjects = Project::where('status', 'completed')->count();
        $delayedProjects = Project::where('status', '!=', 'completed')
            ->where('end_date', '<', now())
            ->count();

        $averageCompletionRate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100, 2) : 0;

        return [
            'total_employees' => User::employees()->count(),
            'active_employees' => User::activeEmployees()->count(),
            'total_branches' => Branch::count(),
            'active_branches' => Branch::where('status', 'active')->count(),
            'total_projects' => $totalProjects,
            'active_projects' => Project::whereIn('status', ['pending', 'in_progress'])->count(),
            'completed_projects' => $completedProjects,
            'delayed_projects' => $delayedProjects,
            'average_completion_rate' => $averageCompletionRate,
            'ongoing_projects' => Project::where('status', 'in_progress')->count(),
            'total_revenue' => Transaction::where('type', 'income')->sum('amount'),
            'total_expenses' => Transaction::where('type', 'expense')->sum('amount'),
            'net_profit' => Transaction::where('type', 'income')->sum('amount') -
                           Transaction::where('type', 'expense')->sum('amount'),
            'new_employees_this_month' => User::newJoinsThisMonth()->count(),
            'performance_distribution' => [
                'excellent' => User::where('performance_rating', 'excellent')->count(),
                'good' => User::where('performance_rating', 'good')->count(),
                'average' => User::where('performance_rating', 'average')->count(),
                'weak' => User::where('performance_rating', 'weak')->count(),
            ]
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        $activities = [];

        // Recent employee additions
        $recentEmployees = User::with('branch')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                $branchName = $user->branch ? $user->branch->name : 'the company';
                return [
                    'type' => 'employee_added',
                    'title' => 'New Employee Added',
                    'description' => "{$user->name} joined {$branchName}",
                    'timestamp' => $user->created_at,
                    'icon' => 'user-plus'
                ];
            });

        // Recent project completions
        $recentProjects = Project::with('branch')
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($project) {
                return [
                    'type' => 'project_completed',
                    'title' => 'Project Completed',
                    'description' => "{$project->title} has been completed",
                    'timestamp' => $project->updated_at,
                    'icon' => 'check-circle'
                ];
            });

        // Combine and sort activities
        $activities = collect([...$recentEmployees, ...$recentProjects])
            ->sortByDesc('timestamp')
            ->take(10)
            ->values()
            ->all();

        return $activities;
    }

    /**
     * Get branch-specific statistics
     */
    private function getBranchStats(Branch $branch)
    {
        return [
            'branch_name' => $branch->name,
            'total_employees' => $branch->users()->count(),
            'active_employees' => $branch->users()->where('status', 'active')->count(),
            'total_projects' => $branch->projects()->count(),
            'completed_projects' => $branch->projects()->where('status', 'completed')->count(),
            'ongoing_projects' => $branch->projects()->where('status', 'in_progress')->count(),
            'branch_performance' => $this->calculateBranchPerformance($branch),
            'top_performers' => $branch->users()
                ->where('performance_rating', 'excellent')
                ->limit(3)
                ->get(['id', 'name', 'performance_rating'])
        ];
    }

    /**
     * Calculate branch performance based on project completion rate
     */
    private function calculateBranchPerformance(Branch $branch)
    {
        $totalProjects = $branch->projects()->count();
        if ($totalProjects === 0) return 'N/A';

        $completedProjects = $branch->projects()->where('status', 'completed')->count();
        $completionRate = ($completedProjects / $totalProjects) * 100;

        if ($completionRate >= 90) return 'excellent';
        if ($completionRate >= 75) return 'good';
        if ($completionRate >= 60) return 'average';
        return 'weak';
    }
}
