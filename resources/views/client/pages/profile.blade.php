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
                            <img src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : asset('images/default/avatar_default.jpg') }}" alt="Avatar"
                                class="profile-avatar rounded-circle" id="profileAvatar"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name) }}&background=227447&color=fff&size=150'">
                            <label for="avatarInput" class="avatar-upload-btn position-absolute">
                                <img src="{{ asset('images/svg/profile/add-photo.svg') }}" alt="Camera"
                                    class="camera-icon">
                            </label>
                            <input type="file" id="avatarInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div id="avatarUploadProgress" class="d-none" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Card -->
                    <div class="card profile-card">
                        <div class="card-body px-5 py-4">
                            @include('components.toast-main')
                            @include('components.toast')

                            <form id="profileForm" method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                <div class="profile-field mb-3 pb-3 border-bottom border-2">
                                    <label class="form-label color-primary-13 mb-2 text-lg">Họ và Tên</label>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <input type="text" class="form-control profile-input border-0 p-0 bg-transparent text-1lg"
                                            id="fullName" name="full_name" value="{{ auth()->user()->full_name }}"
                                            style="color: var(--primary-color); font-weight: 500;" disabled>
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
                                            id="phone" name="phone" value="{{ auth()->user()->phone }}"
                                            style="color: var(--primary-color); font-weight: 500;" disabled>
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
                                        <span class="email-profile" style="font-weight: 500;">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>

                                <!-- Password Field (Read-only) -->
                                <div class="profile-field mb-3 pb-3 border-bottom border-2">
                                    <label class="form-label color-primary-13 mb-2 text-lg">Password</label>
                                    <div class="d-flex align-items-center justify-content-between text-1lg">
                                        <span class="password-profile" style="font-weight: 500; color: var(--primary-color);">••••••••••</span>
                                        <a href="{{ route('account-settings') }}" class="btn btn-link p-0" title="Thiết lập tài khoản">
                                            <img src="{{ asset('images/svg/profile/edit.svg') }}" alt="Edit"
                                                width="20" height="20">
                                        </a>
                                    </div>
                                </div>

                                <!-- Referral Code Field -->
                                <div class="profile-field mb-4 border-bottom">
                                    <label class="form-label color-primary-13 mb-2 text-lg">Mã giới thiệu</label>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="referral-code text-1lg"
                                            style="color: var(--primary-color); font-weight: 500;">{{ auth()->user()->referral_code }}</span>
                                        <button type="button" class="btn btn-link p-0 profile-copy-btn"
                                            data-code="{{ auth()->user()->referral_code }}">
                                            <img src="{{ asset('images/svg/profile/copy.svg') }}" alt="Copy"
                                                width="20" height="20">
                                        </button>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-save text-1lg" id="saveBtn"
                                        style="background-color: var(--primary-color); border-color: var(--primary-color); display: none;">
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
            if (!file) {
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                showToast('Vui lòng chọn file ảnh có định dạng: JPEG, PNG, JPG, GIF.', 'error');
                this.value = '';
                return;
            }

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showToast('Kích thước ảnh không được vượt quá 5MB.', 'error');
                this.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileAvatar').src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Upload avatar
            const formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', '{{ csrf_token() }}');

            // Show progress
            document.getElementById('avatarUploadProgress').classList.remove('d-none');
            document.getElementById('avatarInput').disabled = true;

            fetch('{{ route("profile.avatar.upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                return response.json().then(data => {
                    return { status: response.status, data: data };
                });
            })
            .then(result => {
                document.getElementById('avatarUploadProgress').classList.add('d-none');
                document.getElementById('avatarInput').disabled = false;

                if (result.status === 200 && result.data.success) {
                    showToast(result.data.message || 'Đã cập nhật ảnh đại diện thành công!', 'success');
                    if (result.data.avatar_url) {
                        document.getElementById('profileAvatar').src = result.data.avatar_url;
                    }
                } else if (result.status === 422 && result.data.errors) {
                    // Validation errors
                    const firstError = Object.values(result.data.errors)[0];
                    showToast(Array.isArray(firstError) ? firstError[0] : firstError, 'error');
                    // Reset to original avatar
                    document.getElementById('profileAvatar').src = '{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : asset("images/default/avatar_default.jpg") }}';
                } else {
                    showToast(result.data.message || 'Không thể upload ảnh đại diện!', 'error');
                    // Reset to original avatar
                    document.getElementById('profileAvatar').src = '{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : asset("images/default/avatar_default.jpg") }}';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('avatarUploadProgress').classList.add('d-none');
                document.getElementById('avatarInput').disabled = false;
                showToast('Đã xảy ra lỗi khi upload ảnh đại diện!', 'error');
                // Reset to original avatar
                document.getElementById('profileAvatar').src = '{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : asset("images/default/avatar_default.jpg") }}';
            });

            // Reset input
            this.value = '';
        });

        // Handle edit buttons
        let isEditing = false;
        document.querySelectorAll('.profile-edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const fieldId = this.getAttribute('data-field');
                const input = document.getElementById(fieldId);
                
                if (input.disabled) {
                    // Enable editing
                    input.disabled = false;
                    input.style.border = '1px solid var(--primary-color)';
                    input.style.borderRadius = '4px';
                    input.style.padding = '8px';
                    input.style.backgroundColor = '#fff';
                    input.focus();
                    input.select();
                    
                    // Show save button
                    document.getElementById('saveBtn').style.display = 'block';
                    isEditing = true;
                } else {
                    // Focus and select
                    input.focus();
                    input.select();
                }
            });
        });

        // Handle copy referral code
        const copyBtn = document.querySelector('.profile-copy-btn');
        if (copyBtn) {
            copyBtn.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                if (!code) {
                    showToast('Không tìm thấy mã giới thiệu!', 'error');
                    return;
                }

                // Try using Clipboard API first
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(code).then(function() {
                        showToast('Đã sao chép mã giới thiệu: ' + code, 'success');
                    }).catch(function(err) {
                        console.error('Clipboard API error:', err);
                        // Fallback method
                        fallbackCopyTextToClipboard(code);
                    });
                } else {
                    // Fallback for browsers that don't support Clipboard API
                    fallbackCopyTextToClipboard(code);
                }
            });
        }

        // Fallback copy function
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showToast('Đã sao chép mã giới thiệu: ' + text, 'success');
                } else {
                    showToast('Không thể sao chép mã giới thiệu!', 'error');
                }
            } catch (err) {
                console.error('Fallback copy error:', err);
                showToast('Không thể sao chép mã giới thiệu!', 'error');
            } finally {
                document.body.removeChild(textArea);
            }
        }

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Lấy giá trị trực tiếp từ input để đảm bảo lấy được cả khi disabled
            const fullNameInput = document.getElementById('fullName');
            const phoneInput = document.getElementById('phone');
            
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('full_name', fullNameInput.value);
            formData.append('phone', phoneInput.value);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                return response.json().then(data => {
                    return { status: response.status, data: data };
                });
            })
            .then(result => {
                if (result.status === 200 && result.data.success) {
                    showToast(result.data.message || 'Đã lưu thông tin!', 'success');
                    // Disable inputs after save
                    fullNameInput.disabled = true;
                    phoneInput.disabled = true;
                    fullNameInput.style.border = 'none';
                    phoneInput.style.border = 'none';
                    fullNameInput.style.padding = '0';
                    phoneInput.style.padding = '0';
                    fullNameInput.style.backgroundColor = 'transparent';
                    phoneInput.style.backgroundColor = 'transparent';
                    document.getElementById('saveBtn').style.display = 'none';
                    isEditing = false;
                } else if (result.status === 422 && result.data.errors) {
                    // Validation errors
                    const firstError = Object.values(result.data.errors)[0];
                    showToast(Array.isArray(firstError) ? firstError[0] : firstError, 'error');
                } else {
                    showToast(result.data.message || 'Không thể lưu thông tin!', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Đã xảy ra lỗi khi lưu thông tin!', 'error');
            });
        });
    </script>
@endpush
