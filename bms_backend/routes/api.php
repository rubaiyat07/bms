<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\FinanceController;
use App\Http\Controllers\Api\ExpenseCategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\PerformanceController;
use App\Http\Controllers\Api\DepartmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::prefix('auth')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/branch/{branch}', [DashboardController::class, 'branchStats']);

    // Branches
    Route::apiResource('branches', BranchController::class);
    Route::post('branches/{branch}/activate', [BranchController::class, 'activate']);
    Route::post('branches/{branch}/deactivate', [BranchController::class, 'deactivate']);
    Route::get('branches/performance', [BranchController::class, 'performance']);

    // Departments
    Route::apiResource('departments', DepartmentController::class);
    Route::post('departments/{department}/activate', [DepartmentController::class, 'activate']);
    Route::post('departments/{department}/deactivate', [DepartmentController::class, 'deactivate']);
    Route::get('branches/{branch}/departments', [DepartmentController::class, 'getByBranch']);

    // Users
    Route::apiResource('users', UserController::class);
    Route::post('users/{user}/activate', [UserController::class, 'activate']);
    Route::post('users/{user}/deactivate', [UserController::class, 'deactivate']);
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole']);
    Route::post('users/{user}/remove-role', [UserController::class, 'removeRole']);
    Route::get('employees/stats', [UserController::class, 'getStats']);

    // Roles & Permissions
    Route::apiResource('roles', RoleController::class);
    Route::get('permissions', [RoleController::class, 'permissions']);
    Route::post('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions']);

    // Projects
    Route::apiResource('projects', ProjectController::class);
    Route::post('projects/{project}/complete', [ProjectController::class, 'complete']);
    Route::post('projects/{project}/cancel', [ProjectController::class, 'cancel']);

    // Inventory
    Route::apiResource('inventory', InventoryController::class);
    Route::post('inventory/{item}/adjust-quantity', [InventoryController::class, 'adjustQuantity']);
    Route::get('inventory/low-stock', [InventoryController::class, 'lowStock']);

    // Finance
    Route::apiResource('transactions', FinanceController::class);
    Route::post('transactions/{transaction}/approve', [FinanceController::class, 'approve']);
    Route::post('transactions/{transaction}/reject', [FinanceController::class, 'reject']);
    Route::get('expense-categories', [FinanceController::class, 'categories']);
    Route::get('reports/income-expense', [FinanceController::class, 'incomeExpenseReport']);
    Route::apiResource('expense-categories', ExpenseCategoryController::class);

    // Messages
    Route::apiResource('messages', MessageController::class);
    Route::post('messages/{message}/mark-read', [MessageController::class, 'markAsRead']);
    Route::get('messages/unread/count', [MessageController::class, 'unreadCount']);

    // Announcements
    Route::apiResource('announcements', AnnouncementController::class);
    Route::get('announcements/active/list', [AnnouncementController::class, 'activeAnnouncements']);

    // Performance
    Route::prefix('performance')->group(function () {
        Route::post('calculate/{employeeId}', [PerformanceController::class, 'calculateEmployeePerformance']);
        Route::get('employee/{employeeId}', [PerformanceController::class, 'getEmployeePerformance']);
        Route::post('calculate-all', [PerformanceController::class, 'calculateAllPerformances']);
        Route::get('stats', [PerformanceController::class, 'getPerformanceStats']);
        Route::put('employee/{employeeId}', [PerformanceController::class, 'updatePerformance']);
    });

    // Audit Logs
    Route::get('audit-logs', [AuditLogController::class, 'index']);
    Route::get('audit-logs/{log}', [AuditLogController::class, 'show']);

    // Notifications
    Route::get('notifications', function (Request $request) {
        return $request->user()->notifications;
    });
    Route::post('notifications/{id}/read', function (Request $request, $id) {
        $request->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    });
    Route::post('notifications/read-all', function (Request $request) {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    });
});
