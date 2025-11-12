@extends('client.layouts.app')
@section('title', 'Dùng thử Go-Invoice - ' . config('app.name'))
@section('description', 'Đăng ký tài khoản dùng thử Go-Invoice - ' . config('app.name'))
@section('keywords', 'Dùng thử Go Invoice, ' . config('app.name'))

@section('content')
    <section class="home-page container-page py-5">
        <div class="container">
           <div class="p-5 pt-0">
            <h1 class="trial-title text-center mb-4 pt-5">ĐĂNG KÝ TÀI KHOẢN DÙNG THỬ GO-INVOICE</h1>
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-5">
                    <div class="trial-illustration">
                        <img src="{{ asset('images/d/trials/register.png') }}" alt="Go-Invoice Illustration" class="img-fluid">
                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="trial-form-card">
                        <form id="trialRegistrationForm" method="post" action="#" onsubmit="event.preventDefault();">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label color-primary">Họ và tên*</label>
                                <input type="text" name="name" id="trial-name" class="form-control trial-input" placeholder="Tên đăng nhập" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label color-primary">Email*</label>
                                <input type="email" name="email" id="trial-email" class="form-control trial-input" placeholder="Nhập email của bạn" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label color-primary">Số điện thoại*</label>
                                <input type="tel" name="phone" id="trial-phone" class="form-control trial-input" placeholder="Nhập số điện thoại của bạn" required>
                            </div>
                            <button type="submit" class="btn trial-submit w-100">Đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="trial-policy mt-5">
                <h2 class="trial-policy-title mb-0">Chính sách dùng thử:</h2>
                <p class="trial-policy-text">
                    GoTax dành tặng Quý Khách 2 tuần miễn phí để trải nghiệm. Sau đó, Quý Khách có thể đánh giá và cân nhắc đăng ký chính thức.
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
                    <h2 class="verification-title">Xác thực tài khoản</h2>
                    <div class="verification-message">
                        <p class="mb-0">Quý khách đã đăng ký dùng thử thành công.</p>
                        <p>Truy cập email <strong id="verification-email"></strong> để xác thực tài khoản và sử dụng ngay.</p>
                    </div>
                    <button type="button" class="btn verification-gmail-btn" id="openGmailBtn">
                        <img src="{{ asset('images/d/trials/mail.png') }}" alt="Gmail Icon" class="gmail-icon">
                        <span>Mở Gmail</span>
                    </button>
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
            const form = document.getElementById('trialRegistrationForm');
            const verificationModal = new bootstrap.Modal(document.getElementById('verificationModal'));
            const verificationEmailEl = document.getElementById('verification-email');
            const openGmailBtn = document.getElementById('openGmailBtn');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get email value
                const emailInput = document.getElementById('trial-email');
                const email = emailInput.value.trim();
                
                if (email) {
                    // Set email in modal
                    verificationEmailEl.textContent = email;
                    
                    // Show modal
                    verificationModal.show();
                }
            });

            // Open Gmail button
            openGmailBtn.addEventListener('click', function() {
                const emailInput = document.getElementById('trial-email');
                const email = emailInput.value.trim();
                if (email) {
                    window.open('https://mail.google.com/mail/?view=cm&fs=1&to=' + encodeURIComponent(email), '_blank');
                } else {
                    window.open('https://mail.google.com', '_blank');
                }
            });
        });
    </script>
@endpush


