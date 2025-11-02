<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'contact_email',
        'contact_phone',
        'manager_id',
        'status',
        'opening_date',
    ];

    protected $casts = [
        'opening_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['performance_score', 'performance_level'];

    // Relationships
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'target_branch_id');
    }

    // Accessors
    public function getPerformanceScoreAttribute()
    {
        $userCount = $this->users()->count();
        $projectCount = $this->projects()->count();
        
        $performanceScore = 0;
        if ($userCount > 0) $performanceScore += min($userCount * 10, 40);
        if ($projectCount > 0) $performanceScore += min($projectCount * 15, 30);
        
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