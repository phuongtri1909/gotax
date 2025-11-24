@extends('client.layouts.app')
@section('title', 'Thiết Lập Tài Khoản - ' . config('app.name'))
@section('description', 'Thiết lập tài khoản')
@section('keywords', 'account settings, thiết lập tài khoản')

@section('content')
    <section class="account-settings-page container-page">
        @include('components.title-page', [
            'title' => 'THIẾT LẬP TÀI KHOẢN',
            'breadcrumb' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Pages']],
        ])

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">
                    <div class="card account-settings-card">
                        <div class="card-body">
                            @include('components.toast-main')
                            @include('components.toast')

                            <form id="accountSettingsForm" method="POST" action="{{ route('change-password.post') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="email" class="form-label text-1lg account-settings-label">
                                        Email
                                    </label>
                                    <input type="email" class="form-control form-control-lg form-account-settings"
                                        id="email" value="{{ auth()->user()->email }}" readonly disabled>
                                </div>

                                <!-- Change Password Section -->
                                <div class="border-top pt-4 mb-4">
                                    <label class="text-1lg mb-1 account-settings-label">Đổi mật khẩu</label>
                                    <p class="text-muted small mb-3">Vui lòng nhập mật khẩu mới. Chúng tôi sẽ gửi email xác nhận đến bạn.</p>

                                    <!-- New Password Field -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label color-primary-13">
                                            Mật khẩu mới
                                        </label>
                                        <div class="position-relative password-input-wrapper">
                                            <input type="password"
                                                class="form-control form-control-lg form-account-settings @error('password') is-invalid @enderror" 
                                                id="password" name="password" placeholder="Mật khẩu" required>
                                            <button type="button"
                                                class="btn btn-link position-absolute top-50 end-0 translate-middle-y password-toggle-btn"
                                                onclick="togglePassword('password', 'password-icon')">
                                                <img src="{{ asset('/images/svg/eye.svg') }}" alt="Eye"
                                                    class="eye-icon" id="password-icon" width="20" height="20">
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password Field -->
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label color-primary-13">
                                            Nhập lại
                                        </label>
                                        <div class="position-relative password-input-wrapper">
                                            <input type="password"
                                                class="form-control form-control-lg form-account-settings @error('password_confirmation') is-invalid @enderror"
                                                id="password_confirmation" name="password_confirmation" placeholder="Mật khẩu" required>
                                            <button type="button"
                                                class="btn btn-link position-absolute top-50 end-0 translate-middle-y password-toggle-btn"
                                                onclick="togglePassword('password_confirmation', 'password-confirmation-icon')">
                                                <img src="{{ asset('/images/svg/eye.svg') }}" alt="Eye"
                                                    class="eye-icon" id="password-confirmation-icon" width="20"
                                                    height="20">
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary w-100 py-2 account-settings-btn-save">
                                                    Lưu
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="btn btn-outline-primary w-100 py-2 account-settings-btn-cancel"
                                                    onclick="resetForm()">
                                                    Huỷ
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/account-settings.css')
@endpush

@push('scripts')
    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);

            if (!passwordInput || !passwordIcon) {
                return;
            }

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.src = "{{ asset('/images/svg/eye-slash.svg') }}";
                passwordIcon.alt = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                passwordIcon.src = "{{ asset('/images/svg/eye.svg') }}";
                passwordIcon.alt = 'Show Password';
            }
        }

        function resetForm() {
            document.getElementById('accountSettingsForm').reset();
        }
    </script>
@endpush
