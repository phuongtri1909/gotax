@extends('client.layouts.app')
@section('title', 'Go Invoice - ' . config('app.name'))
@section('description', 'Go Invoice - ' . config('app.name'))
@section('keywords', 'Go Invoice, ' . config('app.name'))

@section('content')
    <section class="home-page container-page">
        @php
            $banners = [
                [
                    'id' => 1,
                    'name' => 'Go Invoice',
                    'title' => '- Giải Pháp Quản Lý Hoá Đơn',
                    'subtitle' => 'Đơn giản hoá công việc kế toán, đồng bộ tải hoá đơn điện tử hàng loạt.',
                    'button_text' => 'Dùng Thử Miễn Phí',
                    'button_link' => '#',
                    'image' => asset('images/d/tools/banner-invoice.png'),
                    'overlay_opacity' => 0.2,
                ],
            ];
        @endphp
        <x-banner-home :banners="$banners" />

        <div class="container">
            <x-tools-section />

            <x-tool-hero title="Đơn giản hóa quy trình lấy hóa đơn điện tử"
                description="Công cụ tải hóa đơn điện tử hàng loạt – Nhanh, Chính xác, Bảo mật"
                titleLeft="công cụ tải hoá đơn Điện Tử hàng loạt!"
                descriptionLeft="Tăng tốc quy trình tải hóa đơn điện tử. Công cụ thông minh, giao diện dễ dùng, hỗ trợ mọi quy mô doanh nghiệp và đảm bảo dữ liệu luôn an toàn tuyệt đối."
                primaryText="Đăng Ký Ngay!" primaryLink="#" secondaryText="Dùng Thử" secondaryLink="#" :image="asset('images/d/tools/body-invoice.png')" />

            @php
                $features = [
                    [
                        'icon' => asset('images/d/tools/clock.png'),
                        'title' => 'Tiết kiệm thời gian',
                        'description' => 'Không còn thao tác thủ công từng hóa đơn – chỉ cần vài cú nhấp chuột là có thể tải hàng nghìn hóa đơn cùng lúc.',
                    ],
                    [
                        'icon' => asset('images/d/tools/money.png'),
                        'title' => 'Tối ưu chi phí kinh doanh',
                        'description' => 'Hộ kinh doanh, Doanh nghiệp hay Công ty dịch vụ kế toán – tất cả đều dễ dàng sử dụng hiệu quả và tối ưu chi phí.',
                    ],
                    [
                        'icon' => asset('images/d/tools/shield.png'),
                        'title' => 'Tuân thủ nguyên tắc bảo mật',
                        'description' => 'Hệ thống áp dụng các tiêu chuẩn bảo mật chặt chẽ, đảm bảo an toàn tuyệt đối cho dữ liệu kế toán.',
                    ],
                    [
                        'icon' => asset('images/d/tools/success.png'),
                        'title' => 'Chính xác tuyệt đối',
                        'description' => 'Hóa đơn được truy xuất trực tiếp từ hệ thống Tổng cục Thuế, đảm bảo chính xác và minh bạch.',
                    ],
                ];
            @endphp

            <x-features-section title="Giải Pháp Tối Ưu Công Việc Của Bạn" :features="$features" />

            @php
                $commonFeatures = [
                    'Tải bảng kê chi tiết hóa đơn mua vào & bán ra hàng loạt.',
                    'Thời gian tải: Tháng - Quý - Năm.',
                    'Không giới hạn số lượng hóa đơn.',
                    'Lấy link hóa đơn gốc từ NCC.',
                    'Xuất dữ liệu Excel, XML, PDF.',
                    'Không giới hạn thiết bị truy cập.',
                ];

                // Convert arrays to objects
                $packages = [
                    (object) [
                        'name' => 'Basic',
                        'price' => '300.000',
                        'mst' => '1',
                        'discount' => 'Ưu đãi',
                        'badge' => null,
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Standard',
                        'price' => '1.000.000',
                        'mst' => '5',
                        'discount' => 'Giảm 30%',
                        'badge' => null,
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Advanced',
                        'price' => '1.500.000',
                        'mst' => '10',
                        'discount' => 'Giảm 50%',
                        'badge' => 'Most Popular',
                        'badge_type' => 'popular',
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Pro',
                        'price' => '2.000.000',
                        'mst' => '20',
                        'discount' => 'Giảm 70%',
                        'badge' => 'Most Popular',
                        'badge_type' => 'popular',
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Premium',
                        'price' => '2.500.000',
                        'mst' => '30',
                        'discount' => 'Giảm 75%',
                        'badge' => null,
                        'features' => $commonFeatures,
                        'button_text' => 'Đăng ký',
                        'button_link' => '#',
                    ],
                    (object) [
                        'name' => 'Enterprise',
                        'price' => '3.000.000',
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
