@extends('client.pages.tools.go-soft.layout')

@section('go-soft-content')
    <div class="go-soft-overview">
        <!-- Top Icon -->
        <div class="overview-icon-header">
            <img src="{{ asset('images/svg/excel-icon.svg') }}" alt="Excel Icon" class="overview-header-icon">
        </div>
        
        <!-- Form Section -->
        <div class="overview-form-section">
            <!-- Date Range -->
            <div class="form-row">
                <label class="form-label">Ngày nộp từ ngày</label>
                <div class="date-range-group">
                    <select class="date-select">
                        <option>01</option>
                    </select>
                    <select class="date-select">
                        <option>01</option>
                    </select>
                    <select class="date-select">
                        <option>2025</option>
                    </select>
                    
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="date-arrow">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#227447" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
                    <select class="date-select">
                        <option>01</option>
                    </select>
                    <select class="date-select">
                        <option>06</option>
                    </select>
                    <select class="date-select">
                        <option>2025</option>
                    </select>
                </div>
            </div>
            
            <!-- Tờ khai Select -->
            <div class="form-row">
                <label class="form-label">Tờ khai</label>
                <select class="form-select" id="toKhaiSelect">
                    <option value="">--Tất cả--</option>
                    <optgroup label="THUẾ GIÁ TRỊ GIA TĂNG">
                        <option>01/GTGT - Tờ khai thuế giá trị gia tăng (GTGT)</option>
                        <option>02/GTGT - Tờ khai thuế GTGT dành cho dự án đầu tư</option>
                        <option>01/GTGT - Tờ khai thuế giá trị gia tăng (TT80/2021)</option>
                    </optgroup>
                    <optgroup label="THUẾ THU NHẬP DOANH NGHIỆP">
                        <option>03/TNDN - Tờ khai quyết toán thuế TNDN</option>
                        <option selected>03/TNDN - Tờ khai quyết toán thuế TNDN (TT80/2021)</option>
                    </optgroup>
                    <optgroup label="THUẾ THU NHẬP CÁ NHÂN">
                        <option>06/KK-TNCN - Tờ khai quyết toán thuế TNCN (TT156/2013)</option>
                    </optgroup>
                </select>
            </div>
            
            <!-- Loại -->
            <div class="form-row">
                <label class="form-label">Loại</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="loai" value="to-khai">
                        <span>Tờ khai</span>
                    </label>
                    <label class="radio-option active">
                        <input type="radio" name="loai" value="giay-nop-tien" checked>
                        <span>Giấy nộp tiền</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="loai" value="thong-bao">
                        <span>Thông báo</span>
                    </label>
                </div>
            </div>
            
            <!-- Action Button -->
            <div class="form-actions">
                <button class="btn-lookup" id="lookupBtn">
                    <span class="btn-text">Đăng nhập</span>
                </button>
            </div>
        </div>
        
        <!-- Back Button -->
        <div class="content-back">
            <a href="{{ route('tools.go-soft') }}" class="btn btn-back-content">
                <img src="{{ asset('images/svg/arrow-left.svg') }}" alt="">
                <span>Trở lại</span>
            </a>
        </div>
    </div>
    
    <!-- Login Modal -->
    <x-go-soft.login-modal />
    
    <!-- Success/Failed Modals -->
    <x-go-soft.result-modals />
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in (fake for now)
    let isLoggedIn = localStorage.getItem('goSoftLoggedIn') === 'true';
    
    const lookupBtn = document.getElementById('lookupBtn');
    const btnText = lookupBtn.querySelector('.btn-text');
    
    // Update button text based on login status
    if (isLoggedIn) {
        btnText.textContent = 'Tra cứu';
    } else {
        btnText.textContent = 'Đăng nhập';
    }
    
    // Button click handler
    lookupBtn.addEventListener('click', function() {
        if (!isLoggedIn) {
            // Show login method modal
            document.getElementById('loginMethodModal').classList.add('show');
        } else {
            // Perform lookup (fake)
            performLookup();
        }
    });
    
    // Login method selection
    document.querySelectorAll('.login-method-card').forEach(card => {
        card.addEventListener('click', function() {
            const method = this.dataset.method;
            document.getElementById('loginMethodModal').classList.remove('show');
            
            if (method === 'tax') {
                document.getElementById('taxLoginModal').classList.add('show');
            } else if (method === 'vneid') {
                document.getElementById('vneidLoginModal').classList.add('show');
            }
        });
    });
    
    // Close modals
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.dataset.close;
            document.getElementById(modalId).classList.remove('show');
        });
    });
    
    // Tax login form submit
    document.querySelector('.tax-login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Fake login
        isLoggedIn = true;
        localStorage.setItem('goSoftLoggedIn', 'true');
        btnText.textContent = 'Tra cứu';
        
        // Close modal
        document.getElementById('taxLoginModal').classList.remove('show');
    });
    
    // Perform lookup (fake)
    function performLookup() {
        // Simulate lookup (50% success rate)
        const isSuccess = Math.random() > 0.5;
        
        setTimeout(() => {
            if (isSuccess) {
                document.getElementById('goSoftSuccessModal').classList.add('show');
                setTimeout(() => {
                    document.getElementById('goSoftSuccessModal').classList.remove('show');
                    // Redirect to download page
                    window.location.href = '{{ route("tools.go-soft.download") }}';
                }, 2000);
            } else {
                document.getElementById('goSoftFailedModal').classList.add('show');
                setTimeout(() => {
                    document.getElementById('goSoftFailedModal').classList.remove('show');
                }, 2000);
            }
        }, 500);
    }
    
    // Sidebar menu items - require login
    document.querySelectorAll('.sidebar-menu-item').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!isLoggedIn && this.getAttribute('href') !== '{{ route("tools.go-soft") }}') {
                e.preventDefault();
                document.getElementById('loginMethodModal').classList.add('show');
            }
        });
    });
});
</script>
@endpush


