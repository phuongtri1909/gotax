@extends('client.layouts.app')
@section('title', '')
@section('description', '')
@section('keyword', '')


@section('content')
    <div class="login-page-container container container-page">
        <div class="login-header-section">
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-site.webp') }}" alt="{{ config('app.name') }}" class="logo-img">
            </div>
            <p class="login-tagline">Nền Tảng Công Nghệ Cho Kế Toán</p>
        </div>

        <div class="login-container">
            <div class="login-card">
                <h2 class="login-title">Quên Mật Khẩu</h2>
                <p class="login-subtitle">Vui lòng nhập email để khôi phục lại mật khẩu.</p>

                @include('components.toast-main')
                @include('components.toast')

                <form method="POST" action="{{ route('forgot-password.post') }}" class="login-form">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}"
                            placeholder="Tài khoản" required autofocus>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="login-button">
                        Gửi Liên Kết
                    </button>
                </form>

                <div class="login-footer">
                    <p class="signup-text">
                        <a href="{{ route('login') }}" class="signup-link">Quay lại để Đăng nhập</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/auth/login.css')
@endpush

