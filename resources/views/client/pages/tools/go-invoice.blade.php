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
        </div>



    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-invoice.css')
@endpush
