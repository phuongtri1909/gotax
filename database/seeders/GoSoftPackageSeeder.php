<?php

namespace Database\Seeders;

use App\Models\GoSoftPackage;
use Illuminate\Database\Seeder;

class GoSoftPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonFeatures = [
            'Tải tờ khai, giấy nộp tiền, thông báo hàng loạt.',
            'Thời gian tải: Tháng - Quý - Năm.',
            'Chuyển file XML thành file Excel.',
            'Tải về dữ liệu dưới dạng XML.',
            'Không giới hạn số lượng tra cứu.',
            'Không giới hạn thiết bị truy cập.',
        ];

        $packages = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'price' => 200000,
                'mst_limit' => 1,
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
                'price' => 800000,
                'mst_limit' => 5,
                'discount_percent' => 20,
                'badge' => null,
                'order' => 2,
                'status' => 'active',
                'description' => 'Gói tiêu chuẩn cho doanh nghiệp vừa',
                'features' => $commonFeatures,
            ],
            [
                'name' => 'Advanced',
                'slug' => 'advanced',
                'price' => 1200000,
                'mst_limit' => 10,
                'discount_percent' => 40,
                'badge' => 'Most Popular',
                'order' => 3,
                'status' => 'active',
                'description' => 'Gói nâng cao được nhiều khách hàng lựa chọn',
                'features' => $commonFeatures,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 1500000,
                'mst_limit' => 20,
                'discount_percent' => 60,
                'badge' => null,
                'order' => 4,
                'status' => 'active',
                'description' => 'Gói chuyên nghiệp cho doanh nghiệp lớn',
                'features' => $commonFeatures,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'price' => 1800000,
                'mst_limit' => 30,
                'discount_percent' => 70,
                'badge' => 'Most Popular',
                'order' => 5,
                'status' => 'active',
                'description' => 'Gói cao cấp với nhiều tính năng ưu việt',
                'features' => $commonFeatures,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 2000000,
                'mst_limit' => 50,
                'discount_percent' => 80,
                'badge' => 'Best Choice',
                'order' => 6,
                'status' => 'active',
                'description' => 'Gói doanh nghiệp với quy mô lớn',
                'features' => $commonFeatures,
            ],
        ];

        foreach ($packages as $package) {
            GoSoftPackage::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}

