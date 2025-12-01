<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoBotPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'bank_id',
        'package_id',
        'transaction_code',
        'amount',
        'discount_percent',
        'discount_amount',
        'mst_limit',
        'status',
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
        'amount' => 'integer',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'integer',
        'mst_limit' => 'integer',
        'expires_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

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
        return $this->belongsTo(GoBotPackage::class, 'package_id');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }
}
