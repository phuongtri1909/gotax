@extends('client.layouts.app')
@section('title', 'Go Bot - Tra Cứu Mã Số Thuế Hàng Loạt')
@section('description', 'Go Bot - Tra Cứu Mã Số Thuế Hàng Loạt')
@section('keyword', 'Go Bot, Tra Cứu Mã Số Thuế')

@section('content')
    <section class="go-bot-section">
        <div class="container">
            <!-- Header -->
            <div class="go-bot-header text-center">
                <h1 class="go-bot-title">Go Bot</h1>
                <p class="go-bot-subtitle">Tra Cứu Mã Số Thuế Hàng Loạt</p>
            </div>
            
            <!-- Content -->
            <div class="go-bot-content">
                @yield('go-bot-content')
            </div>
            
            <!-- Back Button -->
            <div class="go-bot-back">
                <a href="{{ route('tools.go-bot') }}" class="btn btn-back-go-bot">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Trở lại</span>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Modals -->
    @stack('modals')
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-bot.css')
@endpush

