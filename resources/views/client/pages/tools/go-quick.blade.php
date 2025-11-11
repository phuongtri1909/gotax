@extends('client.layouts.app')
@section('title', 'Go Quick - ' . config('app.name'))
@section('description', 'Go Quick - ' . config('app.name'))
@section('keywords', 'Go Quick, ' . config('app.name'))

@section('content')
    <section class="home-page container-page">
        @php
            $banners = [
                [
                    'id' => 1,
                    'name' => 'Go Quick',
                    'title' => '- Giải Pháp công nghệ cho kế toán',
                    'subtitle' => 'Đơn giản hoá công việc kế toán, đọc CCCD nhanh chóng hàng loạt.',
                    'button_text' => 'Dùng Thử Miễn Phí',
                    'button_link' => '#',
                    'image' => asset('images/d/tools/banner-quick.png'),
                    'overlay_opacity' => 0.2,
                ],
            ];
        @endphp
        <x-banner-home :banners="$banners" />
        <div class="container">
            <x-tools-section />

            <x-tool-hero title="Giải pháp số hóa thông tin CCCD nhanh chóng"
                description="Công cụ đọc CCCD hàng loạt – Nhanh, Chính xác, Bảo mật"
                titleLeft="công cụ Đọc CCCD hàng loạt!"
                descriptionLeft="Đọc dữ liệu CCCD  nhanh chóng, chính xác, an toàn, hỗ trợ xử lý hàng loạt và xuất ra file Excel để quản lý thuận tiện."
                primaryText="Đăng Ký Ngay!" primaryLink="#" secondaryText="Dùng Thử" secondaryLink="#" :image="asset('images/d/tools/body-quick.png')" />

            @php
                $features = [
                    [
                        'icon' => asset('images/d/tools/clock.png'),
                        'title' => 'Tiết kiệm thời gian',
                        'description' =>
                            'Công cụ tự động đọc và trích xuất dữ liệu từ CCCD, loại bỏ thao tác thủ công, rút ngắn thời gian làm việc từ hàng giờ xuống chỉ còn vài phút.',
                    ],
                    [
                        'icon' => asset('images/d/tools/money.png'),
                        'title' => 'Tối ưu chi phí kinh doanh',
                        'description' =>
                            'Tối ưu chi phí kinh doanh bằng cách giảm thời gian, công sức và nguồn lực dành cho những công việc lặp lại.',
                    ],
                    [
                        'icon' => asset('images/d/tools/shield.png'),
                        'title' => 'Tuân thủ nguyên tắc bảo mật',
                        'description' => 'Hệ thống đảm bảo tiêu chuẩn bảo mật cao, tuân thủ nguyên tắc an toàn thông tin để bảo vệ dữ liệu người dùng.',
                    ],
                    [
                        'icon' => asset('images/d/tools/success.png'),
                        'title' => 'Chính xác tuyệt đối',
                        'description' =>
                            'Loại bỏ sai sót, giảm thiểu rủi ro, đảm  đảm bảo chính xác và minh bạch.',
                    ],
                ];
            @endphp

            <x-features-section title="Giải Pháp Tối Ưu Công Việc Của Bạn" :features="$features" />

            @php
                $botFeatures = [
                    'Hỗ trợ tải ảnh CCCD lên để đọc dữ liệu tự động',
                    'Quét và trích xuất thông tin CCCD hàng loạt ',
                    'Hỗ trợ đọc đa dạng file: PDF, Excel, Folder chứa ảnh',
                    'Xử lý linh hoạt phân loại mặt trước, mặt sau CCCD',
                    'Tự động nhận diện thông tin khi nhập link ảnh',
                    'Trích xuất dữ liệu hàng loạt ra file Excel ',
                ];

                $botPackages = [
                    (object) [
                        'name' => 'Basic',
                        'title' => 'Số lượng CCCD',
                        'mst' => '1000',
                        'price' => '200.000',
                        'badge' => 'Ưu đãi',
                    ],
                    (object) [
                        'name' => 'Standard',
                        'title' => 'Số lượng CCCD',
                        'mst' => '5000',
                        'price' => '600.000',
                        'badge' => 'Giảm 40%',
                    ],
                    (object) [
                        'name' => 'Advanced',
                        'title' => 'Số lượng CCCD',
                        'mst' => '10.000',
                        'price' => '1.000.000',
                        'badge' => 'Giảm 50%',
                    ],
                    (object) [
                        'name' => 'Pro',
                        'title' => 'Số lượng CCCD',
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
