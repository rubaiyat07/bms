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

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getIsManagerAttribute()
    {
        return $this->subordinates()->count() > 0;
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
