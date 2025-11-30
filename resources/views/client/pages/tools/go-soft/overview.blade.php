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
            
            <!-- Loại -->
            <div class="form-row">
                <label class="form-label">Loại</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="loai" value="to-khai" checked>
                        <span>Tờ khai</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="loai" value="giay-nop-tien">
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
                
                // Reset refresh button
                if (captchaRefreshBtn) {
                    captchaRefreshBtn.disabled = false;
                    captchaRefreshBtn.classList.remove('loading');
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
            const submitBtn = form.querySelector('.btn-submit-login');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Đăng nhập';
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
                if (captchaInput) {
                    captchaInput.value = '';
                }
                await initTaxLogin();
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
                // Refresh captcha và clear input
                if (captchaInput) {
                    captchaInput.value = '';
                }
                await initTaxLogin();
            }
        } catch (error) {
            showGoSoftFailed(error.message || 'Lỗi kết nối');
            await initTaxLogin();
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
    function showGoSoftSuccess(message) {
        const modal = document.getElementById('goSoftSuccessModal');
        const modalMessage = modal.querySelector('.bulk-modal-message');
        if (modalMessage && message) {
            modalMessage.textContent = message;
        }
        modal.classList.add('show');
        setTimeout(() => {
            modal.classList.remove('show');
        }, 2000);
    }
    
    // Hiển thị modal thất bại
    function showGoSoftFailed(message) {
        const modal = document.getElementById('goSoftFailedModal');
        const modalMessage = modal.querySelector('.bulk-modal-message');
        if (modalMessage && message) {
            modalMessage.innerHTML = message.includes('<br>') ? message : `Có lỗi xảy ra!<br>${message}`;
        }
        modal.classList.add('show');
        setTimeout(() => {
            modal.classList.remove('show');
        }, 2000);
    }
    
    function showDownloadSection(downloadData) {
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
                    }
                }
            } else {
                item.style.display = 'none';
            }
        });
        
        document.querySelectorAll('.btn-download-item').forEach(btn => {
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            newBtn.addEventListener('click', function() {
                if (!hasZipBase64) {
                    showGoSoftFailed('Không có dữ liệu để tải xuống');
                    return;
                }
                
                // Disable button và show loading
                this.disabled = true;
                const originalText = this.querySelector('span').textContent;
                this.querySelector('span').textContent = 'Đang tải...';
                
                try {
                    const zipBytes = Uint8Array.from(atob(zipBase64), c => c.charCodeAt(0));
                    const blob = new Blob([zipBytes], { type: 'application/zip' });
                    const url = URL.createObjectURL(blob);
                    
                    let filename = 'download.zip';
                    if (loai === 'to-khai') {
                        filename = `tokhai_${total}_files.zip`;
                    } else if (loai === 'giay-nop-tien') {
                        filename = `giaynoptien_${total}_files.zip`;
                    } else if (loai === 'thong-bao') {
                        filename = `thongbao_${total}_files.zip`;
                    }
                    
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);
                    
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
        const loai = document.querySelector('input[name="loai"]:checked')?.value || 'to-khai';
        
        if (!startDate || !endDate) {
            showGoSoftFailed('Vui lòng chọn khoảng thời gian');
            return;
        }
        
        lookupBtn.disabled = true;
        btnText.textContent = 'Đang tra cứu...';
        
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 600000);
        
        try {
            let routeUrl;
            let requestBody = {
                session_id: String(sessionId),
                start_date: String(startDate),
                end_date: String(endDate)
            };
            
            if (loai === 'to-khai') {
                routeUrl = '{{ route("tools.go-soft.crawl.tokhai") }}';
                if (tokhaiType) {
                    requestBody.tokhai_type = String(tokhaiType);
                }
            } else if (loai === 'thong-bao') {
                routeUrl = '{{ route("tools.go-soft.crawl.thongbao") }}';
            } else if (loai === 'giay-nop-tien') {
                routeUrl = '{{ route("tools.go-soft.crawl.giaynoptien") }}';
            } else {
                throw new Error('Loại không hợp lệ');
            }
            
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
                const loai = document.querySelector('input[name="loai"]:checked')?.value || 'to-khai';
                
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
            
            if (error.message && (
                error.message.toLowerCase().includes('session not found') ||
                error.message.toLowerCase().includes('session expired')
            )) {
                handleSessionExpired(error.message);
                return;
            }
            
            if (error.name === 'AbortError') {
                showGoSoftFailed('Quá trình tra cứu mất quá nhiều thời gian. Vui lòng thử lại với khoảng thời gian ngắn hơn hoặc chọn loại tờ khai cụ thể.');
            } else if (error.message.includes('timeout') || error.message.includes('Timeout')) {
                showGoSoftFailed('Quá trình tra cứu mất quá nhiều thời gian. Vui lòng thử lại với khoảng thời gian ngắn hơn.');
            } else {
                showGoSoftFailed(error.message || 'Lỗi kết nối');
            }
        } finally {
            lookupBtn.disabled = false;
            btnText.textContent = 'Tra cứu';
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


