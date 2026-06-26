<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'priority',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class)->orderByDesc('created_at');
    }

    public function latestLog()
    {
        return $this->hasOne(ActivityLog::class)->latestOfMany();
    }

    public function todayLogs()
    {
        return $this->hasMany(ActivityLog::class)
            ->whereDate('created_at', today());
    }

    /**
     * Get the current status based on the latest log
     */
    public function getCurrentStatusAttribute(): string
    {
        $latest = $this->latestLog;
        return $latest ? $latest->status : 'pending';
    }
}
