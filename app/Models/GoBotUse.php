<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoBotUse extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'mst_limit',
    ];

    protected $casts = [
        'mst_limit' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(GoBotPackage::class, 'package_id');
    }
}
