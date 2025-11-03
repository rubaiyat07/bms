<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'check_in_time',
        'check_out_time',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeOnTime($query)
    {
        return $query->where('status', 'present')
                    ->whereTime('check_in_time', '<=', '09:10:00');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'present')
                    ->whereTime('check_in_time', '>', '09:10:00');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }
}
