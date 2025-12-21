<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoQuickUse extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'cccd_limit',
        'total_used',
        'total_cccd_extracted',
    ];

    protected $casts = [
        'cccd_limit' => 'integer',
        'total_used' => 'integer',
        'total_cccd_extracted' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(GoQuickPackage::class, 'package_id')->withDefault();
    }

    public function usageHistories()
    {
        return $this->hasMany(GoQuickUsageHistory::class, 'go_quick_use_id');
    }
}
