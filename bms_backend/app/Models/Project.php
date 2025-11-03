<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'budget',
        'spent',
        'created_by',
        'assigned_to',
        'manager_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'spent' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_employee', 'project_id', 'employee_id');
    }

    // Calculated Fields
    public function getProgressPercentageAttribute()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) return 0;

        $completedTasks = $this->tasks()->where('status', 'completed')->count();
        return round(($completedTasks / $totalTasks) * 100, 2);
    }

    public function getPerformanceScoreAttribute()
    {
        $progress = $this->progress_percentage;
        $onTime = $this->end_date && $this->end_date >= now() ? 1 : 0.5;
        $budgetEfficiency = $this->budget > 0 ? min(1, $this->budget / max($this->spent, 1)) : 1;

        return round(($progress * 0.5 + $onTime * 0.3 + $budgetEfficiency * 0.2), 2);
    }

    public function getOverdueDaysAttribute()
    {
        if ($this->status === 'completed' || !$this->end_date) return 0;

        $days = now()->diffInDays($this->end_date, false);
        return $days < 0 ? abs($days) : 0;
    }

    public function getTeamEfficiencyAttribute()
    {
        $memberCount = $this->members()->count();
        if ($memberCount === 0) return 0;

        return round($this->progress_percentage / $memberCount, 2);
    }

    public function getPriorityAttribute()
    {
        if ($this->overdue_days > 0) return 'High';
        if ($this->progress_percentage > 75) return 'Low';
        return 'Medium';
    }

    public function getPerformanceRemarkAttribute()
    {
        $score = $this->performance_score;
        if ($score >= 85) return 'Excellent';
        if ($score >= 70) return 'Strong';
        if ($score >= 50) return 'Average';
        return 'Needs Attention';
    }
}
