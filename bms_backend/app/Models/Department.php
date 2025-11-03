<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'branch_id',
        'manager_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['performance_score', 'performance_level'];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Accessors
    public function getPerformanceScoreAttribute()
    {
        $userCount = $this->users()->count();
        $projectCount = $this->projects()->count();

        $performanceScore = 0;
        if ($userCount > 0) $performanceScore += min($userCount * 8, 30);
        if ($projectCount > 0) $performanceScore += min($projectCount * 12, 40);

        return min($performanceScore, 100);
    }

    public function getPerformanceLevelAttribute()
    {
        $score = $this->performance_score;

        if ($score >= 80) return 'best';
        if ($score >= 60) return 'good';
        if ($score >= 40) return 'average';
        if ($score >= 20) return 'bad';
        return 'weak';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }
}
