<?php

namespace Database\Seeders;

use App\Models\GoInvoicePackage;
use Illuminate\Database\Seeder;

class GoInvoicePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonFeatures = [
            'Tải bảng kê chi tiết hóa đơn mua vào & bán ra hàng loạt.',
            'Thời gian tải: Tháng - Quý - Năm.',
            'Không giới hạn số lượng hóa đơn.',
            'Lấy link hóa đơn gốc từ NCC.',
            'Xuất dữ liệu Excel, XML, PDF.',
            'Không giới hạn thiết bị truy cập.',
        ];

        $packages = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'price' => 300000,
                'mst_limit' => 1,
                'license_fee' => 500000,
                'discount_percent' => 0,
                'badge' => null,
                'order' => 1,
                'status' => 'active',
                'description' => 'Gói cơ bản phù hợp cho cá nhân và hộ kinh doanh nhỏ',
                'features' => $commonFeatures,
            ],
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'price' => 1000000,
                'mst_limit' => 5,
                'license_fee' => 500000,
                'discount_percent' => 30,
                'badge' => null,
                'order' => 2,
                'status' => 'active',
                'description' => 'Gói tiêu chuẩn cho doanh nghiệp vừa',
                'features' => $commonFeatures,
            ],
            [
                'name' => 'Advanced',
                'slug' => 'advanced',
                'price' => 1500000,
                'mst_limit' => 10,
                'license_fee' => 500000,
                'discount_percent' => 50,
                'badge' => 'Most Popular',
                'order' => 3,
                'status' => 'active',
                'description' => 'Gói nâng cao được nhiều khách hàng lựa chọn',
                'features' => $commonFeatures,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 2000000,
                'mst_limit' => 20,
                'license_fee' => 500000,
                'discount_percent' => 70,
                'badge' => 'Most Popular',
                'order' => 4,
                'status' => 'active',
                'description' => 'Gói chuyên nghiệp cho doanh nghiệp lớn',
                'features' => $commonFeatures,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'price' => 2500000,
                'mst_limit' => 30,
                'license_fee' => 500000,
                'discount_percent' => 75,
                'badge' => null,
                'order' => 5,
                'status' => 'active',
                'description' => 'Gói cao cấp với nhiều tính năng ưu việt',
                'features' => $commonFeatures,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 3000000,
                'mst_limit' => 50,
                'license_fee' => 500000,
                'discount_percent' => 80,
                'badge' => 'Best Choice',
                'order' => 6,
                'status' => 'active',
                'description' => 'Gói doanh nghiệp với quy mô lớn',
                'features' => $commonFeatures,
            ],
        ];

        foreach ($packages as $package) {
            GoInvoicePackage::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}

