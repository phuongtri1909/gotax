<!-- Login Method Selection Modal -->
<div class="go-soft-modal" id="loginMethodModal">
    <div class="go-soft-modal-content">
        <h3 class="modal-title">Đăng nhập</h3>
        <p class="modal-subtitle">Chọn loại tài khoản bạn muốn sử dụng đăng nhập</p>
        
        <div class="login-methods">
            <button class="login-method-card" data-method="tax">
                <img src="{{ asset('images/d/go-soft/quoc-huy.png') }}" alt="Thuế Điện Tử" class="login-method-logo">
                <p class="login-method-title">Đăng Nhập Bằng Tài Khoản<br>Thuế Điện Tử</p>
            </button>
            
            <button class="login-method-card" data-method="vneid">
                <img src="{{ asset('images/d/go-soft/vneid.png') }}" alt="VNeID" class="login-method-logo">
                <p class="login-method-title">Đăng Nhập Bằng Tài Khoản<br>Định Danh Điện Tử</p>
            </button>
        </div>
    </div>
</div>

<!-- Tax Login Modal -->
<div class="go-soft-modal" id="taxLoginModal">
    <div class="go-soft-modal-content modal-form">
        <button class="modal-close" data-close="taxLoginModal">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff">
                <path d="M18 6L6 18M6 6L18 18" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        
        <h3 class="modal-form-title">Đăng Nhập Thuế Điện Tử</h3>
        
        <form class="tax-login-form" novalidate>
            <div class="form-group">
                <label class="input-label">Tên đăng nhập</label>
                <div class="form-input-wrapper">
                    <input type="text" class="form-input" placeholder="0200769272-ql" value="0200769272-ql">
                </div>
            </div>
            
            <div class="form-group">
                <label class="input-label">Mật khẩu</label>
                <div class="form-input-wrapper">
                    <div class="password-input-wrapper">
                        <input type="password" class="form-input" id="taxPassword" placeholder="••••••••••••" value="nv@2107">
                        <button type="button" class="password-toggle" onclick="toggleTaxPassword()">
                            <img src="{{ asset('/images/svg/eye.svg') }}" alt="Eye" class="eye-icon" id="tax-password-icon">
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Captcha fields - Ẩn đi vì login mới không cần captcha -->
            <div class="form-group" style="display: none;">
                <label class="input-label">Mã capcha</label>
                <div class="form-input-wrapper">
                    <div class="captcha-wrapper">
                        <img class="captcha-img" style="max-width: 150px; height: auto;">
                        <button type="button" class="captcha-refresh" title="Làm mới captcha">
                            <img src="{{ asset('/images/d/go-soft/load.png') }}" alt="Refresh" class="refresh-icon" width="30" height="30">
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="form-group" style="display: none;">
                <label class="input-label">Nhập mã capcha</label>
                <div class="form-input-wrapper">
                    <input type="text" class="form-input captcha-input" placeholder="Nhập mã capcha" autocomplete="off">
                </div>
            </div>
            
            <div class="form-group checkbox-group">
                <div class="form-input-wrapper">
                    <label class="checkbox-label">
                        <input type="checkbox" checked>
                        <span>Chúng tôi đồng ý ủy quyền cho GoSoft hỗ trợ tải hoá đơn của doanh nghiệp mình từ TCT.</span>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-input-wrapper d-flex justify-content-center">
                    <button type="submit" class="btn-submit-login">Đăng nhập</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- VNeID Login Modal -->
<div class="go-soft-modal" id="vneidLoginModal">
    <div class="go-soft-modal-content modal-form">
        <button class="modal-close" data-close="vneidLoginModal">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        
        <h3 class="modal-form-title">Đăng Nhập VNeID</h3>
        
        <div class="vneid-login-content">
            <div class="vneid-form">
                <div class="form-group">
                    <label class="input-label">Tài khoản</label>
                    <div class="input-with-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="input-icon">
                            <path d="M16 17v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                            <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <input type="text" class="form-input" placeholder="Nhập tài khoản">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="input-label">Password</label>
                    <div class="input-with-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="input-icon">
                            <rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M6 8V6a4 4 0 0 1 8 0v2" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <input type="password" class="form-input" placeholder="Nhập mật khẩu">
                        <button type="button" class="password-toggle-vneid">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M1 10s3-6 9-6 9 6 9 6-3 6-9 6-9-6-9-6z" stroke="currentColor" stroke-width="2"/>
                                <circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label vneid-remember">
                        <input type="checkbox">
                        <span>Nhớ mật khẩu</span>
                    </label>
                </div>
                
                <button type="submit" class="btn-submit-login">Đăng nhập</button>
            </div>
            
            <div class="vneid-qr">
                <div class="qr-code-box">
                    <img src="{{ asset('images/d/go-soft/qr-vneid.png') }}" alt="QR Code" class="qr-code-image">
                </div>
                <p class="qr-instruction">Hãy quét mã QR bằng ứng dụng<br>VNeID để đăng nhập</p>
            </div>
        </div>
    </div>
</div>



