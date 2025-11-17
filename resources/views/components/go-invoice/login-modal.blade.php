<!-- Invoice Login Modal -->
<div class="go-invoice-modal" id="invoiceLoginModal">
    <div class="go-invoice-modal-content modal-form">
        <button class="modal-close" data-close="invoiceLoginModal">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        
        <h3 class="modal-form-title">Đăng Nhập HĐĐT</h3>
        
        <form class="invoice-login-form">
            <div class="form-group">
                <label class="input-label">Tên đăng nhập</label>
                <input type="text" class="form-input" placeholder="MST Doanh Nghiệp" value="">
            </div>
            
            <div class="form-group">
                <label class="input-label">Mật khẩu</label>
                <div class="password-input-wrapper">
                    <input type="password" class="form-input" placeholder="••••••••••••" value="">
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
                    <input type="text" class="form-input captcha-input" placeholder="••••••••••••" readonly>
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
                    <span>Chúng tôi đồng ý ủy quyền cho GOTAX hỗ trợ tải hoá đơn của doanh nghiệp mình từ TCT.</span>
                </label>
            </div>
            
            <button type="submit" class="btn-submit-login">Đăng nhập</button>
        </form>
    </div>
</div>



