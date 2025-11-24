<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'text' => 'Mỗi kỳ kê khai thuế, team mình gần như \'giải phóng\' được cả tuần nhờ vào công cụ này. Không thể thiếu được nữa!',
                'rating' => 5,
                'name' => 'Thuy Le Thi',
                'avatar' => null,
                'order' => 1,
                'status' => true,
            ],
            [
                'text' => 'Trợ thủ đắc lực cho dân kế toán - cảm giác như có thêm một đồng nghiệp chỉ chuyên lo phần tra cứu và tải dữ liệu. Nhẹ đầu hơn hẳn mỗi kỳ quyết toán.',
                'rating' => 5,
                'name' => 'Nguyen Thi Hue',
                'avatar' => null,
                'order' => 2,
                'status' => true,
            ],
            [
                'text' => 'Giải pháp quá tuyệt vời cho bộ phận kế toán doanh nghiệp vừa và nhỏ. Tiết kiệm được nhiều nhân sự và công sức cho công việc kiểm tra và tải dữ liệu thuế.',
                'rating' => 5,
                'name' => 'Phuong Anh Dao',
                'avatar' => null,
                'order' => 3,
                'status' => true,
            ],
            [
                'text' => 'Rất tiện lợi cho dân kế toán như mình. Tải hóa đơn, tờ khai số lượng lớn mà không lo lỗi hệ thống hay quá tải.',
                'rating' => 5,
                'name' => 'Dao Minh Duc',
                'avatar' => null,
                'order' => 4,
                'status' => true,
            ],
            [
                'text' => 'Công cụ này giúp mình tiết kiệm rất nhiều thời gian trong việc tra cứu và tải các tờ khai thuế. Giao diện dễ sử dụng, thao tác nhanh chóng.',
                'rating' => 5,
                'name' => 'Tran Van Anh',
                'avatar' => null,
                'order' => 5,
                'status' => true,
            ],
            [
                'text' => 'Từ khi sử dụng Go Suite, công việc kế toán của mình trở nên đơn giản hơn rất nhiều. Đặc biệt là tính năng đọc CCCD rất tiện lợi.',
                'rating' => 5,
                'name' => 'Le Thi Mai',
                'avatar' => null,
                'order' => 6,
                'status' => true,
            ],
            [
                'text' => 'Hệ thống ổn định, không bị lỗi khi tải nhiều file cùng lúc. Mình đã giới thiệu cho nhiều đồng nghiệp sử dụng.',
                'rating' => 5,
                'name' => 'Hoang Van Nam',
                'avatar' => null,
                'order' => 7,
                'status' => true,
            ],
            [
                'text' => 'Dịch vụ hỗ trợ khách hàng rất tốt, phản hồi nhanh. Công cụ này thực sự là giải pháp hoàn hảo cho dân kế toán.',
                'rating' => 5,
                'name' => 'Nguyen Thi Lan',
                'avatar' => null,
                'order' => 8,
                'status' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
