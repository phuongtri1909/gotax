<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobTool extends Model
{
    protected $table = 'jobs_tools';
    
    protected $primaryKey = 'id';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'user_id',
        'tool',
        'action',
        'status',
        'progress',
        'result',
        'error',
        'params',
    ];
    
    protected $casts = [
        'params' => 'array',
        'result' => 'array',
        'progress' => 'integer',
    ];
    
    /**
     * Boot method để tự động generate ID
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($job) {
            if (empty($job->id)) {
                $job->id = (string) Str::uuid();
            }
        });
    }
    
    /**
     * Relationship với JobProgress
     */
    public function progressHistory()
    {
        return $this->hasMany(JobProgress::class, 'job_tools_id', 'id');
    }
    
    /**
     * Relationship với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope: Filter by tool
     */
    public function scopeForTool($query, $tool)
    {
        return $query->where('tool', $tool);
    }
    
    /**
     * Scope: Filter by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Scope: Filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
