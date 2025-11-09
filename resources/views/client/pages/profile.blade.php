@extends('client.layouts.app')
@section('title', 'My Profile - ' . config('app.name'))
@section('description', 'Thông tin cá nhân')
@section('keywords', 'profile, thông tin cá nhân')

@section('content')
    <section class="profile-page container-page">
        @include('components.title-page', [
            'title' => 'MY PROFILE',
            'breadcrumb' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Pages']],
        ])

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">
                    <!-- Avatar Section -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ asset('images/default/avatar_default.jpg') }}" alt="Avatar"
                                class="profile-avatar rounded-circle" id="profileAvatar"
                                onerror="this.src='https://ui-avatars.com/api/?name=User&background=227447&color=fff&size=150'">
                            <label for="avatarInput" class="avatar-upload-btn position-absolute">
                                <img src="{{ asset('images/svg/profile/add-photo.svg') }}" alt="Camera"
                                    class="camera-icon">
                            </label>
                            <input type="file" id="avatarInput" class="d-none" accept="image/*">
                        </div>
                    </div>

                    <!-- Profile Card -->
                    <div class="card profile-card">
                        <div class="card-body px-5 py-4">
                            <form id="profileForm">
                                <!-- Full Name Field -->
                                <div class="profile-field mb-3 pb-3 border-bottom border-2">
                                    <label class="form-label color-primary-13 mb-2 text-lg">Họ và Tên</label>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <input type="text" class="form-control profile-input border-0 p-0 bg-transparent text-1lg"
                                            id="fullName" value="Thuy Duong"
                                            style="color: var(--primary-color); font-weight: 500;">
                                        <button type="button" class="btn btn-link p-0 profile-edit-btn"
                                            data-field="fullName">
                                            <img src="{{ asset('images/svg/profile/edit.svg') }}" alt="Edit"
                                                width="20" height="20">
                                        </button>
                                    </div>
                                </div>

                                <!-- Phone Field -->
                                <div class="profile-field mb-3 pb-3 border-bottom border-2">
                                    <label class="form-label color-primary-13 mb-2 text-lg">Phone</label>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <input type="text" class="form-control profile-input border-0 p-0 bg-transparent text-1lg"
                                            id="phone" value="0834 085 578"
                                            style="color: var(--primary-color); font-weight: 500;">
                                        <button type="button" class="btn btn-link p-0 profile-edit-btn" data-field="phone">
                                            <img src="{{ asset('images/svg/profile/edit.svg') }}" alt="Edit"
                                                width="20" height="20">
                                        </button>
                                    </div>
                                </div>

                                <!-- Email Field (Read-only) -->
                                <div class="profile-field mb-3 pb-3 border-bottom border-2">
                                    <label class="form-label color-primary-13 mb-2 text-lg">Email</label>
                                    <div class="d-flex align-items-center justify-content-between text-1lg">
                                        <span class="email-profile" style="font-weight: 500;">thuyduong123@gmail.com</span>
                                    </div>
                                </div>

                                <!-- Referral Code Field -->
                                <div class="profile-field mb-4 border-bottom">
                                    <label class="form-label color-primary-13 mb-2 text-lg">Mã giới thiệu</label>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="referral-code text-1lg"
                                            style="color: var(--primary-color); font-weight: 500;">AEJ1325</span>
                                        <button type="button" class="btn btn-link p-0 profile-copy-btn"
                                            data-code="AEJ1325">
                                            <img src="{{ asset('images/svg/profile/copy.svg') }}" alt="Copy"
                                                width="20" height="20">
                                        </button>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-save text-1lg"
                                        style="background-color: var(--primary-color); border-color: var(--primary-color);">
                                        Lưu
                                    </button>
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
    @vite('resources/assets/frontend/css/pages/profile.css')
@endpush

@push('scripts')
    <script>
        // Handle avatar upload
        document.getElementById('avatarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileAvatar').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle edit buttons
        document.querySelectorAll('.profile-edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const fieldId = this.getAttribute('data-field');
                const input = document.getElementById(fieldId);
                input.focus();
                input.select();
            });
        });

        // Handle copy referral code
        document.querySelector('.profile-copy-btn').addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            navigator.clipboard.writeText(code).then(function() {
                // Show toast or alert
                alert('Đã sao chép mã giới thiệu: ' + code);
            });
        });

        // Handle form submit
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Handle form submission
            alert('Đã lưu thông tin!');
        });
    </script>
@endpush
