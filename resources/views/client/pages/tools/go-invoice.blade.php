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
                    'button_link' => route('tools.trial.go-invoice'),
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
                primaryText="Đăng Ký Ngay!" primaryLink="#pricing-section" secondaryText="Dùng Thử" secondaryLink="{{ route('tools.trial.go-invoice') }}" :image="asset('images/d/tools/body-invoice.png')" />

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


            <x-pricing-section :packages="$packages" />
        </div>



    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-invoice.css')
@endpush
