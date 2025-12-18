<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobProgress extends Model
{
    protected $table = 'jobs_progress';
    
    protected $fillable = [
        'job_tools_id',
        'step',
        'message',
        'percent',
        'data',
    ];
    
    protected $casts = [
        'percent' => 'integer',
        'data' => 'array',
    ];
    
    /**
     * Relationship với JobTool
     */
    public function job()
    {
        return $this->belongsTo(JobTool::class, 'job_tools_id', 'id');
    }
}
