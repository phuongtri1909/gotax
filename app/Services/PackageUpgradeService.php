<?php

namespace App\Services;

use App\Models\PackageUpgradeConfig;
use App\Models\PackageUpgradeHistory;
use App\Models\GoInvoiceUse;
use App\Models\GoSoftUse;
use App\Models\GoBotUse;
use App\Models\GoQuickUse;
use App\Models\GoInvoicePackage;
use App\Models\GoSoftPackage;
use App\Models\GoBotPackage;
use App\Models\GoQuickPackage;
use App\Models\GoInvoicePurchase;
use App\Models\GoSoftPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PackageUpgradeService
{
    
    public function calculateUpgradePrice($userId, $toolType, $newPackageId)
    {
        try {
            
            $newPackage = $this->getPackage($toolType, $newPackageId);
            if (!$newPackage) {
                return null;
            }

            $currentUse = $this->getCurrentUse($userId, $toolType);

            if (!$currentUse || !$currentUse->package_id) {
                return null;
            }

            $oldPackage = $currentUse->package;
            if (!$oldPackage) {
                return null;
            }

            $validation = $this->validateUpgrade($currentUse, $oldPackage, $newPackage, $toolType);
            if (!$validation['can_upgrade']) {
                return [
                    'can_upgrade' => false,
                    'message' => $validation['message']
                ];
            }

            $oldPrice = (float) $oldPackage->price;
            $newPrice = (float) $newPackage->price;

            if ($newPrice <= $oldPrice) {
                return [
                    'can_upgrade' => false,
                    'message' => 'Gói này không cao hơn gói hiện tại của bạn.'
                ];
            }

            $daysUsed = $this->calculateDaysUsed($currentUse);

            $isFirstMonth = $daysUsed <= 30;

            $isExpired = $currentUse->expires_at && $currentUse->expires_at->isPast();

            $daysRemaining = 0;
            $hoursRemaining = 0;
            
            if ($currentUse->expires_at && $currentUse->expires_at->isFuture()) {
                $now = Carbon::now();
                $expiresAt = Carbon::parse($currentUse->expires_at);
                $diff = $now->diff($expiresAt);
                $daysRemaining = $diff->days;
                $hoursRemaining = $diff->h;
            }

            $purchases = $this->getPurchaseHistory($userId, $toolType, $oldPackage->id);
            $purchaseCount = count($purchases);

            $createdAt = $currentUse->created_at ? Carbon::parse($currentUse->created_at) : Carbon::now();
            $now = Carbon::now();
            $daysFromStart = $createdAt->diffInDays($now);
            $yearsUsed = $daysFromStart / 365;

            $upgradeCount = PackageUpgradeHistory::countUpgrades($userId, $toolType) + 1;

            if (in_array($toolType, ['go-bot', 'go-quick'])) {
                
                $config = PackageUpgradeConfig::getByToolType($toolType);
                $discountPercent = $config ? ($config->cross_product_discount ?? 10) : 10;

                $discountAmount = round($newPrice * ($discountPercent / 100));
                $finalAmount = round($newPrice - $discountAmount);

                $newExpiresAt = null;
                $shouldResetExpires = false;
            } else {
                
                $config = PackageUpgradeConfig::getByToolType($toolType);
                if (!$config) {
                    $discountPercent = $this->getDefaultDiscountPercent($upgradeCount, $isFirstMonth, $isExpired);
                } else {
                    $discountPercent = $config->getDiscountPercent($upgradeCount, $isFirstMonth, $isExpired);
                }

                $actualPaidAmount = isset($purchases[0]) ? (int)$purchases[0]['amount'] : $oldPrice;

                $firstYearAmount = isset($purchases[0]) ? (int)$purchases[0]['amount'] : $oldPrice;

                $priceDifference = round($newPrice - $firstYearAmount);
                $discountAmount = round($priceDifference * ($discountPercent / 100));
                $finalAmount = max(0, round($priceDifference - $discountAmount));

                $newExpiresAt = Carbon::now()->addYear();
                $shouldResetExpires = true;
            }

            if (in_array($toolType, ['go-bot', 'go-quick'])) {
                
                $currentLimit = 0;
                $newLimit = 0;
                
                if ($toolType === 'go-bot') {
                    $currentLimit = $currentUse->mst_limit ?? 0;
                    $newLimit = $newPackage->mst_limit ?? 0;
                } elseif ($toolType === 'go-quick') {
                    $currentLimit = $currentUse->cccd_limit ?? 0;
                    $newLimit = $newPackage->cccd_limit ?? 0;
                }
                
                $totalLimitAfter = $currentLimit + $newLimit;
                
                return [
                    'can_upgrade' => true,
                    'old_package' => [
                        'id' => $oldPackage->id,
                        'name' => $oldPackage->name,
                        'price' => $oldPrice,
                    ],
                    'new_package' => [
                        'id' => $newPackage->id,
                        'name' => $newPackage->name,
                        'price' => $newPrice,
                    ],
                    'price_difference' => $newPrice, 
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                    'current_limit' => $currentLimit,
                    'new_limit' => $newLimit,
                    'total_limit_after' => $totalLimitAfter,
                    'limit_type' => $toolType === 'go-bot' ? 'mst' : 'cccd',
                ];
            } else {
                
                return [
                    'can_upgrade' => true,
                    'old_package' => [
                        'id' => $oldPackage->id,
                        'name' => $oldPackage->name,
                        'price' => $actualPaidAmount, 
                    ],
                    'new_package' => [
                        'id' => $newPackage->id,
                        'name' => $newPackage->name,
                        'price' => $newPrice,
                    ],
                    'price_difference' => $priceDifference,
                    'remaining_value' => 0, 
                    'new_package_value_for_remaining' => 0, 
                    'days_remaining' => $daysRemaining, 
                    'hours_remaining' => $hoursRemaining, 
                    'upgrade_count' => $upgradeCount,
                    'days_used' => $daysUsed,
                    'is_first_month' => $isFirstMonth,
                    'is_expired' => $isExpired,
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                    'new_expires_at' => $newExpiresAt,
                    'keep_current_expires' => false, 
                    'should_reset_expires' => true,
                ];
            }

        } catch (\Exception $e) {
            Log::error('Lỗi tính toán giá nâng cấp: ' . $e->getMessage(), [
                'user_id' => $userId,
                'tool_type' => $toolType,
                'new_package_id' => $newPackageId,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    private function validateUpgrade($currentUse, $oldPackage, $newPackage, $toolType)
    {
        $oldPrice = (float) $oldPackage->price;
        $newPrice = (float) $newPackage->price;

        if ($newPrice < $oldPrice) {
            
            if (!$currentUse->expires_at) {
                return [
                    'can_upgrade' => false,
                    'message' => 'Không thể giảm cấp gói. Vui lòng chọn gói cao hơn hoặc bằng gói hiện tại.'
                ];
            }

            $daysRemaining = Carbon::now()->diffInDays($currentUse->expires_at, false);

            if ($daysRemaining >= 30) {
                return [
                    'can_upgrade' => false,
                    'message' => 'Không thể giảm cấp gói. Chỉ có thể mua gói thấp hơn khi thời hạn còn lại dưới 1 tháng.'
                ];
            }

            return [
                'can_upgrade' => false,
                'message' => 'Bạn có thể mua gói này như một gói mới (không phải nâng cấp).',
                'can_purchase' => true
            ];
        }

        if ($newPrice == $oldPrice) {
            return [
                'can_upgrade' => false,
                'message' => 'Gói này giống với gói hiện tại của bạn.'
            ];
        }

        return [
            'can_upgrade' => true,
            'message' => 'Có thể nâng cấp gói.'
        ];
    }

    private function calculateDaysUsed($currentUse)
    {
        if (!$currentUse) {
            return 0;
        }

        if ($currentUse->created_at) {
            $daysUsed = Carbon::now()->diffInDays($currentUse->created_at, false);
            return max(0, $daysUsed);
        }

        if ($currentUse->expires_at) {
            $totalDays = 365; 
            $daysRemaining = Carbon::now()->diffInDays($currentUse->expires_at, false);
            
            if ($daysRemaining < 0) {
                return $totalDays; 
            }
            
            return max(0, $totalDays - $daysRemaining);
        }

        return 0;
    }

    public function getPackage($toolType, $packageId)
    {
        if ($toolType === 'go-invoice') {
            return GoInvoicePackage::find($packageId);
        } elseif ($toolType === 'go-soft') {
            return GoSoftPackage::find($packageId);
        } elseif ($toolType === 'go-bot') {
            return GoBotPackage::find($packageId);
        } elseif ($toolType === 'go-quick') {
            return GoQuickPackage::find($packageId);
        }
        return null;
    }

    private function getPurchaseHistory($userId, $toolType, $packageId)
    {
        if ($toolType === 'go-invoice') {
            $purchases = GoInvoicePurchase::where('user_id', $userId)
                ->where('package_id', $packageId)
                ->where('status', GoInvoicePurchase::STATUS_SUCCESS)
                ->where('is_upgrade', false) 
                ->orderBy('created_at', 'asc')
                ->get();
        } elseif ($toolType === 'go-soft') {
            $purchases = GoSoftPurchase::where('user_id', $userId)
                ->where('package_id', $packageId)
                ->where('status', GoSoftPurchase::STATUS_SUCCESS)
                ->where('is_upgrade', false) 
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            return [];
        }

        $result = [];
        foreach ($purchases as $purchase) {
            $result[] = [
                'id' => $purchase->id,
                'amount' => (int)$purchase->amount,
                'created_at' => $purchase->created_at,
            ];
        }

        return $result;
    }

    public function getCurrentUse($userId, $toolType)
    {
        if ($toolType === 'go-invoice') {
            return GoInvoiceUse::where('user_id', $userId)->first();
        } elseif ($toolType === 'go-soft') {
            return GoSoftUse::where('user_id', $userId)->first();
        } elseif ($toolType === 'go-bot') {
            return GoBotUse::where('user_id', $userId)->first();
        } elseif ($toolType === 'go-quick') {
            return GoQuickUse::where('user_id', $userId)->first();
        }
        return null;
    }

    private function getDefaultDiscountPercent($upgradeCount, $isFirstMonth, $isExpired)
    {
        if ($isExpired) {
            return 5; 
        }

        if (!$isFirstMonth) {
            return 10; 
        }

        if ($upgradeCount === 1) {
            return 30; 
        } elseif ($upgradeCount === 2) {
            return 20; 
        } else {
            return 20; 
        }
    }

    public function createUpgradeHistory($userId, $toolType, $calculationResult, $purchaseId = null)
    {
        try {
            $currentUse = $this->getCurrentUse($userId, $toolType);
            
            return PackageUpgradeHistory::create([
                'user_id' => $userId,
                'tool_type' => $toolType,
                'old_package_id' => $calculationResult['old_package']['id'],
                'old_package_price' => $calculationResult['old_package']['price'],
                'old_expires_at' => $currentUse ? $currentUse->expires_at : null,
                'new_package_id' => $calculationResult['new_package']['id'],
                'new_package_price' => $calculationResult['new_package']['price'],
                'new_expires_at' => $calculationResult['new_expires_at'],
                'price_difference' => $calculationResult['price_difference'],
                'remaining_value' => $calculationResult['remaining_value'] ?? null,
                'new_package_value_for_remaining' => $calculationResult['new_package_value_for_remaining'] ?? null,
                'upgrade_count' => $calculationResult['upgrade_count'],
                'days_used' => $calculationResult['days_used'],
                'days_remaining' => $calculationResult['days_remaining'] ?? 0,
                'is_first_month' => $calculationResult['is_first_month'],
                'is_expired' => $calculationResult['is_expired'],
                'discount_percent' => $calculationResult['discount_percent'],
                'discount_amount' => $calculationResult['discount_amount'],
                'final_amount' => $calculationResult['final_amount'],
                'keep_current_expires' => $calculationResult['keep_current_expires'] ?? false,
                'purchase_id' => $purchaseId,
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi tạo lịch sử nâng cấp: ' . $e->getMessage());
            return null;
        }
    }

    public function canPurchaseLowerPackage($userId, $toolType, $newPackageId)
    {
        $currentUse = $this->getCurrentUse($userId, $toolType);
        
        if (!$currentUse || !$currentUse->package_id) {
            return true; 
        }

        $oldPackage = $currentUse->package;
        $newPackage = $this->getPackage($toolType, $newPackageId);
        
        if (!$oldPackage || !$newPackage) {
            return false;
        }

        $oldPrice = (float) $oldPackage->price;
        $newPrice = (float) $newPackage->price;

        if ($newPrice >= $oldPrice) {
            return true;
        }

        if (!$currentUse->expires_at) {
            return false;
        }

        $daysRemaining = Carbon::now()->diffInDays($currentUse->expires_at, false);

        return $daysRemaining < 30;
    }

    public function calculateDowngradePrice($userId, $toolType, $newPackageId, $checkExpiry = true)
    {
        $currentUse = $this->getCurrentUse($userId, $toolType);
        
        if (!$currentUse || !$currentUse->package_id) {
            return null; 
        }

        $oldPackage = $currentUse->package;
        $newPackage = $this->getPackage($toolType, $newPackageId);
        
        if (!$oldPackage || !$newPackage) {
            return null;
        }

        $oldPrice = (float) $oldPackage->price;
        $newPrice = (float) $newPackage->price;

        if ($newPrice >= $oldPrice) {
            return null;
        }

        $daysRemaining = null;
        $hoursRemaining = null;
        if ($currentUse->expires_at) {
            $now = Carbon::now();
            $expiresAt = Carbon::parse($currentUse->expires_at);
            
            if ($expiresAt->isFuture()) {
                $diff = $now->diff($expiresAt);
                $daysRemaining = $diff->days;
                $hoursRemaining = $diff->h;
            } else {
                $daysRemaining = 0;
                $hoursRemaining = 0;
            }
        }

        if ($checkExpiry) {
            if (!$currentUse->expires_at || $daysRemaining >= 30) {
                return null;
            }
        }

        $discountPercent = $this->getCrossProductDiscount($toolType);
        $discountAmount = round($newPrice * ($discountPercent / 100));
        $finalAmount = round($newPrice - $discountAmount);

        return [
            'is_downgrade' => true,
            'old_package' => [
                'id' => $oldPackage->id,
                'name' => $oldPackage->name,
                'price' => $oldPrice,
            ],
            'new_package' => [
                'id' => $newPackage->id,
                'name' => $newPackage->name,
                'price' => $newPrice,
            ],
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'original_price' => $newPrice,
            'final_amount' => $finalAmount,
            'days_remaining' => $daysRemaining,
            'hours_remaining' => $hoursRemaining,
            'can_purchase' => !$checkExpiry || ($daysRemaining !== null && $daysRemaining < 30), 
        ];
    }

    public function checkRenewalPackage($userId, $toolType, $newPackageId)
    {
        $currentUse = $this->getCurrentUse($userId, $toolType);
        
        if (!$currentUse || !$currentUse->package_id) {
            return null; 
        }

        if ($currentUse->package_id == $newPackageId) {
            $oldPackage = $currentUse->package;
            $newPackage = $this->getPackage($toolType, $newPackageId);
            
            if (!$oldPackage || !$newPackage) {
                return null;
            }

            $newExpiresAt = null;
            if ($currentUse->expires_at && $currentUse->expires_at->isFuture()) {
                
                $newExpiresAt = $currentUse->expires_at->copy()->addYear();
            } else {
                
                $newExpiresAt = Carbon::now()->addYear();
            }

            $packagePrice = (int) $newPackage->price;
            $config = PackageUpgradeConfig::getByToolType($toolType);
            $discountPercent = $config ? ($config->cross_product_discount ?? 10) : 10;
            $discountAmount = round($packagePrice * ($discountPercent / 100));
            $finalAmount = round($packagePrice - $discountAmount);

            return [
                'is_renewal' => true,
                'current_expires_at' => $currentUse->expires_at,
                'new_expires_at' => $newExpiresAt,
                'package' => [
                    'id' => $newPackage->id,
                    'name' => $newPackage->name,
                    'price' => $packagePrice,
                ],
                'discount_percent' => $discountPercent,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ];
        }

        return null; 
    }

    public function hasAnyActiveProduct($userId, $excludeToolType = null)
    {
        
        $hasGoInvoice = false;
        if ($excludeToolType !== 'go-invoice') {
            $hasGoInvoice = GoInvoiceUse::where('user_id', $userId)
                ->whereNotNull('package_id')
                ->where(function($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', Carbon::now());
                })
                ->exists();
        }

        $hasGoSoft = false;
        if ($excludeToolType !== 'go-soft') {
            $hasGoSoft = GoSoftUse::where('user_id', $userId)
                ->whereNotNull('package_id')
                ->where(function($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', Carbon::now());
                })
                ->exists();
        }

        $hasGoBot = false;
        if ($excludeToolType !== 'go-bot') {
            $hasGoBot = GoBotUse::where('user_id', $userId)
                ->whereNotNull('package_id')
                ->exists();
        }

        $hasGoQuick = false;
        if ($excludeToolType !== 'go-quick') {
            $hasGoQuick = GoQuickUse::where('user_id', $userId)
                ->whereNotNull('package_id')
                ->exists();
        }

        return $hasGoInvoice || $hasGoSoft || $hasGoBot || $hasGoQuick;
    }

    public function getFirstPurchaseDiscount($toolType)
    {
        $config = PackageUpgradeConfig::getByToolType($toolType);
        return $config ? ($config->first_purchase_discount ?? 0) : 0;
    }

    public function getCrossProductDiscount($toolType)
    {
        $config = PackageUpgradeConfig::getByToolType($toolType);
        return $config ? ($config->cross_product_discount ?? 10) : 10;
    }
}

