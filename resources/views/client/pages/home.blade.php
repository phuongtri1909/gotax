@extends('client.layouts.app')
@section('title', 'Home - ' . config('app.name'))
@section('description', config('app.name') . '')
@section('keywords', config('app.name'))

@section('content')
    <section class="home-page container-page">

        @php
            $banners = [
                [
                    'id' => 1,
                    'title' => 'Nền Tảng Công Nghệ Cho Kế Toán',
                    'subtitle' =>
                        'Đơn giản hoá công việc kế toán, kiểm tra rủi ro, tải tờ khai và đọc CCCD nhanh chóng.',
                    'button_text' => 'Dùng Thử Miễn Phí',
                    'button_link' => '#',
                    'image' => asset('images/dev/banner.png'),
                    'overlay_opacity' => 0.2,
                ],
                [
                    'id' => 2,
                    'title' => 'Giải Pháp Kế Toán Thông Minh',
                    'subtitle' => 'Tự động hóa quy trình kế toán, tiết kiệm thời gian và tăng hiệu quả làm việc.',
                    'button_text' => 'Bắt Đầu Ngay',
                    'button_link' => '#',
                    'image' => asset('images/dev/banner.png'),
                    'overlay_opacity' => 0.2,
                ],
                [
                    'id' => 3,
                    'title' => 'Công Nghệ Cho Tương Lai',
                    'subtitle' => 'Trải nghiệm dịch vụ kế toán hiện đại với công nghệ AI và tự động hóa.',
                    'button_text' => 'Khám Phá Ngay',
                    'button_link' => '#',
                    'image' => asset('images/dev/banner.png'),
                    'overlay_opacity' => 0.2,
                ],
            ];
        @endphp
        <x-banner-home :banners="$banners" />
        <div class="container">
            <x-tools-section />
            <x-smart-support-section />
            <x-statistics-section />
            <x-get-more-section />
            <x-testimonials-section />

            @guest
                <x-cta-banner-section />
            @endguest
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/home.css')
@endpush
