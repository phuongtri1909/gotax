@extends('client.pages.tools.go-soft.layout')

@section('go-soft-content')
    <div class="go-soft-overview">
        <!-- Form Section -->
        <div class="overview-form-section">
            <!-- Company Selector (chỉ hiện khi đã login) -->
            <div class="form-row" id="companySelectorRow" style="display: none;">
                <div class="company-selector-wrapper">
                    <div class="company-selector-header" id="companySelectorHeader">
                        <div class="company-selector-left">
                            <div class="company-icon">
                                <img width="40" height="40" src="{{ asset('images/d/go-soft/building.png') }}" alt="">
                            </div>
                            <span class="company-name" id="selectedCompanyName">Chọn công ty</span>
                            <img src="{{ asset('images/d/go-soft/arrowdrop.svg') }}" alt="" class="company-dropdown-arrow" width="8" height="5">
                        </div>
                        <div class="company-mst-label" id="selectedCompanyMST">
                            <span>MST: </span><span id="companyMSTValue">-</span>
                        </div>
                    </div>
                    <div class="company-dropdown" id="companyDropdown">
                        <div class="company-dropdown-title">Danh sách công ty đã lưu</div>
                        <div class="company-list" id="companyList">
                            <!-- Companies will be loaded here -->
                            <div class="company-item" data-company-id="1" data-company-name="CÔNG TY GIẢI PHÁP VÀ CÔNG NGHỆ SỐ WETECH" data-company-mst="0109837318">
                                <div class="company-item-name">CÔNG TY GIẢI PHÁP VÀ CÔNG NGHỆ SỐ WETECH</div>
                                <div class="company-item-mst">MST: 0109837318</div>
                            </div>
                            <div class="company-item" data-company-id="2" data-company-name="CÔNG TY GIẢI PHÁP VÀ CÔNG NGHỆ" data-company-mst="01102352">
                                <div class="company-item-name">CÔNG TY GIẢI PHÁP VÀ CÔNG NGHỆ</div>
                                <div class="company-item-mst">MST: 01102352</div>
                            </div>
                            <div class="company-item" data-company-id="3" data-company-name="CÔNG TY GIÁO DỤC AN HOÀ" data-company-mst="01102353">
                                <div class="company-item-name">CÔNG TY GIÁO DỤC AN HOÀ</div>
                                <div class="company-item-mst">MST: 01102353</div>
                            </div>
                            <div class="company-item" data-company-id="4" data-company-name="CÔNG TY TNHH KANSAI" data-company-mst="01102354">
                                <div class="company-item-name">CÔNG TY TNHH KANSAI</div>
                                <div class="company-item-mst">MST: 01102354</div>
                            </div>
                            <div class="company-item" data-company-id="5" data-company-name="CÔNG TY THIẾT KẾ NỘI THẤT KIM NGỌC" data-company-mst="01102355">
                                <div class="company-item-name">CÔNG TY THIẾT KẾ NỘI THẤT KIM NGỌC</div>
                                <div class="company-item-mst">MST: 01102355</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
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
                    
                    <img src="{{ asset('images/d/go-soft/next.png') }}" alt="" class="date-arrow" width="30" height="30">
                    
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
                    <option value="00">--Tất cả--</option>
                    <optgroup label="THUẾ GIÁ TRỊ GIA TĂNG">
                        <option value="02">02/GTGT - Tờ khai thuế GTGT dành cho dự án đầu tư</option>
                        <option value="842">01/GTGT - TỜ KHAI THUẾ GIÁ TRỊ GIA TĂNG (TT80/2021)</option>
                        <option value="844">02/GTGT - TỜ KHAI THUẾ GIÁ TRỊ GIA TĂNG (TT80/2021)</option>
                    </optgroup>
                    <optgroup label="THUẾ THU NHẬP DOANH NGHIỆP">
                        <option value="892">03/TNDN - Tờ khai quyết toán thuế TNDN (TT80/2021)</option>
                    </optgroup>
                    <optgroup label="THUẾ THU NHẬP CÁ NHÂN">
                        <option value="864">05/KK-TNCN - Tờ khai khấu trừ thuế thu nhập cá nhân (TT80)</option>
                        <option value="953">05/QTT-TNCN - TỜ KHAI QUYẾT TOÁN THUẾ THU NHẬP CÁ NHÂN (TT80/2021)</option>
                    </optgroup>
                    <optgroup label="THUẾ NHÀ THẦU">
                        <option value="41">01/NTNN - Tờ khai thuế nhà thầu nước ngoài</option>
                        <option value="72">04/NTNN - Tờ khai quyết toán thuế nhà thầu nước ngoài</option>
                        <option value="838">01/NTNN -  Tờ khai thuế nhà thầu nước ngoài(TT80/2021)</option>
                        <option value="840">03/NTNN - Tờ khai thuế nhà thầu nước ngoài(TT80/2021)</option>
                    </optgroup>
                    <optgroup label="BÁO CÁO TÀI CHÍNH">
                        <option value="402">TT200 - Bộ báo cáo tài chính</option>
                    </optgroup>
                    <optgroup label="THUẾ MÔN BÀI">
                        <option value="824">01/LPMB - Tờ khai lệ phí môn bài (TT80/2021)</option>
                    </optgroup>
                </select>
            </div>
            
            <!-- Loại (checkbox để chọn nhiều) -->
            <div class="form-row">
                <label class="form-label">Loại</label>
                <div class="checkbox-group" id="loaiCheckboxGroup">
                    <label class="loai-checkbox-option selected">
                        <input type="checkbox" name="loai[]" value="to-khai" checked>
                        <span class="loai-checkbox-icon"></span>
                        <span class="loai-checkbox-text">Tờ khai</span>
                    </label>
                    <label class="loai-checkbox-option">
                        <input type="checkbox" name="loai[]" value="giay-nop-tien">
                        <span class="loai-checkbox-icon"></span>
                        <span class="loai-checkbox-text">Giấy nộp tiền</span>
                    </label>
                    <label class="loai-checkbox-option">
                        <input type="checkbox" name="loai[]" value="thong-bao">
                        <span class="loai-checkbox-icon"></span>
                        <span class="loai-checkbox-text">Thông báo</span>
                    </label>
                </div>
            </div>
            
            <style>
                .checkbox-group {
                    display: flex;
                    gap: 15px;
                    flex-wrap: wrap;
                }
                
                .loai-checkbox-option {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 12px 24px;
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    background: #fff;
                    min-width: 140px;
                }
                
                .loai-checkbox-option:hover {
                    border-color: #4CAF50;
                }
                
                .loai-checkbox-option.selected {
                    border-color: #4CAF50;
                    background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
                }
                
                .loai-checkbox-option input[type="checkbox"] {
                    display: none;
                }
                
                .loai-checkbox-icon {
                    width: 20px;
                    height: 20px;
                    border: 2px solid #ccc;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.2s ease;
                    flex-shrink: 0;
                }
                
                .loai-checkbox-option.selected .loai-checkbox-icon {
                    border-color: #4CAF50;
                    background: #4CAF50;
                }
                
                .loai-checkbox-option.selected .loai-checkbox-icon::after {
                    content: '';
                    width: 6px;
                    height: 6px;
                    background: #fff;
                    border-radius: 50%;
                }
                
                .loai-checkbox-text {
                    font-size: 14px;
                    color: #333;
                    font-weight: 500;
                }
            </style>
            
            <!-- Action Button -->
            <div class="form-actions">
                <button class="btn-lookup" id="lookupBtn">
                    <span class="btn-text">Đăng nhập</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Login Modal -->
    <x-go-soft.login-modal />
    
    <!-- Success/Failed Modals -->
    <x-go-soft.result-modals />
@endsection

