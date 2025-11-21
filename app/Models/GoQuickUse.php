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
    ];

    protected $casts = [
        'cccd_limit' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(GoQuickPackage::class, 'package_id');
    }
}
