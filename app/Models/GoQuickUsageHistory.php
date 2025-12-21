<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoQuickUsageHistory extends Model
{
    protected $fillable = [
        'user_id',
        'go_quick_use_id',
        'job_id',
        'upload_type',
        'batch_index',
        'total_batches',
        'estimated_cccd',
        'actual_cccd',
        'cccd_deducted',
        'status',
    ];

    protected $casts = [
        'batch_index' => 'integer',
        'total_batches' => 'integer',
        'estimated_cccd' => 'integer',
        'actual_cccd' => 'integer',
        'cccd_deducted' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function goQuickUse(): BelongsTo
    {
        return $this->belongsTo(GoQuickUse::class, 'go_quick_use_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobTool::class, 'job_id');
    }
}
