<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoSetting;

class SeoSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seoSettings = [
            [
                'page_key' => 'home',
                'title' => 'Trang chủ - ' . config('app.name'),
                'description' => config('app.name') . ' - Hệ thống công cụ hỗ trợ kế toán và quản lý thuế chuyên nghiệp. Giải pháp tra cứu MST, CCCD, tải tờ khai thuế và quản lý hóa đơn điện tử hiệu quả.',
                'keywords' => config('app.name') . ', công cụ kế toán, tra cứu MST, tra cứu CCCD, tờ khai thuế, hóa đơn điện tử, quản lý thuế',
                'is_active' => true
            ],
            [
                'page_key' => 'contact',
                'title' => 'Liên hệ - ' . config('app.name'),
                'description' => 'Liên hệ với ' . config('app.name') . ' để được tư vấn và hỗ trợ về các công cụ kế toán, tra cứu MST, CCCD và quản lý thuế.',
                'keywords' => 'liên hệ, tư vấn, hỗ trợ, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'faqs',
                'title' => 'Câu hỏi thường gặp - ' . config('app.name'),
                'description' => 'Tìm hiểu các câu hỏi thường gặp về ' . config('app.name') . ' và các công cụ hỗ trợ kế toán, tra cứu MST, CCCD.',
                'keywords' => 'câu hỏi thường gặp, FAQ, hướng dẫn, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'go-invoice',
                'title' => 'Go Invoice - Hóa đơn điện tử - ' . config('app.name'),
                'description' => 'Go Invoice - Công cụ quản lý và tạo hóa đơn điện tử chuyên nghiệp. Hỗ trợ tạo hóa đơn nhanh chóng, quản lý khách hàng và xuất báo cáo chi tiết.',
                'keywords' => 'Go Invoice, hóa đơn điện tử, tạo hóa đơn, quản lý hóa đơn, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'go-bot',
                'title' => 'Go Bot - Tra cứu MST hàng loạt - ' . config('app.name'),
                'description' => 'Go Bot - Công cụ tra cứu mã số thuế (MST) hàng loạt tự động. Tra cứu MST cá nhân, doanh nghiệp, tra cứu rủi ro nhà cung cấp và địa chỉ doanh nghiệp.',
                'keywords' => 'Go Bot, tra cứu MST, tra cứu mã số thuế, tra cứu hàng loạt, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'go-soft',
                'title' => 'Go Soft - Tải tờ khai thuế - ' . config('app.name'),
                'description' => 'Go Soft - Công cụ tải tờ khai, giấy nộp tiền và thông báo thuế hàng loạt. Chuyển đổi file XML sang Excel, không giới hạn số lượng tra cứu.',
                'keywords' => 'Go Soft, tải tờ khai thuế, giấy nộp tiền, XML to Excel, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'go-quick',
                'title' => 'Go Quick - Đọc CCCD tự động - ' . config('app.name'),
                'description' => 'Go Quick - Công cụ đọc và trích xuất thông tin từ CCCD tự động. Hỗ trợ tải ảnh CCCD, quét hàng loạt, đọc đa dạng file PDF, Excel và folder chứa ảnh.',
                'keywords' => 'Go Quick, đọc CCCD, trích xuất CCCD, OCR CCCD, quét CCCD, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'go-invoice-trial',
                'title' => 'Đăng ký dùng thử Go Invoice - ' . config('app.name'),
                'description' => 'Đăng ký dùng thử Go Invoice miễn phí. Trải nghiệm công cụ quản lý hóa đơn điện tử chuyên nghiệp ngay hôm nay.',
                'keywords' => 'dùng thử Go Invoice, đăng ký Go Invoice, trial Go Invoice, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'go-bot-trial',
                'title' => 'Đăng ký dùng thử Go Bot - ' . config('app.name'),
                'description' => 'Đăng ký dùng thử Go Bot miễn phí. Trải nghiệm công cụ tra cứu MST hàng loạt tự động ngay hôm nay.',
                'keywords' => 'dùng thử Go Bot, đăng ký Go Bot, trial Go Bot, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'go-soft-trial',
                'title' => 'Đăng ký dùng thử Go Soft - ' . config('app.name'),
                'description' => 'Đăng ký dùng thử Go Soft miễn phí. Trải nghiệm công cụ tải tờ khai thuế hàng loạt ngay hôm nay.',
                'keywords' => 'dùng thử Go Soft, đăng ký Go Soft, trial Go Soft, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'go-quick-trial',
                'title' => 'Đăng ký dùng thử Go Quick - ' . config('app.name'),
                'description' => 'Đăng ký dùng thử Go Quick miễn phí. Trải nghiệm công cụ đọc CCCD tự động ngay hôm nay.',
                'keywords' => 'dùng thử Go Quick, đăng ký Go Quick, trial Go Quick, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'profile',
                'title' => 'Hồ sơ cá nhân - ' . config('app.name'),
                'description' => 'Quản lý hồ sơ cá nhân, cập nhật thông tin tài khoản và avatar trên ' . config('app.name') . '.',
                'keywords' => 'hồ sơ cá nhân, profile, thông tin tài khoản, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'login',
                'title' => 'Đăng nhập - ' . config('app.name'),
                'description' => 'Đăng nhập vào tài khoản ' . config('app.name') . ' để sử dụng các công cụ kế toán và quản lý thuế chuyên nghiệp.',
                'keywords' => 'đăng nhập, login, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'register',
                'title' => 'Đăng ký tài khoản - ' . config('app.name'),
                'description' => 'Đăng ký tài khoản miễn phí trên ' . config('app.name') . ' để sử dụng các công cụ kế toán và quản lý thuế chuyên nghiệp.',
                'keywords' => 'đăng ký, register, tạo tài khoản, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'forgot-password',
                'title' => 'Quên mật khẩu - ' . config('app.name'),
                'description' => 'Khôi phục mật khẩu tài khoản ' . config('app.name') . '. Nhập email để nhận liên kết đặt lại mật khẩu.',
                'keywords' => 'quên mật khẩu, forgot password, khôi phục mật khẩu, ' . config('app.name'),
                'is_active' => true
            ],
            [
                'page_key' => 'account-settings',
                'title' => 'Thiết lập tài khoản - ' . config('app.name'),
                'description' => 'Thiết lập và quản lý tài khoản trên ' . config('app.name') . '. Đổi mật khẩu và cập nhật thông tin tài khoản.',
                'keywords' => 'thiết lập tài khoản, account settings, đổi mật khẩu, ' . config('app.name'),
                'is_active' => true
            ],
        ];

        foreach ($seoSettings as $setting) {
            SeoSetting::updateOrCreate(
                ['page_key' => $setting['page_key']],
                $setting
            );
        }
    }
}
