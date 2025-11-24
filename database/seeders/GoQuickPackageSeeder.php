<?php

namespace Database\Seeders;

use App\Models\GoQuickPackage;
use Illuminate\Database\Seeder;

class GoQuickPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'price' => 200000,
                'cccd_limit' => 1000,
                'discount_percent' => 0,
                'order' => 1,
                'status' => 'active',
                'description' => 'Gói cơ bản phù hợp cho nhu cầu đọc CCCD nhỏ',
            ],
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'price' => 600000,
                'cccd_limit' => 5000,
                'discount_percent' => 40,
                'order' => 2,
                'status' => 'active',
                'description' => 'Gói tiêu chuẩn cho doanh nghiệp vừa',
            ],
            [
                'name' => 'Advanced',
                'slug' => 'advanced',
                'price' => 1000000,
                'cccd_limit' => 10000,
                'discount_percent' => 50,
                'order' => 3,
                'status' => 'active',
                'description' => 'Gói nâng cao với số lượng đọc lớn',
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 1500000,
                'cccd_limit' => 20000,
                'discount_percent' => 60,
                'order' => 4,
                'status' => 'active',
                'description' => 'Gói chuyên nghiệp cho doanh nghiệp lớn',
            ],
        ];

        foreach ($packages as $package) {
            GoQuickPackage::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}

