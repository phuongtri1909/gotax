<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model cấu hình giảm giá khi nâng cấp gói cho từng tool
 * Mỗi tool (go-invoice, go-soft) có thể có cấu hình giảm giá khác nhau
 */
class PackageUpgradeConfig extends Model
{
    protected $fillable = [
        'tool_type', // 'go-invoice', 'go-soft', 'go-bot', 'go-quick'
        'first_upgrade_discount_first_month', // Giảm % lần đầu up gói trong tháng đầu (30%)
        'second_upgrade_discount_first_month', // Giảm % lần 2 up gói trong tháng đầu (20%)
        'subsequent_upgrade_discount_first_month', // Giảm % lần 3-4-5 up gói trong tháng đầu (20%)
        'upgrade_discount_after_first_month', // Giảm % khi up gói sau tháng đầu (10%)
        'renewal_discount_after_expired', // Giảm % khi gia hạn sau khi hết hạn (5%)
        'cross_product_discount', // Giảm % khi mua sản phẩm khác hoặc gia hạn (10%)
        'first_purchase_discount', // Giảm % lần đầu mua (khác với lần đầu upgrade)
        'status', // active/inactive
    ];

    protected $casts = [
        'first_upgrade_discount_first_month' => 'decimal:2',
        'second_upgrade_discount_first_month' => 'decimal:2',
        'subsequent_upgrade_discount_first_month' => 'decimal:2',
        'upgrade_discount_after_first_month' => 'decimal:2',
        'renewal_discount_after_expired' => 'decimal:2',
        'cross_product_discount' => 'decimal:2',
        'first_purchase_discount' => 'decimal:2',
        'status' => 'string',
    ];

    const TOOL_TYPE_GO_INVOICE = 'go-invoice';
    const TOOL_TYPE_GO_SOFT = 'go-soft';
    const TOOL_TYPE_GO_BOT = 'go-bot';
    const TOOL_TYPE_GO_QUICK = 'go-quick';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * Lấy cấu hình theo tool type
     */
    public static function getByToolType($toolType)
    {
        return self::where('tool_type', $toolType)
            ->where('status', self::STATUS_ACTIVE)
            ->first();
    }

    /**
     * Lấy % giảm giá dựa trên số lần up gói và thời gian
     */
    public function getDiscountPercent($upgradeCount, $isFirstMonth, $isExpired = false)
    {
        // Nếu đã hết hạn, áp dụng giảm giá gia hạn
        if ($isExpired) {
            return $this->renewal_discount_after_expired ?? 5;
        }

        // Nếu không trong tháng đầu
        if (!$isFirstMonth) {
            return $this->upgrade_discount_after_first_month ?? 10;
        }

        // Trong tháng đầu, tính theo số lần up gói
        if ($upgradeCount === 1) {
            return $this->first_upgrade_discount_first_month ?? 30;
        } elseif ($upgradeCount === 2) {
            return $this->second_upgrade_discount_first_month ?? 20;
        } else {
            return $this->subsequent_upgrade_discount_first_month ?? 20;
        }
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}

