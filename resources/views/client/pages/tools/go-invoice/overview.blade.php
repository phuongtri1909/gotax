@extends('client.pages.tools.go-invoice.layout')

@section('go-invoice-content')
    <div class="go-invoice-overview">
        <!-- Form Section -->
        <div class="overview-form-section">
            <!-- Icon at top left -->
            <div class="form-icon-top">
                <img src="{{ asset('images/d/go-invoice/invoice-icon.svg') }}" alt="Invoice" class="form-top-icon">
            </div>
            
            <!-- Date Range -->
            <div class="form-row-two-col">
                <label class="form-label-left">Ngày nộp từ ngày</label>
                <div class="form-input-right">
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
            </div>
            
            <!-- Tờ khai -->
            <div class="form-row-two-col">
                <label class="form-label-left">Tờ khai</label>
                <div class="form-input-right">
                    <select class="form-select-simple" id="toKhaiSelect">
                        <option value="">--Tất cả--</option>
                    </select>
                </div>
            </div>
            
            <!-- Loại -->
            <div class="form-row-two-col">
                <label class="form-label-left">Loại</label>
                <div class="form-input-right">
                    <div class="radio-group-simple">
                        <label class="radio-option-simple">
                            <input type="radio" name="loai-type" value="to-khai">
                            <span>Tờ khai</span>
                        </label>
                        <label class="radio-option-simple">
                            <input type="radio" name="loai-type" value="giay-nop-tien">
                            <span>Giấy nộp tiền</span>
                        </label>
                        <label class="radio-option-simple">
                            <input type="radio" name="loai-type" value="thong-bao">
                            <span>Thông báo</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Action Button -->
            <div class="form-actions">
                <button class="btn-sync" id="syncBtn">
                    <span class="btn-text">Đăng nhập</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Login Modal -->
    <x-go-invoice.login-modal />
    
    <!-- Company Name Setup Modal -->
    <x-go-invoice.company-setup-modal />
    
    <!-- Add Company Modal -->
    <x-go-invoice.add-company-modal />
    
    <!-- Success/Failed Modals -->
    <x-go-soft.result-modals />
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in (fake for now)
    let isLoggedIn = localStorage.getItem('goInvoiceLoggedIn') === 'true';
    let companyName = localStorage.getItem('goInvoiceCompanyName') || '';
    
    const syncBtn = document.getElementById('syncBtn');
    const btnText = syncBtn.querySelector('.btn-text');
    
    // Update button text based on login status
    if (isLoggedIn && companyName) {
        btnText.textContent = 'Đồng bộ hóa đơn';
    } else {
        btnText.textContent = 'Đăng nhập';
    }
    
    // Sync/Login button click
    syncBtn.addEventListener('click', function() {
        if (!isLoggedIn) {
            // Show login modal
            document.getElementById('invoiceLoginModal').classList.add('show');
        } else {
            // Perform sync
            performSync();
        }
    });
    
    // Login form submit
    document.querySelector('.invoice-login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Fake login
        isLoggedIn = true;
        localStorage.setItem('goInvoiceLoggedIn', 'true');
        
        // Close login modal
        document.getElementById('invoiceLoginModal').classList.remove('show');
        
        // Show company setup modal
        setTimeout(() => {
            document.getElementById('companySetupModal').classList.add('show');
        }, 300);
    });
    
    // Company setup form submit
    document.querySelector('.company-setup-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const companyNameInput = document.getElementById('companyNameInput');
        companyName = companyNameInput.value.trim();
        
        if (companyName) {
            localStorage.setItem('goInvoiceCompanyName', companyName);
            
            // Close modal
            document.getElementById('companySetupModal').classList.remove('show');
            
            // Update button
            btnText.textContent = 'Đồng bộ hóa đơn';
        }
    });
    
    // Add company button
    document.getElementById('addCompanyBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('addCompanyModal').classList.add('show');
    });
    
    // Add company form submit
    document.querySelector('.add-company-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const mst = document.getElementById('addCompanyMST').value;
        const password = document.getElementById('addCompanyPassword').value;
        const name = document.getElementById('addCompanyName').value;
        
        // TODO: Add company to backend
        // For now, just close modal
        alert('Công ty đã được thêm thành công! (Demo)');
        
        // Close modal and reset form
        document.getElementById('addCompanyModal').classList.remove('show');
        document.querySelector('.add-company-form').reset();
    });
    
    // Close modals
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.dataset.close;
            if (modalId) {
                document.getElementById(modalId).classList.remove('show');
            }
        });
    });
    
    // Close modal when clicking outside
    document.querySelectorAll('.go-invoice-modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    });
    
    // Perform sync (fake)
    function performSync() {
        // Simulate sync (50% success rate)
        const isSuccess = Math.random() > 0.5;
        
        setTimeout(() => {
            if (isSuccess) {
                document.getElementById('goSoftSuccessModal').classList.add('show');
                setTimeout(() => {
                    document.getElementById('goSoftSuccessModal').classList.remove('show');
                    // Redirect to download page
                    window.location.href = '{{ route("tools.go-invoice.download") }}';
                }, 2000);
            } else {
                document.getElementById('goSoftFailedModal').classList.add('show');
                setTimeout(() => {
                    document.getElementById('goSoftFailedModal').classList.remove('show');
                }, 2000);
            }
        }, 500);
    }
});
</script>
@endpush

