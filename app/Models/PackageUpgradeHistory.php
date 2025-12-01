<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model lưu lịch sử nâng cấp gói
 * Mỗi lần user nâng cấp gói sẽ tạo 1 bản ghi
 */
class PackageUpgradeHistory extends Model
{
    protected $fillable = [
        'user_id',
        'tool_type', // 'go-invoice' hoặc 'go-soft'
        'old_package_id', // ID gói cũ
        'new_package_id', // ID gói mới
        'old_package_price', // Giá gói cũ
        'new_package_price', // Giá gói mới
        'price_difference', // Chênh lệch giá (new - old)
        'remaining_value', // Giá trị còn lại của gói cũ
        'new_package_value_for_remaining', // Giá trị gói mới cho thời gian còn lại
        'upgrade_count', // Số lần up gói của user (1, 2, 3...)
        'days_used', // Số ngày đã sử dụng gói cũ
        'days_remaining', // Số ngày còn lại
        'is_first_month', // Có phải trong tháng đầu không
        'is_expired', // Gói cũ đã hết hạn chưa
        'discount_percent', // % giảm giá được áp dụng
        'discount_amount', // Số tiền được giảm
        'final_amount', // Số tiền cuối cùng phải trả
        'purchase_id', // ID của purchase record (liên kết với GoInvoicePurchase hoặc GoSoftPurchase)
        'old_expires_at', // Thời hạn gói cũ
        'new_expires_at', // Thời hạn gói mới (tính từ thời điểm up gói)
        'keep_current_expires', // Giữ nguyên thời hạn cũ (bù trừ thời gian)
        'note', // Ghi chú
    ];

    protected $casts = [
        'old_package_price' => 'integer',
        'new_package_price' => 'integer',
        'price_difference' => 'integer',
        'remaining_value' => 'integer',
        'new_package_value_for_remaining' => 'integer',
        'upgrade_count' => 'integer',
        'days_used' => 'integer',
        'days_remaining' => 'integer',
        'is_first_month' => 'boolean',
        'is_expired' => 'boolean',
        'discount_percent' => 'decimal:2', // Giữ decimal vì là phần trăm
        'discount_amount' => 'integer',
        'final_amount' => 'integer',
        'old_expires_at' => 'datetime',
        'new_expires_at' => 'datetime',
        'keep_current_expires' => 'boolean',
    ];

    const TOOL_TYPE_GO_INVOICE = 'go-invoice';
    const TOOL_TYPE_GO_SOFT = 'go-soft';

    /**
     * Relationship với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship với gói cũ (GoInvoicePackage hoặc GoSoftPackage)
     */
    public function oldPackage()
    {
        if ($this->tool_type === self::TOOL_TYPE_GO_INVOICE) {
            return $this->belongsTo(GoInvoicePackage::class, 'old_package_id');
        } else {
            return $this->belongsTo(GoSoftPackage::class, 'old_package_id');
        }
    }

    /**
     * Relationship với gói mới (GoInvoicePackage hoặc GoSoftPackage)
     */
    public function newPackage()
    {
        if ($this->tool_type === self::TOOL_TYPE_GO_INVOICE) {
            return $this->belongsTo(GoInvoicePackage::class, 'new_package_id');
        } else {
            return $this->belongsTo(GoSoftPackage::class, 'new_package_id');
        }
    }

    /**
     * Relationship với Purchase (GoInvoicePurchase hoặc GoSoftPurchase)
     */
    public function purchase()
    {
        if ($this->tool_type === self::TOOL_TYPE_GO_INVOICE) {
            return $this->belongsTo(GoInvoicePurchase::class, 'purchase_id');
        } else {
            return $this->belongsTo(GoSoftPurchase::class, 'purchase_id');
        }
    }

    /**
     * Scope: Lấy lịch sử theo user và tool type
     */
    public function scopeByUserAndTool($query, $userId, $toolType)
    {
        return $query->where('user_id', $userId)
            ->where('tool_type', $toolType);
    }

    /**
     * Scope: Lấy lịch sử nâng cấp thành công (có purchase_id)
     */
    public function scopeSuccessful($query)
    {
        return $query->whereNotNull('purchase_id');
    }

    /**
     * Đếm số lần up gói của user cho tool type
     */
    public static function countUpgrades($userId, $toolType)
    {
        return self::where('user_id', $userId)
            ->where('tool_type', $toolType)
            ->whereNotNull('purchase_id') // Chỉ tính các lần up gói thành công
            ->count();
    }
}

