@extends('client.layouts.app')
@section('title', 'Go Soft - ' . config('app.name'))
@section('description', 'Go Soft - ' . config('app.name'))
@section('keywords', 'Go Soft, ' . config('app.name'))

@section('content')
    <section class="home-page container-page">
        @php
            $banners = [
                [
                    'id' => 1,
                    'name' => 'Go Soft',
                    'title' => '- Giải Pháp Tra Cứu Tờ Khai',
                    'subtitle' => 'Đơn giản hoá công việc kế toán, tải tờ khai hàng loạt.',
                    'button_text' => 'Dùng Thử Miễn Phí',
                    'button_link' => '#',
                    'image' => asset('images/d/tools/banner-soft.png'),
                    'overlay_opacity' => 0.2,
                ],
            ];
        @endphp
        <x-banner-home :banners="$banners" />

        <div class="container">
            <x-tools-section />

            <x-tool-hero title="Giải pháp tra cứu & tải tờ khai doanh nghiệp"
                description="Công cụ tra cứu tờ khai hàng loạt – Nhanh, Chính xác, Bảo mật"
                titleLeft="công cụ tra cứu tờ khai DN hàng loạt!"
                descriptionLeft="Đơn giản hóa quy trình tra cứu và tải tờ khai. Giao diện dễ dùng, tốc độ xử lý cao, chính xác phù hợp cho kế toán, doanh nghiệp và dịch vụ hành chính."
                primaryText="Đăng Ký Ngay!" primaryLink="#" secondaryText="Dùng Thử" secondaryLink="#" :image="asset('images/d/tools/body-soft.png')" />

            @php
                $features = [
                    [
                        'icon' => asset('images/d/tools/clock.png'),
                        'title' => 'Tiết kiệm thời gian',
                        'description' => 'Tự động hóa toàn bộ quy trình tra cứu và tải tờ khai, giúp bạn xử lý hàng loạt dữ liệu chỉ trong vài phút thay vì hàng giờ thao tác thủ công.',
                    ],
                    [
                        'icon' => asset('images/d/tools/money.png'),
                        'title' => 'Tối ưu chi phí kinh doanh',
                        'description' => 'Hộ kinh doanh, Doanh nghiệp hay Công ty dịch vụ kế toán – đều dễ dàng sử dụng hiệu quả và tối ưu chi phí.',
                    ],
                    [
                        'icon' => asset('images/d/tools/shield.png'),
                        'title' => 'Tuân thủ nguyên tắc bảo mật',
                        'description' => 'Hệ thống được xây dựng theo tiêu chuẩn bảo mật thông tin dành cho các cá nhân và doanh nghiệp.',
                    ],
                    [
                        'icon' => asset('images/d/tools/success.png'),
                        'title' => 'Chính xác tuyệt đối',
                        'description' => 'Tờ khai được tra cứu trực tiếp từ hệ thống Dịch vụ Công, đảm bảo chính xác và minh bạch.',
                    ],
                ];
            @endphp

            <x-features-section title="Giải Pháp Tối Ưu Công Việc Của Bạn" :features="$features" />

            @php
                $commonFeatures = [
                    'Tải tờ khai, giấy nộp tiền, thông báo hàng loạt.',
                    'Thời gian tải: Tháng - Quý - Năm.',
                    'Chuyển file XML thành file Excel.',
                    'Tải về dữ liệu dưới dạng XML.',
                    'Không giới hạn số lượng tra cứu.',
                    'Không giới hạn thiết bị truy cập.',
                ];

                // Convert arrays to objects
                $packages = [
                    (object) [
                        'name' => 'Basic',
                        'price' => '200.000',
                        'mst' => '1',
                        'discount' => 'Ưu đãi',
                        'badge' => null,
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Standard',
                        'price' => '800.000',
                        'mst' => '5',
                        'discount' => 'Giảm 20%',
                        'badge' => null,
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Advanced',
                        'price' => '1.200.000',
                        'mst' => '10',
                        'discount' => 'Giảm 40%',
                        'badge' => 'Most Popular',
                        'badge_type' => 'popular',
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Pro',
                        'price' => '1.500.000',
                        'mst' => '20',
                        'discount' => 'Giảm 60%',
                        'badge' => null,
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Premium',
                        'price' => '1.800.000',
                        'mst' => '30',
                        'discount' => 'Giảm 70%',
                        'badge' => 'Most Popular',
                        'badge_type' => 'popular',
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Enterprise',
                        'price' => '2.000.000',
                        'mst' => '50',
                        'discount' => 'Giảm 80%',
                        'badge' => 'Best Choice',
                        'badge_type' => 'choice',
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                ];
            @endphp

            <x-pricing-section :packages="$packages" />
        </div>



    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-invoice.css')
@endpush
