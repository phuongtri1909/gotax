@extends('client.layouts.app')
@section('title', '404 - Page Not Found - ' . config('app.name'))
@section('description', '404 - Page Not Found')
@section('keywords', '404, page not found')

@section('content')
    <section class="error-page container-page">
        @include('components.title-page', [
            'title' => '404 - PAGE NOT FOUND',
            'breadcrumb' => [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Pages']
            ]
        ])

        <div class="container py-5">
            <div class="row align-items-center error-row py-5">
                <!-- Content Section - Left on Desktop, Right on Mobile -->
                <div class="col-12 col-lg-6 order-2 order-lg-1 error-content">
                    <h1 class="error-title">SORRY, PAGE NOT FOUND!</h1>
                    <div class="error-text-content">
                        <p class="error-subtitle">Oops! Bọn mình đang "tút tát" lại trang web!</p>
                        <p class="error-description-line">Trang sẽ hoạt động trở lại trong thời gian ngắn. Quay lại sau</p>
                        <p class="error-description-line">chút xíu nhé - Cảm ơn bạn đã đồng hành!</p>
                    </div>
                    <a href="{{ route('home') }}" class="error-button">
                        <span class="error-button-icon">
                            <img src="{{ asset('images/svg/arrow-left.svg') }}" alt="Arrow Left">
                        </span>
                        <span class="error-button-text text-1lg">Về Trang Chủ</span>
                    </a>
                </div>

                <!-- Illustration Section - Right on Desktop, Left on Mobile -->
                <div class="col-12 col-lg-6 order-1 order-lg-2 error-illustration">
                    <img src="{{ asset('images/d/errors/404.png') }}" 
                         alt="404 Error Illustration" 
                         onerror="this.src='https://via.placeholder.com/600x500/227447/FFFFFF?text=404+Error'">
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/error-pages.css')
@endpush
