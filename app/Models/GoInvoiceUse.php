<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoInvoiceUse extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'mst_limit',
        'expires_at',
        'purchase_count',
        'first_purchase_date',
    ];

    protected $casts = [
        'mst_limit' => 'integer',
        'expires_at' => 'datetime',
        'purchase_count' => 'integer',
        'first_purchase_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(GoInvoicePackage::class, 'package_id')->withDefault();
    }
}
