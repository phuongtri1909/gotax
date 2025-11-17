@extends('client.pages.tools.go-bot.layout')

@section('go-bot-content')
    <div class="go-bot-overview">
        <!-- Tab Navigation -->
        <div class="go-bot-tabs">
            <button class="go-bot-tab active" data-tab="business" id="businessTab">
                Doanh Nghiệp
            </button>
            <button class="go-bot-tab" data-tab="personal" id="personalTab">
                Cá Nhân
            </button>
        </div>
        
        <!-- Search Section -->
        <div class="go-bot-search-section">
            <div class="search-container">
                <label for="taxIdInput" class="search-label">Mã số thuế</label>
                <div class="search-input-group">
                    <input type="text" 
                           id="taxIdInput" 
                           class="search-input" 
                           placeholder="Nhập mã số thuế">
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
        
        <!-- Promotional Section -->
        <div class="go-bot-promo-section">
            <div class="promo-badge">Nổi bật</div>
            <div class="promo-content">
                <div class="promo-left">
                    <h2 class="promo-title">Đơn Giản Hoá Quy Trình Với Công Cụ Tải Tờ Khai Hàng Loạt</h2>
                    <p class="promo-description">
                        Tự động hoá toàn bộ quy trình tra cứu và tải tờ khai giúp bạn tăng tốc quá trình...
                    </p>
                    <button class="btn-promo-more">
                        Xem thêm →
                    </button>
                </div>
                <div class="promo-right">
                    <div class="promo-images">
                        <!-- Placeholder for screenshots -->
                        <div class="promo-image-placeholder"></div>
                        <div class="promo-image-placeholder"></div>
                        <div class="promo-image-placeholder"></div>
                    </div>
                    <button class="promo-close">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const businessTab = document.getElementById('businessTab');
    const personalTab = document.getElementById('personalTab');
    const searchBtn = document.getElementById('searchBtn');
    const taxIdInput = document.getElementById('taxIdInput');
    
    // Tab switching
    businessTab.addEventListener('click', function() {
        businessTab.classList.add('active');
        personalTab.classList.remove('active');
    });
    
    personalTab.addEventListener('click', function() {
        personalTab.classList.add('active');
        businessTab.classList.remove('active');
    });
    
    // Search button click
    searchBtn.addEventListener('click', function() {
        const taxId = taxIdInput.value.trim();
        if (taxId) {
            // Redirect to result page
            window.location.href = '{{ route("tools.go-bot.result") }}?taxId=' + encodeURIComponent(taxId);
        }
    });
    
    // Enter key press
    taxIdInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
});
</script>
@endpush

