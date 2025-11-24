@extends('client.layouts.app')
@section('title', 'Dùng thử ' . $toolName . ' - ' . config('app.name'))
@section('description', 'Đăng ký dùng thử ' . $toolName . ' - ' . config('app.name'))
@section('keywords', 'Dùng thử ' . $toolName . ', ' . config('app.name'))

@section('content')
    <section class="home-page container-page py-5">
        <div class="container">
           <div class="p-5 pt-0">
            <h1 class="trial-title text-center mb-4 pt-5">ĐĂNG KÝ DÙNG THỬ {{ strtoupper($toolName) }}</h1>
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-5">
                    <div class="trial-illustration">
                        <img src="{{ asset('images/d/trials/register.png') }}" alt="{{ $toolName }} Illustration" class="img-fluid">
                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="trial-form-card">
                        @auth
                            @if($hasTrial)
                                <div class="alert alert-info">
                                    <p class="mb-0">Bạn đã đăng ký dùng thử {{ $toolName }} rồi.</p>
                                </div>
                            @else
                                <div class="text-center">
                                    <p class="mb-4">Bạn đang đăng nhập với tài khoản: <strong>{{ auth()->user()->email }}</strong></p>
                                    <button type="button" class="btn trial-submit w-100" id="btn-register-trial">Đăng ký dùng thử</button>
                                </div>
                            @endif
                        @else
                            <div class="text-center">
                                <p class="mb-4">Vui lòng đăng nhập để đăng ký dùng thử</p>
                                <a href="{{ route('login') }}" class="btn trial-submit w-100">Đăng nhập</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="trial-policy mt-5">
                <h2 class="trial-policy-title mb-0">Chính sách dùng thử:</h2>
                <p class="trial-policy-text">
                    @if($trial->description)
                        {!! nl2br(e($trial->description)) !!}
                    @else
                        GoTax dành tặng Quý Khách chương trình dùng thử miễn phí để trải nghiệm. Sau đó, Quý Khách có thể đánh giá và cân nhắc đăng ký chính thức.
                    @endif
                </p>
            </div>
           </div>
        </div>
    </section>

    <!-- Verification Modal -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered verification-modal-dialog">
            <div class="modal-content verification-modal-content">
                <div class="modal-body verification-modal-body">
                    <div class="verification-illustration">
                        <img src="{{ asset('images/d/trials/image-success.png') }}" alt="Verification Illustration" class="img-fluid">
                    </div>
                    <h2 class="verification-title">Xác thực đăng ký dùng thử</h2>
                    <div class="verification-message">
                        <p class="mb-0">Chúng tôi đã gửi email xác thực đến địa chỉ email của bạn.</p>
                        <p>Truy cập email <strong id="verification-email"></strong> để xác thực và hoàn tất đăng ký dùng thử.</p>
                    </div>
                    <a href="https://mail.google.com/" target="_blank" class="btn verification-gmail-btn" id="openGmailBtn">
                        <img src="{{ asset('images/d/trials/mail.png') }}" alt="Gmail Icon" class="gmail-icon">
                        <span>Mở Gmail</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-invoice-trial.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnRegister = document.getElementById('btn-register-trial');
            
            if (btnRegister) {
                btnRegister.addEventListener('click', async function() {
                    const btn = this;
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';

                    // Hiển thị modal xác nhận
                    Swal.fire({
                        title: 'Xác nhận đăng ký',
                        text: 'Bạn có chắc chắn muốn đăng ký dùng thử {{ $toolName }}?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const routeMap = {
                                    'go-invoice': '{{ route("tools.trial.register.go-invoice") }}',
                                    'go-bot': '{{ route("tools.trial.register.go-bot") }}',
                                    'go-soft': '{{ route("tools.trial.register.go-soft") }}',
                                    'go-quick': '{{ route("tools.trial.register.go-quick") }}',
                                };
                                
                                const response = await fetch(routeMap['{{ $toolType }}'], {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                        'Content-Type': 'application/json',
                                    },
                                });

                                const result = await response.json();

                                if (result.success) {
                                    // Hiển thị verification modal
                                    const verificationModal = new bootstrap.Modal(document.getElementById('verificationModal'));
                                    const verificationEmailEl = document.getElementById('verification-email');
                                    
                                    if (verificationEmailEl && result.email) {
                                        verificationEmailEl.textContent = result.email;
                                    }
                                    
                                    verificationModal.show();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: result.message || 'Có lỗi xảy ra khi đăng ký dùng thử.',
                                        confirmButtonColor: '#3085d6'
                                    });
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: 'Có lỗi xảy ra khi đăng ký dùng thử.',
                                    confirmButtonColor: '#3085d6'
                                });
                            } finally {
                                btn.disabled = false;
                                btn.innerHTML = originalText;
                            }
                        } else {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    });
                });
            }
        });
    </script>
@endpush

