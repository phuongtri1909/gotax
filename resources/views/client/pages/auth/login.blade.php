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
                <h2 class="login-title">Đăng Nhập</h2>
                <p class="login-subtitle">Vui lòng nhập thông tin của bạn để tiến hành đăng nhập.</p>

                @include('components.toast-main')
                @include('components.toast')

                <form method="POST" action="{{ route('login.post') }}" class="login-form">
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

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Mật khẩu" required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword()">
                                <img src="{{ asset('/images/svg/eye.svg') }}" alt="Eye" class="eye-icon" id="password-icon">
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-options">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Nhớ mật khẩu</label>
                        </div>
                        <a href="{{ route('forgot-password') }}" class="forgot-password-link">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="login-button">
                        Đăng nhập
                    </button>
                </form>

                <div class="login-footer">
                    <p class="signup-text">
                        Bạn chưa có tài khoản? <a href="{{ route('register') }}" class="signup-link">Đăng ký</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (!passwordInput || !passwordIcon) {
                return;
            }

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                const eyeSlashPath = "{{ asset('/images/svg/eye-slash.svg') }}";
                passwordIcon.src = eyeSlashPath;
                passwordIcon.alt = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                const eyePath = "{{ asset('/images/svg/eye.svg') }}";
                passwordIcon.src = eyePath;
                passwordIcon.alt = 'Show Password';
            }
        }
    </script>
@endpush

@push('styles')
    @vite('resources/assets/frontend/css/pages/auth/login.css')
@endpush
