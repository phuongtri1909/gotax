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
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-invoice.css')
@endpush
