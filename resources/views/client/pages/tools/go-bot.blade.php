@extends('client.layouts.app')
@section('title', 'Go Bot - ' . config('app.name'))
@section('description', 'Go Bot - ' . config('app.name'))
@section('keywords', 'Go Bot, ' . config('app.name'))

@section('content')
    <section class="home-page container-page">
        @php
            $banners = [
                [
                    'id' => 1,
                    'name' => 'Go Bot',
                    'title' => '- Giải Pháp Kiểm Soát Rủi Ro MST DN',
                    'subtitle' =>
                        'Đơn giản hoá công việc kế toán, kiểm tra rủi ro nhà cung cấp, tra MST cá nhân hàng loạt.',
                    'button_text' => 'Dùng Thử Miễn Phí',
                    'button_link' => '#',
                    'image' => asset('images/d/tools/banner-bot.png'),
                    'overlay_opacity' => 0.2,
                ],
            ];
        @endphp
        <x-banner-home :banners="$banners" />
        <div class="container">
            <x-tools-section />

            <x-tool-hero title="Kiểm Soát Rủi Ro Của Doanh Nghiệp"
                description="Công cụ tra cứu MST hàng loạt – Nhanh, Chính xác, Bảo mật"
                titleLeft="công cụ tra Rủi Ro MST DN hàng loạt!"
                descriptionLeft="Tăng tốc quy trình tra cứu MST Doanh nghiệp và cá nhân. Công cụ thông minh, giao diện dễ dùng, hỗ trợ tra cứu mã số thuế nhanh chóng, chính xác và dễ dàng."
                primaryText="Đăng Ký Ngay!" primaryLink="#" secondaryText="Dùng Thử" secondaryLink="{{ route('tools.go-invoice.trial') }}" :image="asset('images/d/tools/body-bot.png')" />

            @php
                $features = [
                    [
                        'icon' => asset('images/d/tools/clock.png'),
                        'title' => 'Tiết kiệm thời gian',
                        'description' =>
                            'Không còn thao tra cứu từng MST cá nhân và doanh nghiệp – chỉ cần vài cú nhấp chuột là có thể tra cứu hàng loạt MST cùng lúc.',
                    ],
                    [
                        'icon' => asset('images/d/tools/money.png'),
                        'title' => 'Tối ưu chi phí kinh doanh',
                        'description' =>
                            'Hộ kinh doanh, Doanh nghiệp hay Công ty dịch vụ kế toán – đều dễ dàng sử dụng hiệu quả và tối ưu chi phí.',
                    ],
                    [
                        'icon' => asset('images/d/tools/shield.png'),
                        'title' => 'Tuân thủ nguyên tắc bảo mật',
                        'description' => 'Hệ thống được xây dựng theo tiêu chuẩn bảo mật thông tin dành cho các cá nhân và doanh nghiệp.',
                    ],
                    [
                        'icon' => asset('images/d/tools/success.png'),
                        'title' => 'Chính xác tuyệt đối',
                        'description' =>
                            'MST được tra cứu trực tiếp từ hệ thống Tổng cục Thuế, đảm bảo chính xác và minh bạch.',
                    ],
                ];
            @endphp

            <x-features-section title="Giải Pháp Tối Ưu Công Việc Của Bạn" :features="$features" />

            @php
                $botFeatures = [
                    'Tra hàng loạt MST cá nhân cũ → MST cá nhân mới',
                    'Tra hàng loạt CCCD → MST cá nhân và doanh nghiệp',
                    'Tra cứu rủi ro nhà cung cấp hàng loạt',
                    'Tra địa chỉ Doanh nghiệp sau sáp nhập chuẩn xác từ TCT',
                    'Hỗ trợ tra cứu file Excel và TXT nhanh chóng',
                    'Trích xuất dữ liệu hàng loạt ra file Excel',
                ];

                $botPackages = [
                    (object) [
                        'name' => 'Basic',
                        'title' => 'Số lượng MST',
                        'mst' => '1000',
                        'price' => '200.000',
                        'badge' => 'Ưu đãi',
                    ],
                    (object) [
                        'name' => 'Standard',
                        'title' => 'Số lượng MST',
                        'mst' => '5000',
                        'price' => '600.000',
                        'badge' => 'Giảm 40%',
                    ],
                    (object) [
                        'name' => 'Advanced',
                        'title' => 'Số lượng MST',
                        'mst' => '10.000',
                        'price' => '1.000.000',
                        'badge' => 'Giảm 50%',
                    ],
                    (object) [
                        'name' => 'Pro',
                        'title' => 'Số lượng MST',
                        'mst' => '20.000',
                        'price' => '1.500.000',
                        'badge' => 'Giảm 60%',
                    ],
                ];
            @endphp

            <x-pricing-bot-section :features="$botFeatures" :packages="$botPackages" />
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-invoice.css')
@endpush
