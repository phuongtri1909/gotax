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
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-invoice.css')
@endpush