@section('go-soft-download')
    <!-- Download Section (ẩn ban đầu, sẽ thay thế row khi có kết quả) -->
    <div id="downloadSection" class="d-none">
        <h2 class="download-title text-center">
            Tra cứu tờ khai Thành Công 
            <img src="{{ asset('images/d/go-quick/check-success.png') }}" alt="Success">
        </h2>

        <div class="download-content">
            <div class="download-info-box">
                <p class="download-info-text">Hệ thống đichvucong đang trong quá trình cập nhật dữ liệu, kết quả tra cứu chậm hơn bình thường. Vui lòng chờ sau ít phút!</p>
            </div>
            
            <div class="download-files-section">
                <h3 class="download-section-title">Tải Xuống File</h3>
                
                <div class="download-files-grid">
                    <div class="download-file-item" data-type="to-khai">
                        <div class="download-file-icon">
                            <img src="{{ asset('images/d/go-soft/xml.png') }}" alt="XML" class="file-type-icon">
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
                    
                    <div class="download-file-item" data-type="giay-nop-tien">
                        <div class="download-file-icon">
                            <img src="{{ asset('images/d/go-soft/xml.png') }}" alt="XML" class="file-type-icon">
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
                    
                    <div class="download-file-item" data-type="thong-bao">
                        <div class="download-file-icon">
                            <img src="{{ asset('images/d/go-soft/xml.png') }}" alt="XML" class="file-type-icon">
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
        </div>
        <button class="btn-reload-download" id="reloadLookupBtn">
            <span>Lấy lại dữ liệu</span>
            <img src="{{ asset('images/svg/exchange.png') }}" alt="">
        </button>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let isLoggedIn = localStorage.getItem('goSoftLoggedIn') === 'true';
    let sessionId = localStorage.getItem('goSoftSessionId') || null;
    
    const lookupBtn = document.getElementById('lookupBtn');
    const btnText = lookupBtn.querySelector('.btn-text');
    const toKhaiSelect = document.getElementById('toKhaiSelect');
    const companySelectorRow = document.getElementById('companySelectorRow');
    
    async function checkSessionOnLoad() {
        if (sessionId) {
            try {
                const response = await fetch('{{ route("tools.go-soft.session.status") }}?session_id=' + encodeURIComponent(sessionId), {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                let result;
                if (!response.ok) {
                    const errorText = await response.text();
                    try {
                        result = JSON.parse(errorText);
                    } catch (e) {
                        result = { 
                            status: 'error', 
                            message: `HTTP ${response.status}: ${response.statusText}`,
                            error_code: 'HTTP_ERROR'
                        };
                    }
                } else {
                    result = await response.json();
                }
                
                if (checkSessionError(result)) {
                    return;
                }
                
                if (result.status === 'success') {
                    isLoggedIn = result.is_logged_in || false;
                    if (isLoggedIn) {
                        localStorage.setItem('goSoftLoggedIn', 'true');
                        updateUIForLoggedIn();
                    } else {
                        handleSessionExpired('Chưa đăng nhập vào hệ thống thuế');
                    }
                }
            } catch (error) {
                // Silent error - session check failed
            }
        } else {
            updateUIForLoggedOut();
        }
    }
    
    function updateUIForLoggedIn() {
        btnText.textContent = 'Tra cứu';
        if (companySelectorRow) {
            companySelectorRow.style.display = 'flex';
        }
    }
    
    function updateUIForLoggedOut() {
        btnText.textContent = 'Đăng nhập';
        if (companySelectorRow) {
            companySelectorRow.style.display = 'none';
        }
        isLoggedIn = false;
        localStorage.removeItem('goSoftLoggedIn');
    }
    
    checkSessionOnLoad();
    
    if (isLoggedIn) {
        updateUIForLoggedIn();
    } else {
        updateUIForLoggedOut();
    }
    
    // Checkbox loại - toggle selected class
    document.querySelectorAll('.loai-checkbox-option input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('.loai-checkbox-option');
            if (this.checked) {
                label.classList.add('selected');
            } else {
                label.classList.remove('selected');
            }
        });
    });
    
    // Button click handler
    lookupBtn.addEventListener('click', async function() {
        if (!isLoggedIn) {
            document.getElementById('loginMethodModal').classList.add('show');
        } else {
            await performLookup();
        }
    });
    
    document.querySelectorAll('.login-method-card').forEach(card => {
        card.addEventListener('click', function() {
            const method = this.dataset.method;
            document.getElementById('loginMethodModal').classList.remove('show');
            
            if (method === 'tax') {
                document.getElementById('taxLoginModal').classList.add('show');
                setTimeout(() => {
                    initTaxLogin();
                }, 300);
            } else if (method === 'vneid') {
                showGoSoftFailed('Tính năng VNeID đang được phát triển');
            }
        });
    });
    
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.dataset.close;
            document.getElementById(modalId).classList.remove('show');
        });
    });
    
    document.querySelectorAll('.go-soft-modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            // Chỉ đóng khi click vào background (không phải vào modal content)
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    });
    
    async function initTaxLogin() {
        try {
        const form = document.querySelector('.tax-login-form');
        const submitBtn = form.querySelector('.btn-submit-login');
        const captchaImg = form.querySelector('.captcha-img');
        const captchaInput = form.querySelector('.captcha-input');
        const captchaRefreshBtn = form.querySelector('.captcha-refresh');
        
        // Clear previous captcha input
        if (captchaInput) {
            captchaInput.value = '';
        }
            
            // Disable và show loading cho cả submit button và refresh button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang tạo session...';
            
            if (captchaRefreshBtn) {
                captchaRefreshBtn.disabled = true;
                captchaRefreshBtn.classList.add('loading');
            }
            
            try {
                // Bước 1: Tạo session
                const sessionResponse = await fetch('{{ route("tools.go-soft.session.create") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                if (!sessionResponse.ok) {
                    const errorText = await sessionResponse.text();
                    let errorData;
                    try {
                        errorData = JSON.parse(errorText);
                    } catch (e) {
                        errorData = { message: `HTTP ${sessionResponse.status}: ${sessionResponse.statusText}` };
                    }
                    
                    if (checkSessionError(errorData)) {
                        return;
                    }
                    
                    throw new Error(errorData.message || `HTTP ${sessionResponse.status}: ${sessionResponse.statusText}`);
                }
                
                const sessionData = await sessionResponse.json();
                
                if (checkSessionError(sessionData)) {
                    return;
                }
                
                if (sessionData.status !== 'success' || !sessionData.session_id) {
                    throw new Error(sessionData.message || 'Không thể tạo session. Vui lòng thử lại.');
                }
                
                // Lưu session_id
                sessionId = sessionData.session_id;
                localStorage.setItem('goSoftSessionId', sessionId);
                // Bước 2: Lấy captcha với session_id vừa tạo
                submitBtn.textContent = 'Đang lấy captcha...';
                
                const captchaResponse = await fetch('{{ route("tools.go-soft.login.init") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        session_id: sessionId 
                    })
                });
                
                if (!captchaResponse.ok) {
                    const errorText = await captchaResponse.text();
                    let errorData;
                    try {
                        errorData = JSON.parse(errorText);
                    } catch (e) {
                        errorData = { message: `HTTP ${captchaResponse.status}: ${captchaResponse.statusText}` };
                    }
                    
                    // Kiểm tra error_code trước
                    if (checkSessionError(errorData)) {
                        return;
                    }
                    
                    throw new Error(errorData.message || `HTTP ${captchaResponse.status}: ${captchaResponse.statusText}`);
                }
                
                const captchaData = await captchaResponse.json();
                
                // Kiểm tra error_code trước
                if (checkSessionError(captchaData)) {
                    return;
                }
                
                if (captchaData.status !== 'success' || !captchaData.captcha_base64) {
                    throw new Error(captchaData.message || 'Không thể lấy captcha. Vui lòng thử lại.');
                }
                
                // Xử lý base64 - có thể đã có prefix hoặc chưa
                let captchaBase64 = captchaData.captcha_base64.trim();
                if (!captchaBase64.startsWith('data:image')) {
                    captchaBase64 = 'data:image/png;base64,' + captchaBase64;
                }
                
                // Hiển thị captcha
                if (captchaImg) {
                    captchaImg.src = captchaBase64;
                    captchaImg.style.display = 'block';
                    captchaImg.alt = 'Captcha';
                    captchaImg.onerror = function() {
                        showGoSoftFailed('Không thể hiển thị captcha. Vui lòng thử lại.');
                    };
                }
                
                // Clear và focus vào captcha input
                if (captchaInput) {
                    captchaInput.value = '';
                    captchaInput.disabled = false;
                    setTimeout(() => captchaInput.focus(), 100);
                }
                
                // Refresh captcha handler
                if (captchaRefreshBtn) {
                    captchaRefreshBtn.onclick = () => {
                        if (captchaInput) {
                            captchaInput.value = '';
                        }
                        initTaxLogin();
                    };
                    captchaRefreshBtn.disabled = false;
                    captchaRefreshBtn.classList.remove('loading');
                }
                
                submitBtn.disabled = false;
                submitBtn.textContent = 'Đăng nhập';
                
            } catch (error) {
                console.error('Init login error:', error);
                showGoSoftFailed(error.message || 'Có lỗi xảy ra');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Đăng nhập';
                
                // Reset refresh button - ĐẢM BẢO LUÔN HOẠT ĐỘNG
                if (captchaRefreshBtn) {
                    captchaRefreshBtn.disabled = false;
                    captchaRefreshBtn.classList.remove('loading');
                    // Đảm bảo có event handler để có thể thử lại
                    captchaRefreshBtn.onclick = () => {
                        if (captchaInput) {
                            captchaInput.value = '';
                        }
                        initTaxLogin();
                    };
                }
                
                // Ẩn captcha nếu có lỗi
                if (captchaImg) {
                    captchaImg.style.display = 'none';
                    captchaImg.src = '';
                }
            }
        } catch (error) {
            console.error('Init login error:', error);
            showGoSoftFailed(error.message || 'Lỗi kết nối');
            const form = document.querySelector('.tax-login-form');
            if (form) {
            const submitBtn = form.querySelector('.btn-submit-login');
                if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Đăng nhập';
                }
                
                // Đảm bảo nút refresh luôn hoạt động khi có lỗi - dùng selector đúng
                const captchaRefreshBtn = form.querySelector('.captcha-refresh') || form.querySelector('.captcha-refresh-btn');
                if (captchaRefreshBtn) {
                    captchaRefreshBtn.disabled = false;
                    captchaRefreshBtn.classList.remove('loading');
                    // Đảm bảo có event handler để có thể thử lại
                    captchaRefreshBtn.onclick = () => {
                        const captchaInput = form.querySelector('.captcha-input');
                        if (captchaInput) {
                            captchaInput.value = '';
                        }
                        initTaxLogin();
                    };
                }
            }
        }
    }
    
    document.querySelector('.tax-login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('.btn-submit-login');
        const usernameInput = form.querySelector('input[type="text"]:not(.captcha-input)');
        const passwordInput = form.querySelector('input[type="password"]');
        const captchaInput = form.querySelector('.captcha-input');
        
        const username = usernameInput ? usernameInput.value : '';
        const password = passwordInput ? passwordInput.value : '';
        const captcha = captchaInput ? captchaInput.value : '';
        
        if (!username || !password || !captcha) {
            showGoSoftFailed('Vui lòng điền đầy đủ thông tin');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Đăng nhập';
            return;
        }
        
        if (!sessionId) {
            showGoSoftFailed('Vui lòng đợi hệ thống khởi tạo...');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang đăng nhập...';
        
        try {
            const response = await fetch('{{ route("tools.go-soft.login.submit") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    username: username,
                    password: password,
                    captcha: captcha
                })
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                let errorData;
                try {
                    errorData = JSON.parse(errorText);
                } catch (e) {
                    errorData = { message: `HTTP ${response.status}: ${response.statusText}` };
                }
                
                if (checkSessionError(errorData)) {
                    return;
                }
                
                showGoSoftFailed(errorData.message || 'Đăng nhập thất bại');
                return;
            }
            
            const result = await response.json();
            
            // Kiểm tra session error (không nên xảy ra ở đây nhưng để an toàn)
            if (checkSessionError(result)) {
                return;
            }
            
            if (result.status === 'success') {
                isLoggedIn = true;
                localStorage.setItem('goSoftLoggedIn', 'true');
                btnText.textContent = 'Tra cứu';
                
                // Hiện company selector
                if (companySelectorRow) {
                    companySelectorRow.style.display = 'flex';
                }
                
                // Đóng modal
                document.getElementById('taxLoginModal').classList.remove('show');
                
                // Hiển thị thông báo thành công
                showGoSoftSuccess('Đăng nhập thành công!');
            } else {
                showGoSoftFailed(result.message || 'Đăng nhập thất bại. Sai thông tin đăng nhập');
                // Chỉ clear input captcha, KHÔNG reload captcha tự động
                // User có thể dùng lại captcha hiện tại để nhập lại
                // Chỉ reload khi user click button refresh
                if (captchaInput) {
                    captchaInput.value = '';
                }
                // Không gọi initTaxLogin() - giữ nguyên captcha hiện tại
            }
        } catch (error) {
            showGoSoftFailed(error.message || 'Lỗi kết nối');
            // Chỉ clear input captcha, KHÔNG reload captcha tự động
            if (captchaInput) {
                captchaInput.value = '';
            }
            // Không gọi initTaxLogin() - giữ nguyên captcha hiện tại
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Đăng nhập';
        }
    });
    
    function handleSessionExpired(message = 'Session đã hết hạn') {
        sessionId = null;
        isLoggedIn = false;
        localStorage.removeItem('goSoftSessionId');
        localStorage.removeItem('goSoftLoggedIn');
        
        btnText.textContent = 'Đăng nhập';
        
        if (companySelectorRow) {
            companySelectorRow.style.display = 'none';
        }
        
        toKhaiSelect.innerHTML = '<option value="00">--Tất cả--</option>';
        
        document.getElementById('loginMethodModal').classList.add('show');
    }
    
    // Hiển thị modal thành công
    // Biến lưu timeout và progress state
    let modalTimeouts = {};
    let modalProgressStates = {};
    
    function showGoSoftSuccess(message) {
        const modal = document.getElementById('goSoftSuccessModal');
        const modalMessage = modal.querySelector('.bulk-modal-message');
        const progressBar = document.getElementById('goSoftSuccessProgress');
        const duration = 2000; // 2 giây cho thành công
        
        if (modalMessage && message) {
            modalMessage.textContent = message;
        }
        
        // Clear timeout cũ nếu có
        if (modalTimeouts['goSoftSuccessModal']) {
            clearTimeout(modalTimeouts['goSoftSuccessModal']);
        }
        
        // Reset progress state
        modalProgressStates['goSoftSuccessModal'] = {
            duration: duration,
            elapsed: 0,
            startTime: null,
            paused: false
        };
        
        // Reset và start progress bar
        if (progressBar) {
            progressBar.style.animation = 'none';
            progressBar.style.width = '100%';
            setTimeout(() => {
                progressBar.style.animation = `bulk-modal-progress ${duration}ms linear forwards`;
                modalProgressStates['goSoftSuccessModal'].startTime = Date.now();
            }, 10);
        }
        
        // Setup hover pause/resume
        setupModalHover('goSoftSuccessModal', duration);
        
        modal.classList.add('show');
        
        // Auto close sau duration
        modalTimeouts['goSoftSuccessModal'] = setTimeout(() => {
            closeGoSoftModal('goSoftSuccessModal');
        }, duration);
    }
    
    // Hiển thị modal thất bại
    function showGoSoftFailed(message) {
        const modal = document.getElementById('goSoftFailedModal');
        const modalMessage = modal.querySelector('.bulk-modal-message');
        const progressBar = document.getElementById('goSoftFailedProgress');
        const duration = 5000; // 5 giây cho lỗi
        
        if (modalMessage && message) {
            modalMessage.innerHTML = message.includes('<br>') ? message : `Có lỗi xảy ra!<br>${message}`;
        }
        
        // Clear timeout cũ nếu có
        if (modalTimeouts['goSoftFailedModal']) {
            clearTimeout(modalTimeouts['goSoftFailedModal']);
        }
        
        // Reset progress state
        modalProgressStates['goSoftFailedModal'] = {
            duration: duration,
            elapsed: 0,
            startTime: null,
            paused: false
        };
        
        // Reset và start progress bar
        if (progressBar) {
            progressBar.style.animation = 'none';
            progressBar.style.width = '100%';
            setTimeout(() => {
                progressBar.style.animation = `bulk-modal-progress ${duration}ms linear forwards`;
                modalProgressStates['goSoftFailedModal'].startTime = Date.now();
            }, 10);
        }
        
        // Setup hover pause/resume
        setupModalHover('goSoftFailedModal', duration);
        
        modal.classList.add('show');
        
        // Auto close sau duration
        modalTimeouts['goSoftFailedModal'] = setTimeout(() => {
            closeGoSoftModal('goSoftFailedModal');
        }, duration);
    }
    
    // Setup hover pause/resume cho modal
    function setupModalHover(modalId, duration) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        const progressBar = modal.querySelector('.bulk-modal-progress-bar');
        if (!progressBar) return;
        
        // Remove old listeners bằng cách thêm flag
        modal.removeEventListener('mouseenter', modal._hoverEnter);
        modal.removeEventListener('mouseleave', modal._hoverLeave);
        
        // Hover enter - pause
        modal._hoverEnter = () => {
            const state = modalProgressStates[modalId];
            if (!state || !state.startTime || state.paused) return;
            
            // Tính thời gian đã trôi qua
            const elapsed = Date.now() - state.startTime;
            const remaining = Math.max(0, state.duration - elapsed);
            
            // Lưu state
            state.paused = true;
            state.elapsed = elapsed;
            state.remaining = remaining;
            
            // Pause animation bằng cách set animation-play-state
            progressBar.style.animationPlayState = 'paused';
            
            // Clear timeout
            if (modalTimeouts[modalId]) {
                clearTimeout(modalTimeouts[modalId]);
                delete modalTimeouts[modalId];
            }
        };
        
        // Hover leave - resume
        modal._hoverLeave = () => {
            const state = modalProgressStates[modalId];
            if (!state || !state.paused) return;
            
            // Resume animation
            state.paused = false;
            state.startTime = Date.now() - state.elapsed;
            
            // Resume animation
            progressBar.style.animationPlayState = 'running';
            
            // Resume timeout với thời gian còn lại
            if (state.remaining > 0) {
                modalTimeouts[modalId] = setTimeout(() => {
                    closeGoSoftModal(modalId);
                }, state.remaining);
            }
        };
        
        modal.addEventListener('mouseenter', modal._hoverEnter);
        modal.addEventListener('mouseleave', modal._hoverLeave);
    }
    
    // Đóng modal
    function closeGoSoftModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            // Clear timeout
            if (modalTimeouts[modalId]) {
                clearTimeout(modalTimeouts[modalId]);
                delete modalTimeouts[modalId];
            }
            // Reset progress bar
            const progressBar = modal.querySelector('.bulk-modal-progress-bar');
            if (progressBar) {
                progressBar.style.animation = 'none';
                progressBar.style.width = '100%';
            }
            // Clear state
            delete modalProgressStates[modalId];
        }
    }
    
    function showDownloadSection(downloadData, zipFilename = null, mode = 'single') {
        const mainRow = document.getElementById('goSoftMainRow');
        const downloadSection = document.getElementById('downloadSection');
        const formSection = document.querySelector('.overview-form-section');
        
        if (!downloadSection) {
            return;
        }
        
        if (mainRow) {
            mainRow.classList.add('d-none');
        }
        
        if (formSection) {
            formSection.classList.add('d-none');
        }
        
        downloadSection.classList.remove('d-none');
        
        const loai = downloadData.loai || 'to-khai';
        const zipBase64 = downloadData.zip_base64;
        const total = downloadData.total || 0;
        const batchResults = downloadData.batch_results || {};
        
        // Xử lý batch mode
        if (mode === 'batch' && batchResults) {
            console.log('showDownloadSection batch mode:', {
                batchResults,
                mode,
                hasBatchResults: !!batchResults
            });
            
            // Hiển thị tất cả các loại có trong batch results
            document.querySelectorAll('.download-file-item').forEach(item => {
                const dataType = item.getAttribute('data-type');
                const button = item.querySelector('.btn-download-item');
                
                // Map data-type sang crawl_type
                let crawlType = null;
                if (dataType === 'to-khai') crawlType = 'tokhai';
                else if (dataType === 'giay-nop-tien') crawlType = 'giaynoptien';
                else if (dataType === 'thong-bao') crawlType = 'thongbao';
                
                if (crawlType && batchResults[crawlType]) {
                    const typeResult = batchResults[crawlType];
                    const typeZipBase64 = typeResult.zip_base64;
                    const hasZip = typeZipBase64 && typeof typeZipBase64 === 'string' && typeZipBase64.trim().length > 0;
                    
                    console.log(`Checking ${crawlType}:`, {
                        hasTypeResult: !!typeResult,
                        hasZipBase64: !!typeZipBase64,
                        zipBase64Length: typeZipBase64 ? typeZipBase64.length : 0,
                        hasZip
                    });
                    
                    item.style.display = 'flex';
                    
                    if (button) {
                        if (!hasZip) {
                            button.disabled = true;
                            button.style.opacity = '0.5';
                            button.style.cursor = 'not-allowed';
                        } else {
                            button.disabled = false;
                            button.style.opacity = '1';
                            button.style.cursor = 'pointer';
                            
                            // Lưu zip_base64 vào button để dùng khi click
                            button.dataset.zipBase64 = typeZipBase64;
                            button.dataset.crawlType = crawlType;
                            button.dataset.total = typeResult.total || 0;
                        }
                    }
                } else {
                    item.style.display = 'none';
                }
            });
        } else {
            // Single mode - hiển thị 1 loại
        const hasZipBase64 = zipBase64 && typeof zipBase64 === 'string' && zipBase64.trim().length > 0;
        
        document.querySelectorAll('.download-file-item').forEach(item => {
            const dataType = item.getAttribute('data-type');
            const button = item.querySelector('.btn-download-item');
            
            if (dataType === loai) {
                item.style.display = 'flex';
                
                if (button) {
                    if (!hasZipBase64) {
                        button.disabled = true;
                        button.style.opacity = '0.5';
                        button.style.cursor = 'not-allowed';
                    } else {
                        button.disabled = false;
                        button.style.opacity = '1';
                        button.style.cursor = 'pointer';
                            
                            // Lưu zip_base64 vào button
                            button.dataset.zipBase64 = zipBase64;
                            button.dataset.loai = loai;
                            button.dataset.total = total;
                    }
                }
            } else {
                item.style.display = 'none';
            }
        });
        }
        
        document.querySelectorAll('.btn-download-item').forEach(btn => {
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            newBtn.addEventListener('click', function() {
                // Lấy zip_base64 từ dataset (batch mode) hoặc từ downloadData (single mode)
                let zipBase64ToUse = this.dataset.zipBase64;
                let loaiToUse = this.dataset.loai;
                let totalToUse = parseInt(this.dataset.total) || 0;
                let crawlType = this.dataset.crawlType;
                
                // Nếu không có trong dataset (single mode), dùng từ downloadData
                if (!zipBase64ToUse && mode === 'single') {
                    zipBase64ToUse = zipBase64;
                    loaiToUse = loai;
                    totalToUse = total;
                }
                
                if (!zipBase64ToUse || typeof zipBase64ToUse !== 'string' || zipBase64ToUse.trim().length === 0) {
                    showGoSoftFailed('Không có dữ liệu để tải xuống');
                    return;
                }
                
                // Disable button và show loading
                this.disabled = true;
                const originalText = this.querySelector('span').textContent;
                this.querySelector('span').textContent = 'Đang tải...';
                
                try {
                    const zipBytes = Uint8Array.from(atob(zipBase64ToUse), c => c.charCodeAt(0));
                    
                    let filename = 'download.zip';
                    if (mode === 'batch' && crawlType) {
                        // Batch mode - dùng crawlType
                        if (crawlType === 'tokhai') {
                            filename = `tokhai_${totalToUse}_files.zip`;
                        } else if (crawlType === 'giaynoptien') {
                            filename = `giaynoptien_${totalToUse}_files.zip`;
                        } else if (crawlType === 'thongbao') {
                            filename = `thongbao_${totalToUse}_files.zip`;
                        }
                    } else {
                        // Single mode - dùng loai
                        if (loaiToUse === 'to-khai') {
                            filename = `tokhai_${totalToUse}_files.zip`;
                        } else if (loaiToUse === 'giay-nop-tien') {
                            filename = `giaynoptien_${totalToUse}_files.zip`;
                        } else if (loaiToUse === 'thong-bao') {
                            filename = `thongbao_${totalToUse}_files.zip`;
                        }
                    }
                    
                    // Nếu có zipFilename từ tham số, dùng nó
                    if (zipFilename) {
                        filename = zipFilename;
                    }
                    
                    // Dùng Blob URL nhưng đảm bảo download attribute được set đúng
                    const blob = new Blob([zipBytes], { type: 'application/zip' });
                    const url = URL.createObjectURL(blob);
                    
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = filename;
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    
                    // Trigger download
                    setTimeout(() => {
                    link.click();
                        // Clean up sau khi click
                        setTimeout(() => {
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);
                        }, 100);
                    }, 10);
                    
                    this.disabled = false;
                    this.querySelector('span').textContent = originalText;
                } catch (error) {
                    showGoSoftFailed('Lỗi khi tải xuống file. Vui lòng thử lại.');
                    this.disabled = false;
                    this.querySelector('span').textContent = originalText;
                }
            });
        });
        
        const reloadBtn = document.getElementById('reloadLookupBtn');
        if (reloadBtn) {
            const newReloadBtn = reloadBtn.cloneNode(true);
            reloadBtn.parentNode.replaceChild(newReloadBtn, reloadBtn);
            
            newReloadBtn.addEventListener('click', function() {
                localStorage.removeItem('goSoftDownloadData');
                downloadSection.classList.add('d-none');
                if (mainRow) {
                    mainRow.classList.remove('d-none');
                }
                if (formSection) {
                    formSection.classList.remove('d-none');
                }
            });
        }
    }
    
    function checkSessionError(result) {
        if (!result) return false;
        
        const errorCode = result.error_code || '';
        if (errorCode) {
            if (errorCode === 'SESSION_NOT_FOUND' || 
                errorCode === 'MISSING_SESSION_ID' ||
                errorCode === 'SESSION_EXPIRED' ||
                errorCode.includes('SESSION_NOT_FOUND') || 
                errorCode.includes('MISSING_SESSION_ID') ||
                errorCode.includes('SESSION_EXPIRED') ||
                (errorCode.includes('SESSION') && errorCode.length > 0)) {
                handleSessionExpired(result.message || 'Session không hợp lệ');
                return true;
            }
        }
        
        if (result.message) {
            const messageLower = result.message.toLowerCase();
            if (messageLower.includes('session not found') || 
                messageLower.includes('session expired') ||
                messageLower.includes('session đã hết hạn') ||
                messageLower.includes('session không hợp lệ') ||
                messageLower.includes('missing session')) {
                handleSessionExpired(result.message);
                return true;
            }
        }
        
        return false;
    }
    async function performLookup() {
        if (!sessionId) {
            showGoSoftFailed('Vui lòng đăng nhập trước');
            return;
        }
        
        const startDate = getStartDate();
        const endDate = getEndDate();
        const tokhaiTypeRaw = toKhaiSelect.value || '';
        const tokhaiType = tokhaiTypeRaw ? String(tokhaiTypeRaw) : null;
        
        // Lấy tất cả checkbox được chọn
        const selectedLoaiCheckboxes = document.querySelectorAll('input[name="loai[]"]:checked');
        const selectedLoaiList = Array.from(selectedLoaiCheckboxes).map(cb => cb.value);
        
        if (selectedLoaiList.length === 0) {
            showGoSoftFailed('Vui lòng chọn ít nhất 1 loại');
            return;
        }
        
        if (!startDate || !endDate) {
            showGoSoftFailed('Vui lòng chọn khoảng thời gian');
            return;
        }
        
        // Clear download section trước khi bắt đầu crawl mới
        const downloadSection = document.getElementById('downloadSection');
        if (downloadSection) {
            downloadSection.classList.add('d-none');
        }
        const mainRow = document.getElementById('goSoftMainRow');
        if (mainRow) {
            mainRow.classList.remove('d-none');
        }
        const formSection = document.querySelector('.overview-form-section');
        if (formSection) {
            formSection.classList.remove('d-none');
        }
        
        lookupBtn.disabled = true;
        btnText.textContent = 'Đang kết nối...';
        
        // Hiển thị progress modal và reset message
        showProgressModal();
        updateProgressMessage('Đang kết nối...');
        updateProgressCount(0, 'Đang bắt đầu...');
        
        try {
            let requestBody = {
                session_id: String(sessionId),
                start_date: String(startDate),
                end_date: String(endDate),
                stream: 'true'
            };
            
            // Nếu chọn nhiều loại (2+), dùng batch API
            if (selectedLoaiList.length > 1) {
                // Convert loại sang crawl_types format
                const crawlTypes = selectedLoaiList.map(loai => {
                    if (loai === 'to-khai') return 'tokhai';
                    if (loai === 'giay-nop-tien') return 'giaynoptien';
                    if (loai === 'thong-bao') return 'thongbao';
                    return loai;
                });
                
                requestBody.crawl_types = crawlTypes;
                if (tokhaiType && crawlTypes.includes('tokhai')) {
                    requestBody.tokhai_type = String(tokhaiType);
                }
                
                // Dùng batch API
                const routeUrl = '{{ route("tools.go-soft.crawl.batch") }}';
                await performBatchLookup(routeUrl, requestBody, selectedLoaiList);
            } else {
                // Chỉ chọn 1 loại - dùng API riêng như cũ
                const loai = selectedLoaiList[0];
                let routeUrl;
            
            if (loai === 'to-khai') {
                routeUrl = '{{ route("tools.go-soft.crawl.tokhai") }}';
                if (tokhaiType) {
                    requestBody.tokhai_type = String(tokhaiType);
                }
                    await performLookupWithSSE(routeUrl, requestBody, loai);
            } else if (loai === 'thong-bao') {
                routeUrl = '{{ route("tools.go-soft.crawl.thongbao") }}';
                    await performLookupWithSSE(routeUrl, requestBody, loai);
            } else if (loai === 'giay-nop-tien') {
                routeUrl = '{{ route("tools.go-soft.crawl.giaynoptien") }}';
                    await performLookupWithSSE(routeUrl, requestBody, loai);
            } else {
                throw new Error('Loại không hợp lệ');
            }
            }
        } catch (error) {
            hideProgressModal();
            
            // Check session error - kiểm tra cả message và error object
            const errorMessage = error.message || '';
            const errorMessageLower = errorMessage.toLowerCase();
            
            // Check connection error - nếu không kết nối được API thì clear session và yêu cầu đăng nhập lại
            if (errorMessage.includes('CONNECTION_ERROR:') ||
                errorMessageLower.includes('failed to connect') ||
                errorMessageLower.includes("couldn't connect") ||
                errorMessageLower.includes('econnrefused') ||
                errorMessageLower.includes('networkerror') ||
                errorMessageLower.includes('network request failed') ||
                errorMessageLower.includes('fetch failed')) {
                // Extract message từ CONNECTION_ERROR nếu có
                const connectionMessage = errorMessage.includes('CONNECTION_ERROR:') 
                    ? errorMessage.split('CONNECTION_ERROR:')[1].trim()
                    : 'Lỗi hệ thống: Không thể kết nối đến server. Vui lòng đăng nhập lại.';
                handleSessionExpired(connectionMessage);
                return;
            }
            
            if (errorMessageLower.includes('session not found') ||
                errorMessageLower.includes('session expired') ||
                errorMessageLower.includes('session không hợp lệ') ||
                errorMessageLower.includes('session đã hết hạn')) {
                handleSessionExpired(errorMessage || 'Session không hợp lệ');
                return;
            }
            
            // Nếu error có error_code, check session error
            if (error.error_code || (error.response && error.response.error_code)) {
                const errorCode = error.error_code || error.response.error_code;
                if (checkSessionError({ error_code: errorCode, message: errorMessage })) {
                    return;
                }
            }
            
            if (error.name === 'AbortError') {
                showGoSoftFailed('Quá trình tra cứu mất quá nhiều thời gian. Vui lòng thử lại với khoảng thời gian ngắn hơn.');
            } else {
                showGoSoftFailed(errorMessage || 'Lỗi kết nối');
            }
        } finally {
            lookupBtn.disabled = false;
            btnText.textContent = 'Tra cứu';
        }
    }
    
    // Batch lookup - crawl nhiều loại đồng thời
    async function performBatchLookup(routeUrl, requestBody, selectedLoaiList) {
        return new Promise((resolve, reject) => {
            // Clear download section trước khi bắt đầu crawl mới
            const downloadSection = document.getElementById('downloadSection');
            if (downloadSection) {
                downloadSection.classList.add('d-none');
            }
            const mainRow = document.getElementById('goSoftMainRow');
            if (mainRow) {
                mainRow.classList.remove('d-none');
            }
            const formSection = document.querySelector('.overview-form-section');
            if (formSection) {
                formSection.classList.remove('d-none');
            }
            
            let results = [];
            let batchResults = {};
            let totalFiles = 0;
            let zipBase64 = null;
            let zipFilename = null;
            let currentType = '';
            let totalCount = 0; // Tổng số items đã tìm thấy
            let hasError = false; // Flag để track lỗi
            
            fetch(routeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'text/event-stream'
                },
                body: JSON.stringify(requestBody)
            }).then(async response => {
                if (!response.ok) {
                    // Parse response body để lấy error_code
                    let errorData = null;
                    try {
                        const errorText = await response.text();
                        try {
                            errorData = JSON.parse(errorText);
                        } catch (e) {
                            errorData = { message: errorText || `HTTP ${response.status}: ${response.statusText}` };
                        }
                    } catch (e) {
                        errorData = { message: `HTTP ${response.status}: ${response.statusText}` };
                    }
                    
                    // Check session error trước khi throw
                    if (checkSessionError(errorData)) {
                        reject(new Error(errorData.message || 'Session không hợp lệ'));
                        return;
                    }
                    
                    throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`);
                }
                
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';
                
                function processChunk({ done, value }) {
                    if (done) {
                        hideProgressModal();
                        
                        // Nếu có lỗi, không hiển thị trang kết quả
                        if (hasError) {
                            console.warn('Batch stream completed with error - not showing results');
                            resolve();
                            return;
                        }
                        
                        // Hoàn thành - hiển thị kết quả
                        const downloadData = {
                            status: 'success',
                            total: totalFiles,
                            results: results,
                            zip_base64: zipBase64,
                            batch_results: batchResults,
                            loai: 'batch'
                        };
                        
                        // Kiểm tra xem có batchResults với zip_base64 không
                        const hasBatchResults = batchResults && Object.keys(batchResults).length > 0;
                        const hasAnyZipBase64 = hasBatchResults && Object.values(batchResults).some(result => 
                            result && result.zip_base64 && typeof result.zip_base64 === 'string' && result.zip_base64.trim().length > 0
                        );
                        
                        console.log('Batch complete:', {
                            hasBatchResults,
                            hasAnyZipBase64,
                            batchResultsKeys: hasBatchResults ? Object.keys(batchResults) : [],
                            totalFiles
                        });
                        
                        if (hasAnyZipBase64 || zipBase64) {
                            showDownloadSection(downloadData, zipFilename || `batch_${totalFiles}_files.zip`, 'batch');
                        } else if (totalFiles === 0) {
                            showGoSoftFailed('Không tìm thấy dữ liệu trong khoảng thời gian đã chọn');
                        } else {
                            // Có files nhưng không có zip_base64 - vẫn hiển thị download section nhưng buttons sẽ disabled
                            showDownloadSection(downloadData, zipFilename || `batch_${totalFiles}_files.zip`, 'batch');
                        }
                        
                        resolve();
                        return;
                    }
                    
                    buffer += decoder.decode(value, { stream: true });
                    
                    // Parse SSE events - tìm các event hoàn chỉnh (kết thúc bằng \n\n)
                    let eventEnd;
                    while ((eventEnd = buffer.indexOf('\n\n')) !== -1) {
                        const eventBlock = buffer.substring(0, eventEnd);
                        buffer = buffer.substring(eventEnd + 2);
                        
                        // Parse từng line trong event block
                        const lines = eventBlock.split('\n');
                        for (const line of lines) {
                            if (line.startsWith('data: ')) {
                                try {
                                    const data = JSON.parse(line.slice(6));
                                    
                                    if (data.type === 'batch_start') {
                                        // Reset state khi bắt đầu batch mới
                                        totalCount = 0;
                                        results = [];
                                        batchResults = {};
                                        updateProgressModal(`Bắt đầu crawl ${data.total_types} loại...`, 0);
                                        updateProgressCount(0, 'Đang bắt đầu...');
                                    } else if (data.type === 'batch_progress') {
                                        currentType = data.current_type;
                                        const percent = Math.round((data.type_index - 1) / data.total_types * 100);
                                        updateProgressModal(`Đang crawl ${getLoaiLabel(currentType)} (${data.type_index}/${data.total_types})...`, percent);
                                    } else if (data.type === 'progress') {
                                        // Cập nhật progress từ individual crawl
                                        if (data.current !== undefined && data.total !== undefined) {
                                            updateProgressBar(data.current, data.total);
                                        }
                                        if (data.message) {
                                            updateProgressModal(data.message);
                                        }
                                    } else if (data.type === 'item') {
                                        // Cập nhật khi có item mới
                                        totalCount++;
                                        results.push(data.data || data);
                                        updateProgressCount(totalCount, `Đã tìm thấy ${totalCount} items...`);
                                    } else if (data.type === 'download_start') {
                                        updateProgressModal(`[${getLoaiLabel(currentType)}] Bắt đầu tải ${data.total_to_download} file...`, null);
                                        // Hiển thị progress bar
                                        const progressBarContainer = document.getElementById('progressBarContainer');
                                        if (progressBarContainer) {
                                            progressBarContainer.style.display = 'block';
                                        }
                                    } else if (data.type === 'download_progress') {
                                        updateProgressBar(data.downloaded || 0, data.total || 1);
                                        updateProgressModal(`[${getLoaiLabel(currentType)}] Đã tải ${data.downloaded}/${data.total} (${data.percent}%)`, data.percent);
                                        // Không cập nhật progressCount ở đây vì đây là số file download, không phải số items
                                        // progressCount sẽ được cập nhật khi type_complete
                                    } else if (data.type === 'type_complete') {
                                        const typeResult = data.result || {};
                                        batchResults[data.crawl_type] = typeResult;
                                        console.log(`Type complete for ${data.crawl_type}:`, {
                                            hasZipBase64: !!(typeResult.zip_base64),
                                            zipBase64Length: typeResult.zip_base64 ? typeResult.zip_base64.length : 0,
                                            total: typeResult.total
                                        });
                                        // Cập nhật totalCount từ typeResult
                                        if (typeResult.total) {
                                            totalCount += typeResult.total;
                                            updateProgressCount(totalCount, `Đã tìm thấy ${totalCount} items...`);
                                        }
                                    } else if (data.type === 'type_error') {
                                        hasError = true; // Đánh dấu có lỗi
                                        console.warn(`Error crawling ${data.crawl_type}:`, data.error);
                                        // Check session error - nếu session expired thì dừng batch
                                        if (checkSessionError({ 
                                            message: data.error,
                                            error_code: data.error_code || (data.error && data.error.includes('SESSION_EXPIRED') ? 'SESSION_EXPIRED' : null)
                                        })) {
                                            reject(new Error(data.error));
                                            return;
                                        }
                                    } else if (data.type === 'batch_complete') {
                                        totalFiles = data.total_files || 0;
                                        results = data.results || [];
                                        if (data.zip_base64) {
                                            zipBase64 = data.zip_base64;
                                        }
                                        zipFilename = data.zip_filename;
                                        // Merge batch_results từ event vào batchResults hiện tại
                                        if (data.batch_results) {
                                            Object.assign(batchResults, data.batch_results);
                                        }
                                    } else if (data.type === 'batch_zip_data') {
                                        zipBase64 = data.zip_base64 || null;
                                        if (data.zip_filename) {
                                            zipFilename = data.zip_filename;
                                        }
                                    } else if (data.type === 'zip_data') {
                                        // Event zip_data từ individual crawl - lưu vào batchResults
                                        if (data.crawl_type && data.zip_base64) {
                                            if (!batchResults[data.crawl_type]) {
                                                batchResults[data.crawl_type] = {};
                                            }
                                            batchResults[data.crawl_type].zip_base64 = data.zip_base64;
                                            if (data.zip_filename) {
                                                batchResults[data.crawl_type].zip_filename = data.zip_filename;
                                            }
                                        }
                                    } else if (data.type === 'error') {
                                        hasError = true; // Đánh dấu có lỗi
                                        // Check session error trước khi throw
                                        if (checkSessionError({ 
                                            message: data.error,
                                            error_code: data.error_code || (data.error && data.error.includes('SESSION_EXPIRED') ? 'SESSION_EXPIRED' : null)
                                        })) {
                                            reject(new Error(data.error));
                                            return;
                                        }
                                        throw new Error(data.error || 'Lỗi không xác định');
                                    }
                                } catch (e) {
                                    if (e.message && !e.message.includes('JSON')) {
                                        throw e;
                                    }
                                }
                            }
                        }
                    }
                    
                    return reader.read().then(processChunk);
                }
                
                return reader.read().then(processChunk);
            }).catch(error => {
                hideProgressModal();
                
                // Check connection error
                const errorMessage = error.message || '';
                const errorMessageLower = errorMessage.toLowerCase();
                
                if (errorMessageLower.includes('failed to connect') ||
                    errorMessageLower.includes("couldn't connect") ||
                    errorMessageLower.includes('econnrefused') ||
                    errorMessageLower.includes('networkerror') ||
                    errorMessageLower.includes('network request failed') ||
                    errorMessageLower.includes('fetch failed')) {
                    // Reject với message rõ ràng để catch block ngoài xử lý
                    reject(new Error('CONNECTION_ERROR: Lỗi hệ thống: Không thể kết nối đến server'));
                    return;
                }
                
                reject(error);
            });
        });
    }
    
    function getLoaiLabel(crawlType) {
        switch (crawlType) {
            case 'tokhai': return 'Tờ khai';
            case 'thongbao': return 'Thông báo';
            case 'giaynoptien': return 'Giấy nộp tiền';
            default: return crawlType;
        }
    }
    
    // SSE streaming lookup - hiển thị progress realtime
    async function performLookupWithSSE(routeUrl, requestBody, loai) {
        return new Promise((resolve, reject) => {
            // Reset state mỗi lần gọi
            let results = [];
            let totalCount = 0;
            let downloadedCount = 0;
            let zipBase64 = null;
            let zipFilename = null;
            let hasError = false; // Flag để track lỗi
            
            // Đảm bảo loai được set đúng
            const currentLoai = loai || 'to-khai';
            console.log('performLookupWithSSE called with loai:', currentLoai);
            
            // Tạo URL với query params cho SSE
            const params = new URLSearchParams();
            params.append('session_id', requestBody.session_id);
            params.append('start_date', requestBody.start_date);
            params.append('end_date', requestBody.end_date);
            if (requestBody.tokhai_type) {
                params.append('tokhai_type', requestBody.tokhai_type);
            }
            params.append('stream', 'true');
            params.append('_token', '{{ csrf_token() }}');
            
            // Dùng fetch với streaming reader
            fetch(routeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'text/event-stream'
                },
                body: JSON.stringify(requestBody)
            }).then(async response => {
                if (!response.ok) {
                    // Parse response body để lấy error_code
                    let errorData = null;
                    try {
                        const errorText = await response.text();
                        try {
                            errorData = JSON.parse(errorText);
                        } catch (e) {
                            errorData = { message: errorText || `HTTP ${response.status}: ${response.statusText}` };
                        }
                    } catch (e) {
                        errorData = { message: `HTTP ${response.status}: ${response.statusText}` };
                    }
                    
                    // Check session error trước khi throw
                    if (checkSessionError(errorData)) {
                        reject(new Error(errorData.message || 'Session không hợp lệ'));
                        return;
                    }
                    
                    throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`);
                }
                
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';
                
                function processChunk({ done, value }) {
                    if (done) {
                        hideProgressModal();
                        
                        // Nếu có lỗi, không hiển thị trang kết quả
                        if (hasError) {
                            console.warn('Stream completed with error - not showing results');
                            resolve();
                            return;
                        }
                        
                        // Chỉ hiển thị kết quả nếu có zip_base64 hoặc có results
                        if (zipBase64 || (results && results.length > 0)) {
                            const downloadData = {
                                status: 'success',
                                total: totalCount,
                                results: results,
                                zip_base64: zipBase64,
                                loai: currentLoai,
                                timestamp: Date.now()
                            };
                            
                            localStorage.setItem('goSoftDownloadData', JSON.stringify(downloadData));
                            showDownloadSection(downloadData);
                        } else {
                            // Không có data - có thể đã bị lỗi trước đó
                            // Không hiển thị trang kết quả
                            console.warn('No data to display - stream completed without zip_base64 or results');
                        }
                        
                        resolve();
                        return;
                    }
                    
                    buffer += decoder.decode(value, { stream: true });
                    
                    // Parse SSE events
                    const lines = buffer.split('\n');
                    buffer = lines.pop(); // Giữ lại phần chưa hoàn thành
                    
                    for (const line of lines) {
                        if (line.startsWith('data: ')) {
                            try {
                                const eventData = JSON.parse(line.slice(6));
                                handleSSEEvent(eventData);
                            } catch (e) {
                                // Ignore parse errors
                            }
                        }
                    }
                    
                    // Continue reading
                    reader.read().then(processChunk).catch(reject);
                }
                
                function handleSSEEvent(event) {
                    const loaiLabel = currentLoai === 'to-khai' ? 'tờ khai' : (currentLoai === 'thong-bao' ? 'thông báo' : 'giấy nộp tiền');
                    
                    switch (event.type) {
                        case 'info':
                            updateProgressMessage(event.message || 'Đang xử lý...');
                            break;
                            
                        case 'progress':
                            if (event.total) {
                                updateProgressBar(event.current || 0, event.total);
                            }
                            if (event.message) {
                                updateProgressMessage(event.message);
                            }
                            break;
                        
                        // Xử lý download progress (cho thông báo và giấy nộp tiền)
                        case 'download_start':
                            updateProgressMessage(`Bắt đầu tải ${event.total_to_download} ${loaiLabel}...`);
                            break;
                            
                        case 'download_progress':
                            updateProgressBar(event.downloaded || 0, event.total || 1);
                            updateProgressMessage(`Đã tải ${event.downloaded}/${event.total} ${loaiLabel} (${event.percent}%)`);
                            break;
                            
                        case 'download_complete':
                            updateProgressMessage(`Hoàn thành tải ${event.downloaded}/${event.total} ${loaiLabel}`);
                            break;
                            
                        case 'item':
                            downloadedCount++;
                            results.push(event.data);
                            updateProgressCount(downloadedCount, `Đã tìm thấy ${downloadedCount} ${loaiLabel}...`);
                            break;
                            
                        case 'complete':
                            totalCount = event.total || results.length;
                            if (event.zip_base64) {
                                zipBase64 = event.zip_base64;
                            }
                            zipFilename = event.zip_filename || null;
                            // Tính lại loaiLabel để đảm bảo đúng
                            const finalLoaiLabel = currentLoai === 'to-khai' ? 'tờ khai' : (currentLoai === 'thong-bao' ? 'thông báo' : 'giấy nộp tiền');
                            updateProgressMessage(`Hoàn thành! Tổng cộng ${totalCount} ${finalLoaiLabel}.`);
                            console.log('Complete event:', { totalCount, currentLoai, finalLoaiLabel });
                            break;
                            
                        case 'zip_data':
                            // Event riêng chứa zip_base64 (tránh JSON quá lớn)
                            zipBase64 = event.zip_base64 || null;
                            if (event.zip_filename) {
                                zipFilename = event.zip_filename;
                            }
                            break;
                            
                        case 'error':
                            hasError = true; // Đánh dấu có lỗi
                            hideProgressModal();
                            
                            // Check session error với error_code
                            if (checkSessionError({ 
                                message: event.error,
                                error_code: event.error_code || (event.error && event.error.includes('SESSION_EXPIRED') ? 'SESSION_EXPIRED' : null)
                            })) {
                                reject(new Error(event.error));
                                return;
                            }
                            
                            showGoSoftFailed(event.error || 'Có lỗi xảy ra');
                            reject(new Error(event.error));
                            break;
                            
                        case 'warning':
                            console.warn('SSE Warning:', event.message);
                            break;
                    }
                }
                
                reader.read().then(processChunk).catch(reject);
                
            }).catch(error => {
                hideProgressModal();
                
                // Check connection error
                const errorMessage = error.message || '';
                const errorMessageLower = errorMessage.toLowerCase();
                
                if (errorMessageLower.includes('failed to connect') ||
                    errorMessageLower.includes("couldn't connect") ||
                    errorMessageLower.includes('econnrefused') ||
                    errorMessageLower.includes('networkerror') ||
                    errorMessageLower.includes('network request failed') ||
                    errorMessageLower.includes('fetch failed')) {
                    // Reject với message rõ ràng để catch block ngoài xử lý
                    reject(new Error('CONNECTION_ERROR: Lỗi hệ thống: Không thể kết nối đến server'));
                    return;
                }
                
                reject(error);
            });
        });
    }
    
    // Sync lookup - fallback cho thông báo và giấy nộp tiền
    async function performLookupSync(routeUrl, requestBody, loai) {
        updateProgressMessage('Đang tra cứu...');
        
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 600000);
        
        try {
            const response = await fetch(routeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestBody),
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            hideProgressModal();
            
            if (!response.ok) {
                const errorText = await response.text();
                let errorData;
                try {
                    errorData = JSON.parse(errorText);
                } catch (e) {
                    errorData = { message: `HTTP ${response.status}: ${response.statusText}` };
                }
                
                if (checkSessionError(errorData)) {
                    return;
                }
                
                throw new Error(errorData.message || 'Có lỗi xảy ra');
            }
            
            const result = await response.json();
            
            if (checkSessionError(result)) {
                return;
            }
            
            if (result.status === 'success') {
                const downloadData = {
                    status: 'success',
                    total: result.total || 0,
                    results: result.results || [],
                    zip_base64: result.zip_base64 || null,
                    loai: loai,
                    timestamp: Date.now()
                };
                
                localStorage.setItem('goSoftDownloadData', JSON.stringify(downloadData));
                showDownloadSection(downloadData);
            } else {
                showGoSoftFailed(result.message || 'Tra cứu thất bại');
            }
        } catch (error) {
            clearTimeout(timeoutId);
            hideProgressModal();
            throw error;
        }
    }
    
    // Progress modal functions
    function showProgressModal() {
        let modal = document.getElementById('progressModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'progressModal';
            modal.className = 'go-soft-modal';
            modal.innerHTML = `
                <div class="go-soft-modal-content" style="max-width: 400px; text-align: center;">
                    <div class="progress-spinner" style="margin: 20px auto;">
                        <svg width="50" height="50" viewBox="0 0 50 50" style="animation: spin 1s linear infinite;">
                            <circle cx="25" cy="25" r="20" fill="none" stroke="#227447" stroke-width="4" stroke-dasharray="80 40" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <p id="progressMessage" style="margin: 15px 0; color: #333; font-size: 14px;">Đang kết nối...</p>
                    <div id="progressBarContainer" style="display: none; margin: 15px 0;">
                        <div style="background: #e5e7eb; border-radius: 10px; height: 8px; overflow: hidden;">
                            <div id="progressBar" style="background: linear-gradient(90deg, #10A142, #227447); height: 100%; width: 0%; transition: width 0.3s ease;"></div>
                        </div>
                        <p id="progressCount" style="margin-top: 10px; color: #666; font-size: 12px;"></p>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Add spinner animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        }
        modal.classList.add('show');
    }
    
    function hideProgressModal() {
        const modal = document.getElementById('progressModal');
        if (modal) {
            modal.classList.remove('show');
        }
    }
    
    function updateProgressMessage(message) {
        const el = document.getElementById('progressMessage');
        if (el) el.textContent = message;
    }
    
    function updateProgressBar(current, total) {
        const container = document.getElementById('progressBarContainer');
        const bar = document.getElementById('progressBar');
        if (container) container.style.display = 'block';
        if (bar && total > 0) {
            bar.style.width = Math.min((current / total) * 100, 100) + '%';
        }
    }
    
    function updateProgressCount(count, message) {
        const el = document.getElementById('progressCount');
        if (el) el.textContent = message || `Đã tải ${count} file...`;
        
        const msgEl = document.getElementById('progressMessage');
        if (msgEl) msgEl.textContent = message || `Đang tải tờ khai...`;
    }
    
    // Hàm update progress modal với message và percent (dùng cho batch crawl)
    function updateProgressModal(message, percent) {
        updateProgressMessage(message);
        if (percent !== null && percent !== undefined) {
            const container = document.getElementById('progressBarContainer');
            const bar = document.getElementById('progressBar');
            if (container) container.style.display = 'block';
            if (bar) {
                bar.style.width = Math.min(percent, 100) + '%';
            }
        }
    }
    
    function getStartDate() {
        const selects = document.querySelectorAll('.date-range-group select');
        if (selects.length < 3) return null;
        const day = selects[0].value.padStart(2, '0');
        const month = selects[1].value.padStart(2, '0');
        const year = selects[2].value;
        return `${day}/${month}/${year}`;
    }
    
    function getEndDate() {
        const selects = document.querySelectorAll('.date-range-group select');
        if (selects.length < 6) return null;
        const day = selects[3].value.padStart(2, '0');
        const month = selects[4].value.padStart(2, '0');
        const year = selects[5].value;
        return `${day}/${month}/${year}`;
    }
    
    function showResults(result) {
        
        // Tạo modal hoặc section để hiển thị kết quả
        const resultHtml = `
            <div style="margin-top: 20px; padding: 20px; background: #f5f5f5; border-radius: 8px;">
                <h3>Kết quả tra cứu</h3>
                <p><strong>Tổng số:</strong> ${result.total || 0} tờ khai</p>
                <div style="max-height: 400px; overflow-y: auto;">
                    <pre style="background: white; padding: 15px; border-radius: 4px; overflow-x: auto;">${JSON.stringify(result.results || [], null, 2)}</pre>
                </div>
            </div>
        `;
        
        // Thêm vào form section
        const formSection = document.querySelector('.overview-form-section');
        const existingResult = formSection.querySelector('.result-display');
        if (existingResult) {
            existingResult.remove();
        }
        
        const resultDiv = document.createElement('div');
        resultDiv.className = 'result-display';
        resultDiv.innerHTML = resultHtml;
        formSection.appendChild(resultDiv);
        
        // Scroll to result
        resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    document.querySelectorAll('.sidebar-menu-item').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!isLoggedIn && this.getAttribute('href') !== '{{ route("tools.go-soft") }}') {
                e.preventDefault();
                document.getElementById('loginMethodModal').classList.add('show');
            }
        });
    });
    
    function initDateSelects() {
        const selects = document.querySelectorAll('.date-select');
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth() + 1;
        const currentDay = currentDate.getDate();
        
        // Start date - first day of current month
        if (selects[0]) {
            for (let i = 1; i <= 31; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i.toString().padStart(2, '0');
                if (i === 1) option.selected = true;
                selects[0].appendChild(option);
            }
        }
        if (selects[1]) {
            for (let i = 1; i <= 12; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i.toString().padStart(2, '0');
                if (i === currentMonth) option.selected = true;
                selects[1].appendChild(option);
            }
        }
        if (selects[2]) {
            for (let i = currentYear; i >= currentYear - 5; i--) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                if (i === currentYear) option.selected = true;
                selects[2].appendChild(option);
            }
        }
        
        // End date - current date
        if (selects[3]) {
            for (let i = 1; i <= 31; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i.toString().padStart(2, '0');
                if (i === currentDay) option.selected = true;
                selects[3].appendChild(option);
            }
        }
        if (selects[4]) {
            for (let i = 1; i <= 12; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i.toString().padStart(2, '0');
                if (i === currentMonth) option.selected = true;
                selects[4].appendChild(option);
            }
        }
        if (selects[5]) {
            for (let i = currentYear; i >= currentYear - 5; i--) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                if (i === currentYear) option.selected = true;
                selects[5].appendChild(option);
            }
        }
    }
    
    initDateSelects();
    
    const companySelectorHeader = document.getElementById('companySelectorHeader');
    const companyDropdown = document.getElementById('companyDropdown');
    const selectedCompanyName = document.getElementById('selectedCompanyName');
    const selectedCompanyMST = document.getElementById('selectedCompanyMST');
    const companyMSTValue = document.getElementById('companyMSTValue');
    const companyList = document.getElementById('companyList');
    
    if (companySelectorHeader) {
        companySelectorHeader.addEventListener('click', function(e) {
            e.stopPropagation();
            companySelectorHeader.classList.toggle('active');
            companyDropdown.classList.toggle('show');
        });
    }
    
    document.addEventListener('click', function(e) {
        if (companyDropdown && !companyDropdown.contains(e.target) && !companySelectorHeader.contains(e.target)) {
            companySelectorHeader.classList.remove('active');
            companyDropdown.classList.remove('show');
        }
    });
    
    if (companyList) {
        companyList.addEventListener('click', function(e) {
            const companyItem = e.target.closest('.company-item');
            if (companyItem) {
                const companyName = companyItem.dataset.companyName;
                const companyMST = companyItem.dataset.companyMst;
                
                selectedCompanyName.textContent = companyName;
                companyMSTValue.textContent = companyMST;
                
                companyList.querySelectorAll('.company-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                companyItem.classList.add('active');
                
                companySelectorHeader.classList.remove('active');
                companyDropdown.classList.remove('show');
                
                localStorage.setItem('goSoftSelectedCompany', JSON.stringify({
                    id: companyItem.dataset.companyId,
                    name: companyName,
                    mst: companyMST
                }));
            }
        });
    }
    
    const savedCompany = localStorage.getItem('goSoftSelectedCompany');
    if (savedCompany) {
        try {
            const company = JSON.parse(savedCompany);
            selectedCompanyName.textContent = company.name;
            companyMSTValue.textContent = company.mst;
            
            const companyItem = companyList.querySelector(`[data-company-id="${company.id}"]`);
            if (companyItem) {
                companyItem.classList.add('active');
            }
        } catch (e) {
            // Silent error
        }
    }
    
    window.toggleTaxPassword = function() {
        const passwordInput = document.getElementById('taxPassword');
        const passwordIcon = document.getElementById('tax-password-icon');
        
        if (!passwordInput || !passwordIcon) {
            return;
        }
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.src = '{{ asset("/images/svg/eye-slash.svg") }}';
            passwordIcon.alt = 'Hide Password';
        } else {
            passwordInput.type = 'password';
            passwordIcon.src = '{{ asset("/images/svg/eye.svg") }}';
            passwordIcon.alt = 'Show Password';
        }
    };
});
</script>
@endpush


