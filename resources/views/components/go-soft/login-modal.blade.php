<!-- Login Method Selection Modal -->
<div class="go-soft-modal" id="loginMethodModal">
    <div class="go-soft-modal-content">
        <h3 class="modal-title">Đăng nhập</h3>
        <p class="modal-subtitle">Chọn loại tài khoản bạn muốn sử dụng đăng nhập</p>
        
        <div class="login-methods">
            <button class="login-method-card" data-method="tax">
                <img src="{{ asset('images/d/go-soft/gov-logo.png') }}" alt="Thuế Điện Tử" class="login-method-logo">
                <p class="login-method-title">Đăng Nhập Bằng Tài Khoản<br>Thuế Điện Tử</p>
            </button>
            
            <button class="login-method-card" data-method="vneid">
                <img src="{{ asset('images/d/go-soft/vneid-logo.png') }}" alt="VNeID" class="login-method-logo">
                <p class="login-method-title">Đăng Nhập Bằng Tài Khoản<br>Định Danh Điện Tử</p>
            </button>
        </div>
    </div>
</div>

<!-- Tax Login Modal -->
<div class="go-soft-modal" id="taxLoginModal">
    <div class="go-soft-modal-content modal-form">
        <button class="modal-close" data-close="taxLoginModal">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        
        <h3 class="modal-form-title">Đăng Nhập Thuế Điện Tử</h3>
        
        <form class="tax-login-form">
            <div class="form-group">
                <label class="input-label">Tên đăng nhập</label>
                <input type="text" class="form-input" placeholder="MST Doanh Nghiệp-QL" value="MST Doanh Nghiệp-QL">
            </div>
            
            <div class="form-group">
                <label class="input-label">Mật khẩu</label>
                <div class="password-input-wrapper">
                    <input type="password" class="form-input" placeholder="••••••••••••" value="password123">
                    <button type="button" class="password-toggle">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M1 10s3-6 9-6 9 6 9 6-3 6-9 6-9-6-9-6z" stroke="currentColor" stroke-width="2"/>
                            <circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label class="input-label">Mã capcha</label>
                <div class="captcha-wrapper">
                    <input type="text" class="form-input captcha-input" placeholder="Nhập mã capcha" readonly>
                    <button type="button" class="captcha-refresh">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M1.66666 3.33333V8.33333H6.66666" stroke="#227447" stroke-width="2" stroke-linecap="round"/>
                            <path d="M18.3333 16.6667V11.6667H13.3333" stroke="#227447" stroke-width="2" stroke-linecap="round"/>
                            <path d="M16.5 7.5C16.0395 6.27649 15.263 5.19305 14.2476 4.3598C13.2321 3.52656 12.0132 2.97338 10.7127 2.75598C9.41219 2.53858 8.07841 2.66466 6.8421 3.12241C5.60578 3.58017 4.51001 4.35495 3.66666 5.37499L1.66666 8.33333M18.3333 11.6667L16.3333 14.625C15.49 15.645 14.3942 16.4198 13.1579 16.8776C11.9216 17.3353 10.5878 17.4614 9.28728 17.244C7.98677 17.0266 6.76786 16.4734 5.75244 15.6402C4.73702 14.8069 3.96049 13.7235 3.5 12.5" stroke="#227447" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label class="input-label">Nhập mã capcha</label>
                <input type="text" class="form-input" placeholder="••••••••••••">
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" checked>
                    <span>Chúng tôi đồng ý ủy quyền cho GoSoft hỗ trợ tải hoá đơn của doanh nghiệp mình từ TCT.</span>
                </label>
            </div>
            
            <button type="submit" class="btn-submit-login">Đăng nhập</button>
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



