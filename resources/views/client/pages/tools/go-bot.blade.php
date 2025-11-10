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
                    'subtitle' => 'Đơn giản hoá công việc kế toán, kiểm tra rủi ro nhà cung cấp, tra MST cá nhân hàng loạt.',
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
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-invoice.css')
@endpush
