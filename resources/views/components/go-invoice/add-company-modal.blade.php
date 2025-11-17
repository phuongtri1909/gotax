<!-- Add Company Modal -->
<div class="go-invoice-modal" id="addCompanyModal">
    <div class="go-invoice-modal-content modal-add-company">
        <button class="modal-close" data-close="addCompanyModal">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        
        <div class="modal-add-company-header">
            <h3 class="modal-add-company-title">Thêm Công Ty Mới</h3>
            <div class="modal-add-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" fill="#227447"/>
                    <path d="M12 8V16M8 12H16" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
        
        <p class="modal-add-company-subtitle">Thêm các công ty mới để chuyển đổi giữa các công ty nhanh chóng.</p>
        
        <form class="add-company-form">
            <div class="form-group">
                <label class="input-label-uppercase">MÃ SỐ THUẾ</label>
                <div class="input-with-validation">
                    <input type="text" class="form-input" id="addCompanyMST" value="0112344733" placeholder="Nhập mã số thuế">
                    <div class="validation-icon valid">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <circle cx="10" cy="10" r="8" fill="#227447"/>
                            <path d="M6 10L9 13L14 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="input-label-uppercase">MẬT KHẨU</label>
                <input type="password" class="form-input" id="addCompanyPassword" placeholder="Nhập mật khẩu">
            </div>
            
            <div class="form-group">
                <label class="input-label-uppercase">TÊN CÔNG TY</label>
                <input type="text" class="form-input" id="addCompanyName" value="CÔNG TY GIÁO DỤC AN HOÀ" placeholder="Nhập tên công ty">
            </div>
            
            <div class="form-group">
                <label class="checkbox-label-remember">
                    <input type="checkbox">
                    <span>Nhớ mật khẩu</span>
                </label>
            </div>
            
            <button type="submit" class="btn-add-company">THÊM</button>
        </form>
    </div>
</div>

