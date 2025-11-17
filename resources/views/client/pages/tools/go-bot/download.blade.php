@extends('client.pages.tools.go-bot.layout')

@section('go-bot-content')
    <div class="go-bot-download">
        <!-- Success Message -->
        <div class="download-success-message">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" fill="#227447"/>
                <path d="M8 12L11 15L16 9" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>File Của Bạn Đã Tra Cứu Thành Công</span>
        </div>
        
        <!-- Tab Navigation -->
        <div class="go-bot-tabs">
            <button class="go-bot-tab active" data-tab="business" id="businessTab">
                Doanh Nghiệp
            </button>
            <button class="go-bot-tab" data-tab="personal" id="personalTab">
                Cá Nhân
            </button>
        </div>
        
        <!-- Download Section -->
        <div class="go-bot-download-section">
            <h2 class="download-title">Tải Xuống File</h2>
            
            <div class="download-file-card">
                <div class="download-file-icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <rect x="10" y="10" width="40" height="40" rx="4" fill="#227447"/>
                        <path d="M20 25L30 35L40 25" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M30 35V15" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    <span class="download-file-label">Excel</span>
                </div>
                
                <button class="btn-download-file" id="downloadFileBtn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10 3V13M10 13L6 9M10 13L14 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 16V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19H15C15.5304 19 16.0391 18.7893 16.4142 18.4142C16.7893 18.0391 17 17.5304 17 17V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>File từ MST</span>
                </button>
            </div>
        </div>
        
        <!-- Upgrade Section -->
        <div class="go-bot-upgrade-section">
            <div class="upgrade-content">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="upgrade-icon">
                    <path d="M12 2L15 9L22 10L17 15L18 22L12 18L6 22L7 15L2 10L9 9L12 2Z" fill="#FFC107" stroke="#FFC107" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 class="upgrade-title">Nâng Cấp Để Tra Cứu MST Không Giới Hạn!</h3>
                <button class="btn-upgrade">
                    <span>Nâng Cấp Ngay</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M7 14L11 10L7 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const businessTab = document.getElementById('businessTab');
    const personalTab = document.getElementById('personalTab');
    const downloadFileBtn = document.getElementById('downloadFileBtn');
    
    // Tab switching
    businessTab.addEventListener('click', function() {
        businessTab.classList.add('active');
        personalTab.classList.remove('active');
    });
    
    personalTab.addEventListener('click', function() {
        personalTab.classList.add('active');
        businessTab.classList.remove('active');
    });
    
    // Download file
    if (downloadFileBtn) {
        downloadFileBtn.addEventListener('click', function() {
            // TODO: Implement download functionality
            alert('Download file (Demo)');
        });
    }
});
</script>
@endpush

