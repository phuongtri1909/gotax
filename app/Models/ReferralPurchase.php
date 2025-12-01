<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralPurchase extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'referral_code',
        'tool_type',
        'purchase_id',
        'transaction_code',
        'amount',
        'status',
        'purchase_date',
    ];

    protected $casts = [
        'amount' => 'integer',
        'purchase_date' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}
