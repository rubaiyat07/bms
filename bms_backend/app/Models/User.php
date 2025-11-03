<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'branch_id',
        'department_id',
        'last_login_at',
        'last_login_ip',
        'employee_id',
        'designation',
        'performance_rating',
        'join_date',
        'manager_id',
        'address',
        'date_of_birth',
        'gender',
        'salary',
        'grade',
        'documents',
        'performance_history',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'join_date' => 'date',
        'date_of_birth' => 'date',
        'salary' => 'decimal:2',
        'documents' => 'array',
        'performance_history' => 'array',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'posted_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'uploaded_by');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function managedBranch()
    {
        return $this->hasOne(Branch::class, 'manager_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function completedProjects()
    {
        return $this->hasMany(Project::class, 'created_by')->where('status', 'completed');
    }

    public function assignedProjects()
    {
        return $this->hasMany(Project::class, 'assigned_to');
    }

    public function completedAssignedProjects()
    {
        return $this->assignedProjects()->where('status', 'completed');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function completedTasks()
    {
        return $this->assignedTasks()->where('status', 'completed');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function performance()
    {
        return $this->hasOne(EmployeePerformance::class, 'employee_id');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getIsManagerAttribute()
    {
        return $this->subordinates()->count() > 0;
    }

    public function getCalculatedPerformanceRatingAttribute()
    {
        $completedProjectsCount = $this->completedAssignedProjects()->count();
        $totalAssignedProjects = $this->assignedProjects()->count();

        // Get attendance data
        $attendanceRate = $this->getAttendanceRate();

        // Get punctuality score
        $punctualityScore = $this->getPunctualityScore();

        // Get tasks completed count
        $tasksCompletedCount = $this->getTasksCompletedCount();
        $totalAssignedTasks = $this->assignedTasks()->count();

        // Calculate weighted score
        $projectScore = ($totalAssignedProjects > 0) ? ($completedProjectsCount / $totalAssignedProjects) * 100 : 0;
        $taskScore = ($totalAssignedTasks > 0) ? ($tasksCompletedCount / $totalAssignedTasks) * 100 : 0;
        $attendanceScore = $attendanceRate * 100;
        $punctualityScore = $punctualityScore * 100;

        $totalScore = ($projectScore * 0.30) + ($taskScore * 0.30) + ($attendanceScore * 0.25) + ($punctualityScore * 0.15);

        if ($totalScore >= 85) {
            return 'excellent';
        } elseif ($totalScore >= 70) {
            return 'strong';
        } elseif ($totalScore >= 50) {
            return 'average';
        } else {
            return 'weak';
        }
    }

    public function calculatePerformanceScore()
    {
        $completedProjectsCount = $this->completedAssignedProjects()->count();
        $totalAssignedProjects = $this->assignedProjects()->count();

        $attendanceRate = $this->getAttendanceRate();
        $punctualityScore = $this->getPunctualityScore();

        $tasksCompletedCount = $this->getTasksCompletedCount();
        $totalAssignedTasks = $this->assignedTasks()->count();

        // Calculate component scores
        $projectScore = ($totalAssignedProjects > 0) ? ($completedProjectsCount / $totalAssignedProjects) * 100 : 0;
        $taskScore = ($totalAssignedTasks > 0) ? ($tasksCompletedCount / $totalAssignedTasks) * 100 : 0;
        $attendanceScore = $attendanceRate * 100;
        $punctualityScore = $punctualityScore * 100;

        // Calculate weighted total score
        $totalScore = ($projectScore * 0.30) + ($taskScore * 0.30) + ($attendanceScore * 0.25) + ($punctualityScore * 0.15);

        // Determine rating
        if ($totalScore >= 85) {
            $rating = 'excellent';
        } elseif ($totalScore >= 70) {
            $rating = 'strong';
        } elseif ($totalScore >= 50) {
            $rating = 'average';
        } else {
            $rating = 'weak';
        }

        // Save to performance table
        $this->performance()->updateOrCreate(
            ['employee_id' => $this->id],
            [
                'total_score' => round($totalScore, 2),
                'rating_label' => $rating,
                'breakdown' => [
                    'projects' => round($projectScore, 2),
                    'tasks' => round($taskScore, 2),
                    'attendance' => round($attendanceScore, 2),
                    'punctuality' => round($punctualityScore, 2),
                ],
                'last_updated_at' => now(),
            ]
        );

        return [
            'score' => round($totalScore, 2),
            'rating' => $rating,
            'breakdown' => [
                'projects' => round($projectScore, 2),
                'tasks' => round($taskScore, 2),
                'attendance' => round($attendanceScore, 2),
                'punctuality' => round($punctualityScore, 2),
            ]
        ];
    }

    private function getAttendanceRate()
    {
        $totalDays = $this->attendanceRecords()
            ->where('date', '>=', now()->subDays(30))
            ->count();

        if ($totalDays === 0) return 0;

        $presentDays = $this->attendanceRecords()
            ->where('date', '>=', now()->subDays(30))
            ->whereIn('status', ['present', 'half_day'])
            ->count();

        return $presentDays / $totalDays;
    }

    private function getPunctualityScore()
    {
        $presentDays = $this->attendanceRecords()
            ->where('date', '>=', now()->subDays(30))
            ->where('status', 'present')
            ->count();

        if ($presentDays === 0) return 0;

        $onTimeDays = $this->attendanceRecords()
            ->where('date', '>=', now()->subDays(30))
            ->where('status', 'present')
            ->whereTime('check_in_time', '<=', '09:10:00')
            ->count();

        return $onTimeDays / $presentDays;
    }

    private function getTasksCompletedCount()
    {
        return $this->completedTasks()
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();
    }

    // Scopes
    public function scopeEmployees($query)
    {
        return $query->where('status', '!=', 'suspended');
    }

    public function scopeActiveEmployees($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeManagers($query)
    {
        return $query->whereHas('subordinates');
    }

    public function scopeNewJoinsThisMonth($query)
    {
        return $query->whereMonth('join_date', now()->month)
                    ->whereYear('join_date', now()->year);
    }

    public function scopeByPerformance($query, $rating)
    {
        return $query->where('performance_rating', $rating);
    }
}
