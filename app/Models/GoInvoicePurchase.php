<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoInvoicePurchase extends Model
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
        'license_fee',
        'expires_tool',
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
        // Upgrade fields
        'is_upgrade',
        'upgrade_history_id',
        'old_package_id',
    ];

    protected $casts = [
        'amount' => 'integer',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'integer',
        'license_fee' => 'integer',
        'mst_limit' => 'integer',
        'expires_tool' => 'datetime',
        'expires_at' => 'datetime',
        'is_upgrade' => 'boolean',
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
        return $this->belongsTo(GoInvoicePackage::class, 'package_id');
    }

    /**
     * Relationship với gói cũ (nếu là upgrade)
     */
    public function oldPackage(): BelongsTo
    {
        return $this->belongsTo(GoInvoicePackage::class, 'old_package_id');
    }

    /**
     * Relationship với upgrade history
     */
    public function upgradeHistory(): BelongsTo
    {
        return $this->belongsTo(PackageUpgradeHistory::class, 'upgrade_history_id');
    }

    /**
     * Kiểm tra xem purchase này có phải là upgrade không
     */
    public function isUpgrade(): bool
    {
        return $this->is_upgrade === true;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }
}
