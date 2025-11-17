@extends('client.pages.tools.go-soft.layout')

@section('go-soft-content')
    <div class="go-soft-download">
        <h2 class="download-success-title text-center">
            Tra cứu tờ khai Thành Công 
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" style="vertical-align: middle;">
                <circle cx="16" cy="16" r="15" fill="#227447"/>
                <path d="M10 16L14 20L22 12" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </h2>
        
        <div class="download-info-box">
            <p class="download-info-text">Hệ thống đichvucong đang trong quá trình cập nhật dữ liệu, kết quả tra cứu chậm hơn bình thường. Vui lòng chờ sau ít phút!</p>
        </div>
        
        <div class="download-files-section">
            <h3 class="download-section-title">Tải Xuống File</h3>
            
            <div class="download-files-grid">
                <div class="download-file-item">
                    <div class="download-file-icon">
                        <img src="{{ asset('images/svg/xml-icon.svg') }}" alt="XML" class="file-type-icon">
                        <p class="file-type-label">XML</p>
                    </div>
                    <button class="btn-download-item">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.83334 8.33333L10 12.5L14.1667 8.33333" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 12.5V2.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Tờ khai</span>
                    </button>
                </div>
                
                <div class="download-file-item">
                    <div class="download-file-icon">
                        <img src="{{ asset('images/svg/xml-icon.svg') }}" alt="XML" class="file-type-icon">
                        <p class="file-type-label">XML</p>
                    </div>
                    <button class="btn-download-item">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.83334 8.33333L10 12.5L14.1667 8.33333" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 12.5V2.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Giấy nộp tiền</span>
                    </button>
                </div>
                
                <div class="download-file-item">
                    <div class="download-file-icon">
                        <img src="{{ asset('images/svg/xml-icon.svg') }}" alt="XML" class="file-type-icon">
                        <p class="file-type-label">XML</p>
                    </div>
                    <button class="btn-download-item">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.83334 8.33333L10 12.5L14.1667 8.33333" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 12.5V2.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Thông báo</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="download-actions">
            <button class="btn-reload-lookup" id="reloadLookupBtn">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M1.66666 3.33333V8.33333H6.66666" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3.14166 12.5C3.63466 13.9464 4.57296 15.2006 5.83193 16.0831C7.0909 16.9656 8.60559 17.4312 10.1563 17.4162C11.707 17.4013 13.2119 16.9067 14.4547 16.0008C15.6976 15.095 16.6144 13.8234 17.0808 12.3667C17.5472 10.91 17.5409 9.34463 17.0629 7.89173C16.5849 6.43884 15.6581 5.17445 14.4077 4.27789C13.1573 3.38133 11.6483 2.89847 10.0979 2.89641C8.54752 2.89435 7.03721 3.37318 5.78416 4.26666L1.66666 8.33333" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Lấy lại dữ liệu</span>
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Download buttons
    document.querySelectorAll('.btn-download-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.querySelector('span').textContent;
            alert(`Đang tải ${type}... (Demo)`);
        });
    });
    
    // Reload button - go back to overview
    const reloadBtn = document.getElementById('reloadLookupBtn');
    if (reloadBtn) {
        reloadBtn.addEventListener('click', function() {
            window.location.href = '{{ route("tools.go-soft.overview") }}';
        });
    }
});
</script>
@endpush



