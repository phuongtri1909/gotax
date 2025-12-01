<?php

namespace Database\Seeders;

use App\Models\PackageUpgradeConfig;
use Illuminate\Database\Seeder;

class PackageUpgradeConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'tool_type' => PackageUpgradeConfig::TOOL_TYPE_GO_INVOICE,
                'first_upgrade_discount_first_month' => 30.00, // Lần đầu up gói trong tháng đầu: 30%
                'second_upgrade_discount_first_month' => 20.00, // Lần 2 up gói trong tháng đầu: 20%
                'subsequent_upgrade_discount_first_month' => 20.00, // Lần 3-4-5 up gói trong tháng đầu: 20%
                'upgrade_discount_after_first_month' => 10.00, // Up gói sau tháng đầu: 10%
                'renewal_discount_after_expired' => 5.00, // Gia hạn sau khi hết hạn: 5%
                'cross_product_discount' => 10.00, // Giảm khi mua sản phẩm khác hoặc gia hạn: 10%
                'first_purchase_discount' => 30.00, // Giảm % lần đầu mua (khác với lần đầu upgrade)
                'status' => PackageUpgradeConfig::STATUS_ACTIVE,
            ],
            [
                'tool_type' => PackageUpgradeConfig::TOOL_TYPE_GO_SOFT,
                'first_upgrade_discount_first_month' => 30.00, // Lần đầu up gói trong tháng đầu: 30%
                'second_upgrade_discount_first_month' => 20.00, // Lần 2 up gói trong tháng đầu: 20%
                'subsequent_upgrade_discount_first_month' => 20.00, // Lần 3-4-5 up gói trong tháng đầu: 20%
                'upgrade_discount_after_first_month' => 10.00, // Up gói sau tháng đầu: 10%
                'renewal_discount_after_expired' => 5.00, // Gia hạn sau khi hết hạn: 5%
                'cross_product_discount' => 10.00, // Giảm khi mua sản phẩm khác hoặc gia hạn: 10%
                'first_purchase_discount' => 30.00, // Giảm % lần đầu mua (khác với lần đầu upgrade)
                'status' => PackageUpgradeConfig::STATUS_ACTIVE,
            ],
            [
                'tool_type' => PackageUpgradeConfig::TOOL_TYPE_GO_BOT,
                'first_upgrade_discount_first_month' => 0.00,
                'second_upgrade_discount_first_month' => 0.00,
                'subsequent_upgrade_discount_first_month' => 0.00,
                'upgrade_discount_after_first_month' => 0.00,
                'renewal_discount_after_expired' => 0.00,
                'cross_product_discount' => 10.00, // Giảm khi mua sản phẩm khác: 10%
                'first_purchase_discount' => 30.00, // Giảm % lần đầu mua
                'status' => PackageUpgradeConfig::STATUS_ACTIVE,
            ],
            [
                'tool_type' => PackageUpgradeConfig::TOOL_TYPE_GO_QUICK,
                'first_upgrade_discount_first_month' => 0.00,
                'second_upgrade_discount_first_month' => 0.00,
                'subsequent_upgrade_discount_first_month' => 0.00,
                'upgrade_discount_after_first_month' => 0.00,
                'renewal_discount_after_expired' => 0.00,
                'cross_product_discount' => 10.00, // Giảm khi mua sản phẩm khác: 10%
                'first_purchase_discount' => 30.00, // Giảm % lần đầu mua
                'status' => PackageUpgradeConfig::STATUS_ACTIVE,
            ],
        ];

        foreach ($configs as $config) {
            PackageUpgradeConfig::updateOrCreate(
                ['tool_type' => $config['tool_type']],
                $config
            );
        }
    }
}

