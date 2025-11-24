<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoSoftUse extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'expires_at',
        'mst_limit',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'mst_limit' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(GoSoftPackage::class, 'package_id')->withDefault();
    }
    
}
