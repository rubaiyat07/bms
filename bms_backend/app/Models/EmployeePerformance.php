<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'total_score',
        'rating_label',
        'breakdown',
        'last_updated_at',
    ];

    protected $casts = [
        'total_score' => 'decimal:2',
        'breakdown' => 'array',
        'last_updated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Accessors
    public function getRatingColorAttribute()
    {
        return match($this->rating_label) {
            'excellent' => 'green',
            'strong' => 'blue',
            'average' => 'yellow',
            'weak' => 'red',
            default => 'gray',
        };
    }
}
