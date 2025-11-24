<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trial;

class TrialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trials = [
            [
                'tool_type' => Trial::TOOL_INVOICE,
                'mst_limit' => 1,
                'cccd_limit' => null,
                'expires_days' => 14, // 2 tuần
                'description' => 'GoTax dành tặng Quý Khách 2 tuần miễn phí để trải nghiệm Go Invoice với 1 MST. Sau đó, Quý Khách có thể đánh giá và cân nhắc đăng ký chính thức.',
                'status' => true,
            ],
            [
                'tool_type' => Trial::TOOL_BOT,
                'mst_limit' => 10,
                'cccd_limit' => null,
                'expires_days' => null,
                'description' => 'GoTax dành tặng Quý Khách 10 lượt tra cứu MST miễn phí để trải nghiệm Go Bot. Sau đó, Quý Khách có thể đánh giá và cân nhắc đăng ký chính thức.',
                'status' => true,
            ],
            [
                'tool_type' => Trial::TOOL_SOFT,
                'mst_limit' => 1,
                'cccd_limit' => null,
                'expires_days' => 14, // 2 tuần
                'description' => 'GoTax dành tặng Quý Khách 2 tuần miễn phí để trải nghiệm Go Soft với 1 MST. Sau đó, Quý Khách có thể đánh giá và cân nhắc đăng ký chính thức.',
                'status' => true,
            ],
            [
                'tool_type' => Trial::TOOL_QUICK,
                'mst_limit' => null,
                'cccd_limit' => 10,
                'expires_days' => null,
                'description' => 'GoTax dành tặng Quý Khách 10 lượt đọc CCCD miễn phí để trải nghiệm Go Quick. Sau đó, Quý Khách có thể đánh giá và cân nhắc đăng ký chính thức.',
                'status' => true,
            ],
        ];

        foreach ($trials as $trial) {
            Trial::updateOrCreate(
                ['tool_type' => $trial['tool_type']],
                $trial
            );
        }
    }
}
