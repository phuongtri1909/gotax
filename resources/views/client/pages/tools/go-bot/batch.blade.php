@extends('client.pages.tools.go-bot.layout')

@section('go-bot-content')
    <div class="go-bot-batch">
        <!-- Tab Navigation -->
        <div class="go-bot-tabs">
            <button class="go-bot-tab active" data-tab="business" id="businessTab">
                Doanh Nghiệp
            </button>
            <button class="go-bot-tab" data-tab="personal" id="personalTab">
                Cá Nhân
            </button>
        </div>
        
        <!-- Batch Lookup Section -->
        <div class="go-bot-batch-section">
            <h2 class="batch-title">Nhập MST Doanh Nghiệp Bạn Muốn Tra Cứu</h2>
            
            <div class="batch-input-area">
                <textarea 
                    id="batchTaxIds" 
                    class="batch-textarea" 
                    placeholder="Danh sách theo thứ tự:&#10;1&#10;2&#10;3&#10;...&#10;10"></textarea>
            </div>
            
            <div class="batch-actions">
                <button class="btn-batch-search" id="batchSearchBtn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="2"/>
                        <path d="M15 15L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>Tra cứu rủi ro</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Success/Failed Modals -->
    <x-go-soft.result-modals />
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const businessTab = document.getElementById('businessTab');
    const personalTab = document.getElementById('personalTab');
    const batchSearchBtn = document.getElementById('batchSearchBtn');
    const batchTaxIds = document.getElementById('batchTaxIds');
    const successModal = document.getElementById('goSoftSuccessModal');
    const failedModal = document.getElementById('goSoftFailedModal');
    
    // Tab switching
    businessTab.addEventListener('click', function() {
        businessTab.classList.add('active');
        personalTab.classList.remove('active');
    });
    
    personalTab.addEventListener('click', function() {
        personalTab.classList.add('active');
        businessTab.classList.remove('active');
    });
    
    // Batch search
    batchSearchBtn.addEventListener('click', function() {
        const taxIds = batchTaxIds.value.trim();
        if (!taxIds) {
            alert('Vui lòng nhập mã số thuế');
            return;
        }
        
        // Simulate API call (50% success rate)
        const isSuccess = Math.random() > 0.5;
        
        setTimeout(() => {
            if (isSuccess) {
                if (successModal) {
                    successModal.style.display = 'flex';
                    successModal.classList.add('show');
                }
                setTimeout(() => {
                    if (successModal) {
                        successModal.style.display = 'none';
                        successModal.classList.remove('show');
                    }
                    // Redirect to download page
                    window.location.href = '{{ route("tools.go-bot.download") }}';
                }, 2000);
            } else {
                if (failedModal) {
                    failedModal.style.display = 'flex';
                    failedModal.classList.add('show');
                }
                setTimeout(() => {
                    if (failedModal) {
                        failedModal.style.display = 'none';
                        failedModal.classList.remove('show');
                    }
                }, 2000);
            }
        }, 500);
    });
});
</script>
@endpush

