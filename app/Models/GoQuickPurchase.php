<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoQuickPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'bank_id',
        'package_id',
        'transaction_code',
        'amount',
        'cccd_limit',
        'status',
        'payment_status',
        'note',
        'processed_at',
        'casso_response',
        'casso_transaction_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'vat_mst',
        'vat_company',
        'vat_address',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cccd_limit' => 'integer',
        'expires_at' => 'datetime',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(GoQuickPackage::class, 'package_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_PAID);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE 
            && $this->payment_status === self::PAYMENT_STATUS_PAID
            && $this->end_date >= now();
    }
}
