@extends('client.pages.tools.go-bot.layout')

@section('go-bot-content')
    <div class="go-bot-result">
        <!-- Search Section -->
        <div class="go-bot-search-section">
            <div class="search-container">
                <label for="taxIdInput" class="search-label">Mã số thuế</label>
                <div class="search-input-group">
                    <input type="text" 
                           id="taxIdInput" 
                           class="search-input" 
                           placeholder="Nhập mã số thuế"
                           value="{{ request('taxId') ?? '5100449118' }}">
                    <button class="btn-search" id="searchBtn">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="2"/>
                            <path d="M15 15L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>Lấy thông tin</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Warning Banner -->
        <div class="go-bot-warning-banner">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="warning-icon">
                <path d="M12 9V13M12 17H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="warning-text">
                Cơ quan thuế đang cập nhật thêm dữ liệu nên kết quả tra cứu có thể chưa chính xác. Người dùng có thể thử lại vào lúc khác.
            </p>
        </div>
        
        <!-- Result Card -->
        <div class="go-bot-result-card">
            <h2 class="result-card-title">Kết Quả Tra Cứu</h2>
            
            <div class="result-details">
                <div class="result-row">
                    <div class="result-label">Mã số Thuế</div>
                    <div class="result-value">5100449118</div>
                </div>
                
                <div class="result-row">
                    <div class="result-label">Ngày cấp</div>
                    <div class="result-value">29/05/2018</div>
                </div>
                
                <div class="result-row">
                    <div class="result-label">Tên đơn vị</div>
                    <div class="result-value-with-copy">
                        <span>CÔNG TY GIẢI PHÁP VÀ CÔNG NGHỆ (SOLUTION AND TECHNOLOGY COMPANY)</span>
                        <button class="btn-copy" data-copy="CÔNG TY GIẢI PHÁP VÀ CÔNG NGHỆ (SOLUTION AND TECHNOLOGY COMPANY)">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M6 2H12C12.5304 2 13.0391 2.21071 13.4142 2.58579C13.7893 2.96086 14 3.46957 14 4V10M4 6H10C10.5304 6 11.0391 6.21071 11.4142 6.58579C11.7893 6.96086 12 7.46957 12 8V14C12 14.5304 11.7893 15.0391 11.4142 15.4142C11.0391 15.7893 10.5304 16 10 16H4C3.46957 16 2.96086 15.7893 2.58579 15.4142C2.21071 15.0391 2 14.5304 2 14V8C2 7.46957 2.21071 6.96086 2.58579 6.58579C2.96086 6.21071 3.46957 6 4 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="result-row">
                    <div class="result-label">Địa chỉ theo CQT</div>
                    <div class="result-value-with-copy">
                        <span>Tổ 1, Xã Pà Vầy Sủ, Tỉnh Tuyên Quang, Việt Nam</span>
                        <button class="btn-copy" data-copy="Tổ 1, Xã Pà Vầy Sủ, Tỉnh Tuyên Quang, Việt Nam">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M6 2H12C12.5304 2 13.0391 2.21071 13.4142 2.58579C13.7893 2.96086 14 3.46957 14 4V10M4 6H10C10.5304 6 11.0391 6.21071 11.4142 6.58579C11.7893 6.96086 12 7.46957 12 8V14C12 14.5304 11.7893 15.0391 11.4142 15.4142C11.0391 15.7893 10.5304 16 10 16H4C3.46957 16 2.96086 15.7893 2.58579 15.4142C2.21071 15.0391 2 14.5304 2 14V8C2 7.46957 2.21071 6.96086 2.58579 6.58579C2.96086 6.21071 3.46957 6 4 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="result-row">
                    <div class="result-label">Trạng thái</div>
                    <div class="result-value-status">
                        <div class="status-active">NNT đang hoạt động</div>
                        <div class="status-warning">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8 6V10M8 14H8.01M15 8C15 12.4183 11.4183 16 7 16C2.58172 16 -1 12.4183 -1 8C-1 3.58172 2.58172 0 7 0C11.4183 0 15 3.58172 15 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>NNT nằm trong danh sách DN rủi ro cao về thuế theo CV 426/CT-TTRT ngày 26/05/2014 của TCT Tiên Giang</span>
                        </div>
                    </div>
                </div>
                
                <div class="result-row">
                    <div class="result-label">Cơ quan Thuế quản lý</div>
                    <div class="result-value">Thuế cơ sở 7 tỉnh Tuyên Quang - Mã CQT: 20117</div>
                </div>
                
                <div class="result-row">
                    <div class="result-label">Loại hình doanh nghiệp</div>
                    <div class="result-value">Công ty trách nhiệm hữu hạn một thành viên</div>
                </div>
                
                <div class="result-row">
                    <div class="result-label">Người đại diện theo Pháp luật</div>
                    <div class="result-value-with-link">
                        <span>Nguyễn Hồng Tươi</span>
                        <button class="btn-view-detail" id="viewLegalRepBtn">
                            <span>(Xem chi tiết)</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="result-row">
                    <div class="result-label">Ngành nghề chính</div>
                    <div class="result-value-with-link">
                        <span>Bán buôn nhiên liệu rắn và các sản phẩm liên quan</span>
                        <button class="btn-view-detail" id="viewBusinessSectorBtn">
                            <span>(Xem danh sách)</span>
                        </button>
                    </div>
                </div>
                
                <div class="result-row">
                    <div class="result-label">Cán bộ quản lý Thuế</div>
                    <div class="result-value">Phạm Thanh Hà - SĐT: 0834085578 - Email: Thanhpham@gmail.com</div>
                </div>
            </div>
            
            <div class="result-updated">
                Dữ liệu được cập nhật lúc 17/08/2025 - 20:28:44
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="go-bot-result-actions">
            <a href="{{ route('tools.go-bot.overview') }}" class="btn btn-back-result">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Trở lại</span>
            </a>
            <a href="{{ route('tools.go-bot.batch') }}" class="btn btn-batch-lookup">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M3 4H17M3 8H17M3 12H17M3 16H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Tra hàng loạt</span>
            </a>
        </div>
        
        <!-- Search History -->
        <div class="go-bot-history">
            <h3 class="history-title">Lịch Sử Tra Cứu</h3>
            <div class="history-list">
                <div class="history-item">
                    <div class="history-company-name">CÔNG TY TNHH GIẢI PHÁP VÀ CÔNG NGHỆ</div>
                    <div class="history-tax-code">Mã số thuế: <span>02608005588</span></div>
                    <div class="history-address">Địa chỉ: Tổ 1, Xã Thanh Hà, Tỉnh Tuyên Quang, Việt Nam</div>
                </div>
                <div class="history-item">
                    <div class="history-company-name">CÔNG TY TNHH GIẢI PHÁP VÀ CÔNG NGHỆ</div>
                    <div class="history-tax-code">Mã số thuế: <span>02608005588</span></div>
                    <div class="history-address">Địa chỉ: Tổ 1, Xã Thanh Hà, Tỉnh Tuyên Quang, Việt Nam</div>
                </div>
                <div class="history-item">
                    <div class="history-company-name">CÔNG TY TNHH GIẢI PHÁP VÀ CÔNG NGHỆ</div>
                    <div class="history-tax-code">Mã số thuế: <span>02608005588</span></div>
                    <div class="history-address">Địa chỉ: Tổ 1, Xã Thanh Hà, Tỉnh Tuyên Quang, Việt Nam</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Legal Representative Modal -->
    <x-go-bot.legal-representative-modal />
    
    <!-- Business Sector Modal -->
    <x-go-bot.business-sector-modal />
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewLegalRepBtn = document.getElementById('viewLegalRepBtn');
    const viewBusinessSectorBtn = document.getElementById('viewBusinessSectorBtn');
    const legalRepModal = document.getElementById('legalRepModal');
    const businessSectorModal = document.getElementById('businessSectorModal');
    const searchBtn = document.getElementById('searchBtn');
    const taxIdInput = document.getElementById('taxIdInput');
    
    // View legal representative
    if (viewLegalRepBtn) {
        viewLegalRepBtn.addEventListener('click', function() {
            if (legalRepModal) {
                legalRepModal.classList.add('show');
            }
        });
    }
    
    // View business sector
    if (viewBusinessSectorBtn) {
        viewBusinessSectorBtn.addEventListener('click', function() {
            if (businessSectorModal) {
                businessSectorModal.classList.add('show');
            }
        });
    }
    
    // Copy buttons
    document.querySelectorAll('.btn-copy').forEach(btn => {
        btn.addEventListener('click', function() {
            const text = this.dataset.copy;
            navigator.clipboard.writeText(text).then(() => {
                // Show feedback
                const originalHTML = this.innerHTML;
                this.innerHTML = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8L6 11L13 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                }, 2000);
            });
        });
    });
    
    // Search button
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const taxId = taxIdInput.value.trim();
            if (taxId) {
                window.location.href = '{{ route("tools.go-bot.result") }}?taxId=' + encodeURIComponent(taxId);
            }
        });
    }
    
    // Close modals
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.go-bot-modal');
            if (modal) {
                modal.classList.remove('show');
            }
        });
    });
    
    // Close modal when clicking outside
    document.querySelectorAll('.go-bot-modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    });
});
</script>
@endpush

