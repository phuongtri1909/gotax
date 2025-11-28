@extends('client.layouts.app')
@section('title', '')
@section('description', '')
@section('keyword', '')


@section('content')
    <section class="go-quick-upload-section">
        <div class="container">
            <div class="upload-header text-center">
                <h1 class="upload-title mb-0">Go Quick</h1>
                <p class="upload-subtitle">Đọc CCCD Hàng Loạt</p>
            </div>

            <div class="d-flex justify-content-center">
                <div class="upload-tabs">
                    <button class="tab-button active" data-tab="single">Đọc nhanh</button>
                    <button class="tab-button" data-tab="bulk">Hàng loạt</button>
                </div>
            </div>

            <div id="singleUploadTab" class="tab-content">
                <div class="upload-content row">
                    <div class="row g-4 col-10 offset-1 mt-0">
                        <div class="col-12 col-md-6 mt-0">
                            <div class="upload-box for-front" data-upload="front">
                                <div class="upload-box-content">
                                    <div class="upload-icon">
                                        <img src="{{ asset('images/d/go-quick/image-upload.svg') }}" alt="Image Upload">
                                    </div>
                                    <h3 class="upload-label">Mặt trước</h3>
                                    <input type="file" class="upload-input" id="frontUpload" accept="image/*" multiple
                                        hidden>
                                    <label for="frontUpload" class="upload-trigger"></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mt-0">
                            <div class="upload-box for-back" data-upload="back">
                                <div class="upload-box-content">
                                    <div class="upload-icon">
                                        <img src="{{ asset('images/d/go-quick/image-upload.svg') }}" alt="Image Upload">
                                    </div>
                                    <h3 class="upload-label">Mặt sau</h3>
                                    <input type="file" class="upload-input" id="backUpload" accept="image/*" multiple
                                        hidden>
                                    <label for="backUpload" class="upload-trigger"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="bulkUploadTab" class="tab-content d-none">
                <div class="bulk-upload-area" id="bulkUploadArea">
                    <div class="bulk-upload-box" id="bulkUploadBox">
                        <div class="bulk-upload-icon d-flex flex-column gap-1 align-items-center justify-content-center">
                            <img src="{{ asset('images/d/go-quick/img-keo-tha.svg') }}" alt="Image Upload">
                            <img src="{{ asset('images/d/go-quick/img-bong.svg') }}" alt="bong">
                        </div>
                        <h3 class="bulk-upload-title">Kéo và thả tệp của bạn vào đây!</h3>
                        <p class="bulk-upload-subtitle mb-0">Vui lòng tải lên <strong>PDF</strong> files</p>
                        <p class="bulk-upload-limit">Kích thước tối đa của một tập tin là <strong>10 MB</strong></p>

                        <div class="bulk-upload-buttons">
                            <button class="bulk-upload-btn" data-type="excel">
                                <div class="img-excel d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('images/d/go-quick/excel.png') }}" alt="Excel"
                                        class="btn-file-icon">
                                </div>
                                <span class="d-flex align-items-center gap-1">
                                    <img src="{{ asset('images\svg\uploads.svg') }}" alt="uploads">
                                    FILE EXCEL</span>
                            </button>
                            <button class="bulk-upload-btn" data-type="pdf">
                                <div class="img-pdf d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('images/d/go-quick/pdf.png') }}" alt="PDF"
                                        class="btn-file-icon">
                                </div>
                                <span class="d-flex align-items-center gap-1">
                                    <img src="{{ asset('images\svg\uploads.svg') }}" alt="uploads">
                                    FILE PDF</span>
                            </button>
                            <button class="bulk-upload-btn" data-type="folder">
                                <div class="img-folder d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('images/d/go-quick/folder.png') }}" alt="Folder"
                                        class="btn-file-icon">
                                </div>
                                <span class="d-flex align-items-center gap-1">
                                    <img src="{{ asset('images\svg\uploads.svg') }}" alt="uploads">
                                    FOLDER ẢNH</span>
                            </button>
                        </div>

                        <input type="file" id="bulkFileInput" accept=".pdf,.xlsx,.xls,.zip" hidden>
                        <input type="file" id="bulkFolderInput" accept="image/*" multiple hidden>
                    </div>

                    <div class="bulk-progress-section d-none" id="bulkProgressSection">
                        <div class="bulk-file-card">
                            <div class="bulk-file-info">
                                <img src="{{ asset('images/d/go-quick/pdf.png') }}" alt="PDF" class="bulk-file-thumb">
                                <div class="w-100">
                                    <div class="bulk-file-details">
                                        <p class="bulk-file-name" id="bulkFileName">file-cccd.pdf</p>
                                        <p class="bulk-file-size" id="bulkFileSize">34 KB</p>
                                    </div>
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <div class="bulk-progress-bar flex-grow-1">
                                            <div class="bulk-progress-fill" id="bulkProgressFill" style="width: 0%">
                                            </div>
                                        </div>
                                        <div class="bulk-progress-info">
                                            <span class="bulk-progress-percent" id="bulkProgressPercent">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button class="btn-cancel" id="bulkCancelBtn">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>

                <div class="upload-back">
                    <a href="{{ route('tools.go-quick') }}" class="btn btn-back-link">
                        <img src="{{ asset('images/svg/arrow-left.svg') }}" alt="">
                        <span>Trở lại</span>
                    </a>
                </div>
            </div>

            <div id="uploadSection">
                <div class="upload-info text-center">
                    <h3 class="info-title" id="infoTitle">Tải lên ảnh CCCD ở đây!</h3>
                    <p class="info-description" id="infoDescription">Vui lòng tải ảnh rõ nét</p>
                    <p class="info-limit">Kích thước tối đa của một tập tin là <strong>5 MB</strong></p>
                </div>

                <div class="upload-actions text-center">
                    <button type="button" class="btn btn-upload" id="uploadButton">
                        <svg class="btn-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17 13V15C17 15.5304 16.7893 16.0391 16.4142 16.4142C16.0391 16.7893 15.5304 17 15 17H5C4.46957 17 3.96086 16.7893 3.58579 16.4142C3.21071 16.0391 3 15.5304 3 15V13"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M13 6L10 3L7 6" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10 3V13" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <span class="btn-text">PHOTO CCCD</span>
                    </button>
                </div>

                <div class="upload-back">
                    <a href="{{ route('tools.go-quick') }}" class="btn btn-back-link">
                        <img src="{{ asset('images/svg/arrow-left.svg') }}" alt="">
                        <span>Trở lại</span>
                    </a>
                </div>
            </div>

            <div id="bulkDownloadSection" class="d-none">
                <h2 class="download-title text-center">
                    File Của Bạn đã Trích Xuất thành công
                    <img src="{{ asset('images/d/go-quick/check-success.png') }}" alt="Success">
                </h2>

                <div class="download-content">
                    <h3 class="download-section-title">Tải xuống file</h3>
                    <div class="download-box">
                        <div class="download-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"
                                fill="none">
                                <rect width="100" height="100" rx="50" fill="#EB5757" />
                                <path
                                    d="M36.1808 42.1664H63.7011C65.5365 42.1715 67.3159 42.7296 68.75 43.75V42.4198C68.75 40.947 68.0932 39.5346 66.9241 38.4932C65.7549 37.4517 64.1693 36.8667 62.5159 36.8667H53.295L52.181 34.1429C51.8645 33.2933 51.2531 32.5553 50.4351 32.0354C49.617 31.5154 48.6347 31.2405 47.6298 31.2503H37.4845C36.6598 31.2502 35.8433 31.3959 35.0823 31.6789C34.3213 31.962 33.6308 32.3767 33.051 32.899C32.4712 33.4214 32.0135 34.0411 31.7044 34.7222C31.3953 35.4032 31.241 36.1321 31.2504 36.8667V43.6444C32.6695 42.6869 34.4006 42.1679 36.1808 42.1664Z"
                                    fill="url(#paint0_linear_1_54621)" />
                                <path
                                    d="M63.7011 46.8779H36.1808C35.0865 46.8504 34.0127 47.1661 33.1227 47.7769C32.2328 48.3878 31.5754 49.2604 31.2504 50.2621V62.7085C31.241 63.4986 31.3953 64.2827 31.7044 65.0153C32.0135 65.7479 32.4712 66.4145 33.051 66.9764C33.6308 67.5383 34.3213 67.9844 35.0823 68.2889C35.8433 68.5933 36.6598 68.75 37.4845 68.75H62.5159C64.1693 68.75 65.7549 68.1207 66.9241 67.0004C68.0932 65.8802 68.75 64.3609 68.75 62.7766V51.057C68.6133 49.8788 68.0171 48.7936 67.0809 48.0187C66.1447 47.2437 64.9375 46.8363 63.7011 46.8779Z"
                                    fill="url(#paint1_linear_1_54621)" />
                                <defs>
                                    <linearGradient id="paint0_linear_1_54621" x1="50" y1="31.25"
                                        x2="54.8691" y2="86.9938" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="white" />
                                        <stop offset="1" stop-color="#A8A7AD" />
                                    </linearGradient>
                                    <linearGradient id="paint1_linear_1_54621" x1="50" y1="46.875"
                                        x2="64.6822" y2="142.927" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="white" />
                                        <stop offset="1" stop-color="#A8A7AD" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <button class="btn-download-file" id="downloadFileBtn">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path
                                    d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5.83334 8.33333L10 12.5L14.1667 8.33333" stroke="white" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M10 12.5V2.5" stroke="white" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span>File đọc CCCD</span>
                        </button>
                    </div>

                </div>
                <button class="btn-reload-download" id="reloadDownloadBtn">
                    <span>Lấy lại dữ liệu</span>
                    <img src="{{ asset('images/svg/exchange.png') }}" alt="">
                </button>

                <div class="promo-banner" id="promoBanner">
                    <button class="promo-banner-close" id="promoBannerClose">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="promo-banner-content">
                        <div class="promo-banner-left">
                            <span class="promo-badge">Nổi bật</span>
                            <h3 class="promo-title">Tối Ưu Hoá Thời Gian Với Công Cụ Tải Hoá Đơn Điện Tử Hàng Loạt</h3>
                            <p class="promo-description">Tự động hoá toàn bộ quy trình tải hoá đơn điện giúp bạn tiết kiệm thời gian và nâng cao hiệu quả công việc. Hỗ trợ xử lý hàng loạt với độ chính xác cao.</p>
                            <button class="promo-btn-more">Xem thêm →</button>
                        </div>
                        <div class="promo-banner-right">
                            <div class="promo-screenshots">
                                <img src="{{ asset('images/d/go-quick/ads.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="resultSection" class="d-none">
                <h2 class="result-title text-center">Kết quả trích Xuất</h2>
                <div class="row g-4 result-row-equal">
                    <div class="col-12 col-md-9">
                        <div id="resultFields"></div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="result-images-container">
                            <div class="upload-box for-front result-upload-box" data-upload="front">
                                <div class="upload-box-content">
                                    <div class="upload-icon">
                                        <img src="{{ asset('images/d/go-quick/image-upload.svg') }}" alt="Image Upload">
                                    </div>
                                    <h3 class="upload-label">Mặt trước</h3>
                                </div>
                                <img id="resultFrontImg" src="" alt="Mặt trước" style="display: none;">
                                <div class="result-image-placeholder" id="resultFrontPlaceholder">
                                    <img src="{{ asset('images/d/go-quick/image-upload.svg') }}" alt="Image Upload"
                                        class="placeholder-icon">
                                    <p class="placeholder-text">Mặt trước</p>
                                </div>
                            </div>
                            <div class="upload-box for-back result-upload-box" data-upload="back">
                                <div class="upload-box-content">
                                    <div class="upload-icon">
                                        <img src="{{ asset('images/d/go-quick/image-upload.svg') }}" alt="Image Upload">
                                    </div>
                                    <h3 class="upload-label">Mặt sau</h3>
                                </div>
                                <img id="resultBackImg" src="" alt="Mặt sau" style="display: none;">
                                <div class="result-image-placeholder" id="resultBackPlaceholder">
                                    <img src="{{ asset('images/d/go-quick/image-upload.svg') }}" alt="Image Upload"
                                        class="placeholder-icon">
                                    <p class="placeholder-text">Mặt sau</p>
                                </div>
                            </div>
                            <button class="btn-change-image" id="changeImageBtn">
                                <img src="{{ asset('images/svg/uploads.svg') }}" alt="Upload">
                                <span>Thay ảnh khác</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="result-actions text-center">
                    <button class="btn-reload" id="reloadButton">
                        <span>Lấy lại dữ liệu</span>
                        <img src="{{ asset('images/svg/exchange.png') }}" alt="">
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="bulk-modal" id="bulkSuccessModal">
        <div class="bulk-modal-content">
            <div class="bulk-modal-icon success">
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="36" viewBox="0 0 50 36"
                    fill="none">
                    <path
                        d="M17.8875 35.8375C16.8875 35.8375 15.9375 35.4375 15.2375 34.7375L1.0875 20.5875C-0.3625 19.1375 -0.3625 16.7375 1.0875 15.2875C2.5375 13.8375 4.9375 13.8375 6.3875 15.2875L17.8875 26.7875L43.5875 1.0875C45.0375 -0.3625 47.4375 -0.3625 48.8875 1.0875C50.3375 2.5375 50.3375 4.9375 48.8875 6.3875L20.5375 34.7375C19.8375 35.4375 18.8875 35.8375 17.8875 35.8375Z"
                        fill="#227447" />
                </svg>
            </div>
            <h3 class="bulk-modal-title success">Thành công</h3>
            <p class="bulk-modal-message">File của bạn đã trích xuất dữ liệu thành công!</p>
        </div>
    </div>

    <div class="bulk-modal" id="bulkFailedModal">
        <div class="bulk-modal-content">
            <div class="bulk-modal-icon failed">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120"
                    fill="none">
                    <path
                        d="M45.8508 77.9008C44.9008 77.9008 43.9508 77.5508 43.2008 76.8008C41.7508 75.3508 41.7508 72.9508 43.2008 71.5008L71.5008 43.2008C72.9508 41.7508 75.3508 41.7508 76.8008 43.2008C78.2508 44.6508 78.2508 47.0508 76.8008 48.5008L48.5008 76.8008C47.8008 77.5508 46.8008 77.9008 45.8508 77.9008Z"
                        fill="#EB5757" />
                    <path
                        d="M74.1508 77.9008C73.2008 77.9008 72.2508 77.5508 71.5008 76.8008L43.2008 48.5008C41.7508 47.0508 41.7508 44.6508 43.2008 43.2008C44.6508 41.7508 47.0508 41.7508 48.5008 43.2008L76.8008 71.5008C78.2508 72.9508 78.2508 75.3508 76.8008 76.8008C76.0508 77.5508 75.1008 77.9008 74.1508 77.9008Z"
                        fill="#EB5757" />
                </svg>
            </div>
            <h3 class="bulk-modal-title failed">Oops</h3>
            <p class="bulk-modal-message">Trích xuất dữ liệu thất bại!<br>Vui lòng kiểm tra lại file</p>
        </div>
    </div>
@endsection



@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-quick-upload.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const singleUploadTab = document.getElementById('singleUploadTab');
            const bulkUploadTab = document.getElementById('bulkUploadTab');
            const uploadSection = document.getElementById('uploadSection');

            const savedTab = localStorage.getItem('goQuickActiveTab') || 'single';
            switchTab(savedTab);

            function switchTab(tabName) {
                tabButtons.forEach(btn => {
                    if (btn.dataset.tab === tabName) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });

                if (tabName === 'single') {
                    singleUploadTab.classList.remove('d-none');
                    bulkUploadTab.classList.add('d-none');
                    uploadSection.classList.remove('d-none');
                } else if (tabName === 'bulk') {
                    singleUploadTab.classList.add('d-none');
                    bulkUploadTab.classList.remove('d-none');
                    uploadSection.classList.add('d-none');
                }

                localStorage.setItem('goQuickActiveTab', tabName);
            }

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tab = this.dataset.tab;
                    switchTab(tab);
                });
            });

            const uploadInputs = document.querySelectorAll('.upload-input');
            const uploadBoxes = document.querySelectorAll('.upload-box');

            uploadInputs.forEach((input, index) => {
                input.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    const uploadBox = this.closest('.upload-box');

                    if (files.length > 0) {
                        handleFileUpload(files, uploadBox);
                    } else {
                        updateUploadButton();
                    }
                });
            });

            uploadBoxes.forEach(box => {
                box.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('dragover');
                });

                box.addEventListener('dragleave', function() {
                    this.classList.remove('dragover');
                });

                box.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');

                    const files = Array.from(e.dataTransfer.files);
                    if (files.length > 0) {
                        handleFileUpload(files, this);
                    }
                });
            });

            function clearErrorState() {
                uploadBoxes.forEach(box => {
                    box.classList.remove('has-error');
                });
                const infoTitle = document.getElementById('infoTitle');
                const infoDescription = document.getElementById('infoDescription');
                if (infoTitle) {
                    infoTitle.classList.remove('error');
                    infoTitle.textContent = 'Tải lên ảnh CCCD ở đây!';
                }
                if (infoDescription) {
                    infoDescription.textContent = 'Vui lòng tải ảnh rõ nét';
                }
                updateUploadButton();
            }

            function showErrorState() {
                uploadBoxes.forEach(box => {
                    const uploadType = box.dataset.upload;
                    const hasImage = (uploadType === 'front' && uploadedFrontImage) ||
                        (uploadType === 'back' && uploadedBackImage);

                    if (!hasImage) {
                        box.classList.remove('has-file');
                        box.classList.add('has-error');
                        box.style.backgroundImage = '';
                    }
                });
                const infoTitle = document.getElementById('infoTitle');
                const infoDescription = document.getElementById('infoDescription');
                if (infoTitle) {
                    infoTitle.classList.add('error');
                    infoTitle.textContent = 'Tệp không đọc được!';
                }
                if (infoDescription) {
                    infoDescription.textContent = 'Vui lòng tải ảnh rõ nét';
                }
            }

            function updateUploadButton() {
                const frontInput = document.getElementById('frontUpload');
                const backInput = document.getElementById('backUpload');
                const uploadButton = document.getElementById('uploadButton');
                const buttonText = uploadButton.querySelector('.btn-text');
                const buttonIcon = uploadButton.querySelector('.btn-icon');

                const hasFiles = (frontInput.files.length > 0) || (backInput.files.length > 0);

                if (hasFiles && buttonText) {
                    buttonText.textContent = 'TRÍCH XUẤT';
                    const extractIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    extractIcon.setAttribute('class', 'btn-icon');
                    extractIcon.setAttribute('width', '20');
                    extractIcon.setAttribute('height', '20');
                    extractIcon.setAttribute('viewBox', '0 0 20 20');
                    extractIcon.setAttribute('fill', 'none');
                    extractIcon.innerHTML =
                        '<path d="M12.892 7.73299L8.625 12L7.17049 10.5455M9.75 0.75C4.77944 0.75 0.75 4.77944 0.75 9.75C0.75 14.7206 4.77944 18.75 9.75 18.75C14.7206 18.75 18.75 14.7206 18.75 9.75C18.75 4.77944 14.7206 0.75 9.75 0.75Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';
                    if (buttonIcon) {
                        buttonIcon.replaceWith(extractIcon);
                    } else {
                        uploadButton.insertBefore(extractIcon, buttonText);
                    }
                } else if (buttonText) {
                    buttonText.textContent = 'PHOTO CCCD';
                    const uploadIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    uploadIcon.setAttribute('class', 'btn-icon');
                    uploadIcon.setAttribute('width', '20');
                    uploadIcon.setAttribute('height', '20');
                    uploadIcon.setAttribute('viewBox', '0 0 20 20');
                    uploadIcon.setAttribute('fill', 'none');
                    uploadIcon.innerHTML =
                        '<path d="M17 13V15C17 15.5304 16.7893 16.0391 16.4142 16.4142C16.0391 16.7893 15.5304 17 15 17H5C4.46957 17 3.96086 16.7893 3.58579 16.4142C3.21071 16.0391 3 15.5304 3 15V13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M13 6L10 3L7 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 3V13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                    if (buttonIcon) {
                        buttonIcon.replaceWith(uploadIcon);
                    } else {
                        uploadButton.insertBefore(uploadIcon, buttonText);
                    }
                }
            }

            let uploadedFrontImage = null;
            let uploadedBackImage = null;

            function handleFileUpload(files, uploadBox) {
                clearErrorState();

                const maxSize = 5 * 1024 * 1024;
                let hasError = false;

                for (let file of files) {
                    if (file.size > maxSize) {
                        hasError = true;
                        break;
                    }
                }

                if (hasError) {
                    showErrorState();
                    return;
                }

                if (files.length > 0) {
                    uploadBox.classList.remove('has-error');
                    uploadBox.classList.add('has-file');

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        uploadBox.style.backgroundImage = `url(${e.target.result})`;
                        uploadBox.style.backgroundSize = 'contain';
                        uploadBox.style.backgroundPosition = 'center';
                        uploadBox.style.backgroundRepeat = 'no-repeat';

                        const uploadType = uploadBox.dataset.upload;
                        if (uploadType === 'front') {
                            uploadedFrontImage = e.target.result;
                        } else if (uploadType === 'back') {
                            uploadedBackImage = e.target.result;
                        }
                    };
                    reader.readAsDataURL(files[0]);

                    updateUploadButton();

                    console.log('Files uploaded:', files);
                }
            }

            function showResultSectionWithData(data) {
                document.getElementById('uploadSection').classList.add('d-none');
                document.querySelector('.upload-content').classList.add('d-none');

                const resultSection = document.getElementById('resultSection');
                resultSection.classList.remove('d-none');

                const customers = data.customer || [];
                if (customers.length === 0) {
                    showBulkFailed('Không tìm thấy dữ liệu CCCD trong ảnh. Vui lòng kiểm tra lại ảnh.', false);
                    resetUploadButton();
                    return;
                }

                const customer = customers[0];

                const resultFields = document.getElementById('resultFields');
                resultFields.innerHTML = `
                    <div class="result-field-group">
                        <div class="result-field">
                            <label class="result-label">Số</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="${customer.id_card || ''}" readonly>
                                <button class="result-copy-btn" data-value="${customer.id_card || ''}">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Họ và tên</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="${customer.name || ''}" readonly>
                                <button class="result-copy-btn" data-value="${customer.name || ''}">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Ngày sinh</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="${customer.birth_date || ''}" readonly>
                                <button class="result-copy-btn" data-value="${customer.birth_date || ''}">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <div class="result-field">
                                <label class="result-label">Giới tính</label>
                                <div class="result-input-wrapper">
                                    <input type="text" class="result-input" value="${customer.gender || ''}" readonly>
                                    <button class="result-copy-btn" data-value="${customer.gender || ''}">
                                        <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                    </button>
                                </div>
                            </div>
                            <div class="result-field">
                                <label class="result-label">Quốc tịch</label>
                                <div class="result-input-wrapper">
                                    <input type="text" class="result-input" value="${customer.nationality || 'Việt Nam'}" readonly>
                                    <button class="result-copy-btn" data-value="${customer.nationality || 'Việt Nam'}">
                                        <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Quê quán</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="${customer.hometown || ''}" readonly>
                                <button class="result-copy-btn" data-value="${customer.hometown || ''}">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Nơi thường trú</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="${customer.address || ''}" readonly>
                                <button class="result-copy-btn" data-value="${customer.address || ''}">   
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Ngày cấp</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="${customer.created_date || ''}" readonly>
                                <button class="result-copy-btn" data-value="${customer.created_date || ''}">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Nơi cấp</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="${customer.place_created || ''}" readonly>
                                <button class="result-copy-btn" data-value="${customer.place_created || ''}">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.querySelectorAll('.result-copy-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const value = this.dataset.value;
                        navigator.clipboard.writeText(value).then(() => {
                            console.log('Copied:', value);
                        });
                    });
                });

                const frontImg = document.getElementById('resultFrontImg');
                const backImg = document.getElementById('resultBackImg');
                const frontPlaceholder = document.getElementById('resultFrontPlaceholder');
                const backPlaceholder = document.getElementById('resultBackPlaceholder');
                const frontBox = document.querySelector('.result-upload-box[data-upload="front"]');
                const backBox = document.querySelector('.result-upload-box[data-upload="back"]');

                if (uploadedFrontImage) {
                    frontImg.src = uploadedFrontImage;
                    frontImg.style.display = 'block';
                    if (frontPlaceholder) frontPlaceholder.style.display = 'none';
                    if (frontBox) frontBox.classList.add('has-file');
                } else {
                    frontImg.style.display = 'none';
                    if (frontPlaceholder) frontPlaceholder.style.display = 'flex';
                    if (frontBox) frontBox.classList.remove('has-file');
                }

                if (uploadedBackImage) {
                    backImg.src = uploadedBackImage;
                    backImg.style.display = 'block';
                    if (backPlaceholder) backPlaceholder.style.display = 'none';
                    if (backBox) backBox.classList.add('has-file');
                } else {
                    backImg.style.display = 'none';
                    if (backPlaceholder) backPlaceholder.style.display = 'flex';
                    if (backBox) backBox.classList.remove('has-file');
                }
            }

            function showResultSection() {
                document.getElementById('uploadSection').classList.add('d-none');
                document.querySelector('.upload-content').classList.add('d-none');

                const resultSection = document.getElementById('resultSection');
                resultSection.classList.remove('d-none');

                const resultFields = document.getElementById('resultFields');
                resultFields.innerHTML = `
                    <div class="result-field-group">
                        <div class="result-field">
                            <label class="result-label">Số</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="035203067525" readonly>
                                <button class="result-copy-btn" data-value="035203067525">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Họ và tên</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="NGUYỄN VIỆT TRƯỜNG" readonly>
                                <button class="result-copy-btn" data-value="NGUYỄN VIỆT TRƯỜNG">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Ngày sinh</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="24/10/1999" readonly>
                                <button class="result-copy-btn" data-value="24/10/1999">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <div class="result-field">
                                <label class="result-label">Giới tính</label>
                                <div class="result-input-wrapper">
                                    <input type="text" class="result-input" value="Nam" readonly>
                                    <button class="result-copy-btn" data-value="Nam">
                                        <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                    </button>
                                </div>
                            </div>
                            <div class="result-field">
                                <label class="result-label">Quốc tịch</label>
                                <div class="result-input-wrapper">
                                    <input type="text" class="result-input" value="Việt Nam" readonly>
                                    <button class="result-copy-btn" data-value="Việt Nam">
                                        <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Quê quán</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="Thạnh Phong, Thạnh Liêm, Hà Nam" readonly>
                                <button class="result-copy-btn" data-value="Thạnh Phong, Thạnh Liêm, Hà Nam">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Nơi thường trú</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="Thạnh Phong, Thạnh Liêm, Hà Nam" readonly>
                                <button class="result-copy-btn" data-value="Thạnh Phong, Thạnh Liêm, Hà Nam">   
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Ngày cấp</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="05/04/2021" readonly>
                                <button class="result-copy-btn" data-value="05/04/2021">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                        <div class="result-field">
                            <label class="result-label">Nơi cấp</label>
                            <div class="result-input-wrapper">
                                <input type="text" class="result-input" value="CỤC TRƯỞNG CỤC CẢNH SÁT QUẢN LÝ HÀNH CHÍNH VỀ TRẬT TỰ XÃ HỘI" readonly>
                                <button class="result-copy-btn" data-value="CỤC TRƯỞNG CỤC CẢNH SÁT QUẢN LÝ HÀNH CHÍNH VỀ TRẬT TỰ XÃ HỘI">
                                    <img src="{{ asset('images/svg/copy.svg') }}" alt="Copy">
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.querySelectorAll('.result-copy-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const value = this.dataset.value;
                        navigator.clipboard.writeText(value).then(() => {
                            console.log('Copied:', value);
                        });
                    });
                });

                const frontImg = document.getElementById('resultFrontImg');
                const backImg = document.getElementById('resultBackImg');
                const frontPlaceholder = document.getElementById('resultFrontPlaceholder');
                const backPlaceholder = document.getElementById('resultBackPlaceholder');
                const frontBox = document.querySelector('.result-upload-box[data-upload="front"]');
                const backBox = document.querySelector('.result-upload-box[data-upload="back"]');

                if (uploadedFrontImage) {
                    frontImg.src = uploadedFrontImage;
                    frontImg.style.display = 'block';
                    if (frontPlaceholder) frontPlaceholder.style.display = 'none';
                    if (frontBox) frontBox.classList.add('has-file');
                } else {
                    frontImg.style.display = 'none';
                    if (frontPlaceholder) frontPlaceholder.style.display = 'flex';
                    if (frontBox) frontBox.classList.remove('has-file');
                }

                if (uploadedBackImage) {
                    backImg.src = uploadedBackImage;
                    backImg.style.display = 'block';
                    if (backPlaceholder) backPlaceholder.style.display = 'none';
                    if (backBox) backBox.classList.add('has-file');
                } else {
                    backImg.style.display = 'none';
                    if (backPlaceholder) backPlaceholder.style.display = 'flex';
                    if (backBox) backBox.classList.remove('has-file');
                }
            }

            document.getElementById('uploadButton').addEventListener('click', async function() {
                const frontInput = document.getElementById('frontUpload');
                const backInput = document.getElementById('backUpload');
                const uploadButton = this;
                const buttonText = uploadButton.querySelector('.btn-text');

                if (buttonText && buttonText.textContent === 'TRÍCH XUẤT') {
                    if (!frontInput.files[0] || !backInput.files[0]) {
                        showErrorState();
                        return;
                    }

                    uploadButton.disabled = true;
                    uploadButton.classList.add('processing');

                    uploadButton.innerHTML = '';

                    const spinnerIcon = document.createElement('span');
                    spinnerIcon.className = 'spinner-border spinner-border-sm processing-rotate';

                    const processingText = document.createElement('span');
                    processingText.className = 'processing-text';
                    processingText.textContent = 'ĐANG XỬ LÝ...';

                    uploadButton.appendChild(spinnerIcon);
                    uploadButton.appendChild(processingText);

                    try {
                        const formData = new FormData();
                        formData.append('mt', frontInput.files[0]);
                        formData.append('ms', backInput.files[0]);

                        const response = await fetch('{{ route("tools.go-quick.process-cccd-images") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        if (response.status === 401 || response.status === 403) {
                            throw new Error('Bạn cần đăng nhập để sử dụng tính năng này. Vui lòng đăng nhập và thử lại.');
                        }

                        if (response.status === 302 || response.redirected) {
                            throw new Error('Bạn cần đăng nhập để sử dụng tính năng này. Vui lòng đăng nhập và thử lại.');
                        }

                        if (!response.ok) {
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                const errorResult = await response.json();
                                throw new Error(errorResult.message || `HTTP error! status: ${response.status}`);
                            } else {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                        }

                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Non-JSON response:', text);
                            if (text.includes('<!DOCTYPE') || text.includes('<html') || text.includes('login')) {
                                throw new Error('Bạn cần đăng nhập để sử dụng tính năng này. Vui lòng đăng nhập và thử lại.');
                            }
                            throw new Error('Server trả về dữ liệu không hợp lệ. Vui lòng thử lại.');
                        }

                        const result = await response.json();

                        if (result.status === 'success') {
                            const data = result.data || result;
                            showResultSectionWithData(data);
                        } else {
                            showErrorState();
                            showBulkFailed(result.message || 'Có lỗi xảy ra khi xử lý', false);
                            resetUploadButton();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showErrorState();
                        showBulkFailed(error.message || 'Có lỗi xảy ra khi kết nối server. Vui lòng thử lại sau.', false);
                        resetUploadButton();
                    }
                    return;
                }
            });

            function resetUploadButton() {
                const uploadButton = document.getElementById('uploadButton');
                uploadButton.disabled = false;
                uploadButton.classList.remove('processing');
                uploadButton.innerHTML = '';
                
                const extractIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                extractIcon.setAttribute('class', 'btn-icon');
                extractIcon.setAttribute('width', '20');
                extractIcon.setAttribute('height', '20');
                extractIcon.setAttribute('viewBox', '0 0 20 20');
                extractIcon.setAttribute('fill', 'none');
                extractIcon.innerHTML =
                    '<path d="M12.892 7.73299L8.625 12L7.17049 10.5455M9.75 0.75C4.77944 0.75 0.75 4.77944 0.75 9.75C0.75 14.7206 4.77944 18.75 9.75 18.75C14.7206 18.75 18.75 14.7206 18.75 9.75C18.75 4.77944 14.7206 0.75 9.75 0.75Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';
                
                const buttonText = document.createElement('span');
                buttonText.className = 'btn-text';
                buttonText.textContent = 'TRÍCH XUẤT';
                
                uploadButton.appendChild(extractIcon);
                uploadButton.appendChild(buttonText);
                
                const frontInput = document.getElementById('frontUpload');
                const backInput = document.getElementById('backUpload');
                if (frontInput) frontInput.value = '';
                if (backInput) backInput.value = '';
            }

            document.getElementById('reloadButton').addEventListener('click', function() {
                location.reload();
            });

            document.getElementById('changeImageBtn').addEventListener('click', function() {
                const frontInput = document.getElementById('frontUpload');
                const backInput = document.getElementById('backUpload');
                if (frontInput) frontInput.value = '';
                if (backInput) backInput.value = '';
                
                uploadedFrontImage = null;
                uploadedBackImage = null;
                
                clearErrorState();
                
                document.getElementById('uploadSection').classList.remove('d-none');
                document.querySelector('.upload-content').classList.remove('d-none');

                document.getElementById('resultSection').classList.add('d-none');

                const uploadButton = document.getElementById('uploadButton');
                uploadButton.disabled = false;
                uploadButton.classList.remove('processing');

                uploadButton.innerHTML = '';

                const hasFiles = uploadedFrontImage && uploadedBackImage;

                if (hasFiles) {
                    const extractIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    extractIcon.setAttribute('class', 'btn-icon');
                    extractIcon.setAttribute('width', '20');
                    extractIcon.setAttribute('height', '20');
                    extractIcon.setAttribute('viewBox', '0 0 20 20');
                    extractIcon.setAttribute('fill', 'none');
                    extractIcon.innerHTML =
                        '<path d="M12.892 7.73299L8.625 12L7.17049 10.5455M9.75 0.75C4.77944 0.75 0.75 4.77944 0.75 9.75C0.75 14.7206 4.77944 18.75 9.75 18.75C14.7206 18.75 18.75 14.7206 18.75 9.75C18.75 4.77944 14.7206 0.75 9.75 0.75Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';

                    const buttonText = document.createElement('span');
                    buttonText.className = 'btn-text';
                    buttonText.textContent = 'TRÍCH XUẤT';

                    uploadButton.appendChild(extractIcon);
                    uploadButton.appendChild(buttonText);
                } else {
                    const uploadIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    uploadIcon.setAttribute('class', 'btn-icon');
                    uploadIcon.setAttribute('width', '20');
                    uploadIcon.setAttribute('height', '20');
                    uploadIcon.setAttribute('viewBox', '0 0 20 20');
                    uploadIcon.setAttribute('fill', 'none');
                    uploadIcon.innerHTML =
                        '<path d="M17 13V15C17 15.5304 16.7893 16.0391 16.4142 16.4142C16.0391 16.7893 15.5304 17 15 17H5C4.46957 17 3.96086 16.7893 3.58579 16.4142C3.21071 16.0391 3 15.5304 3 15V13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M13 6L10 3L7 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 3V13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';

                    const buttonText = document.createElement('span');
                    buttonText.className = 'btn-text';
                    buttonText.textContent = 'PHOTO CCCD';

                    uploadButton.appendChild(uploadIcon);
                    uploadButton.appendChild(buttonText);
                }
            });

            const bulkUploadBox = document.getElementById('bulkUploadBox');
            const bulkUploadArea = document.getElementById('bulkUploadArea');
            const bulkFileInput = document.getElementById('bulkFileInput');
            const bulkFolderInput = document.getElementById('bulkFolderInput');
            const bulkProgressSection = document.getElementById('bulkProgressSection');
            const bulkSuccessModal = document.getElementById('bulkSuccessModal');
            const bulkFailedModal = document.getElementById('bulkFailedModal');
            const bulkDownloadSection = document.getElementById('bulkDownloadSection');

            let currentBulkFile = null;
            let uploadProgress = 0;
            let uploadInterval = null;
            let currentAbortController = null;

            document.querySelectorAll('.bulk-upload-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const type = this.dataset.type;
                    if (type === 'folder') {
                        bulkFolderInput.click();
                    } else {
                        bulkFileInput.click();
                    }
                });
            });

            bulkFileInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                if (files.length > 0) {
                    handleBulkFileUpload(files[0]);
                }
            });

            bulkFolderInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                if (files.length > 0) {
                    const imageFiles = files.filter(f => f.type.startsWith('image/'));
                    const nonImageFiles = files.filter(f => !f.type.startsWith('image/'));
                    
                    if (nonImageFiles.length > 0) {
                        showBulkError(nonImageFiles[0], 'Folder chỉ chấp nhận file ảnh. Vui lòng chọn file ảnh hoặc dùng nút FILE EXCEL/PDF để upload file Excel/PDF.');
                        return;
                    }
                    
                    if (imageFiles.length > 0) {
                        handleMultipleImagesUpload(imageFiles);
                    } else {
                        showBulkError(files[0], 'Vui lòng chọn file ảnh hoặc dùng nút FILE EXCEL/PDF để upload file Excel/PDF.');
                    }
                }
            });

            bulkUploadBox.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            bulkUploadBox.addEventListener('dragleave', function() {
                this.classList.remove('dragover');
            });

            bulkUploadBox.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');

                const files = Array.from(e.dataTransfer.files);
                if (files.length > 0) {
                    handleBulkFileUpload(files[0]);
                }
            });

            function handleBulkFileUpload(file) {
                const maxSize = 10 * 1024 * 1024;
                const allowedTypes = ['application/pdf', 'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/zip', 'application/x-zip-compressed'
                ];

                bulkUploadBox.classList.remove('has-error');
                const existingError = bulkUploadBox.querySelector('.bulk-error-file');
                if (existingError) existingError.remove();

                if (file.size > maxSize) {
                    showBulkError(file, 'File vượt quá 10MB');
                    return;
                }

                const fileName = file.name.toLowerCase();
                const isPDF = fileName.endsWith('.pdf') || file.type === 'application/pdf';
                const isExcel = fileName.endsWith('.xlsx') || fileName.endsWith('.xls') || 
                               file.type.includes('spreadsheet') || file.type.includes('excel');
                const isZip = fileName.endsWith('.zip') || file.type === 'application/zip' || 
                             file.type === 'application/x-zip-compressed';
                const isImage = file.type.startsWith('image/');

                if (!isPDF && !isExcel && !isZip && !isImage) {
                    showBulkError(file, 'Loại tệp tin không đúng! Vui lòng chọn PDF, Excel, ZIP hoặc ảnh');
                    return;
                }

                currentBulkFile = file;
                startBulkUpload(file);
            }

            function showBulkError(file, message) {
                bulkUploadBox.classList.add('has-error');

                const title = bulkUploadBox.querySelector('.bulk-upload-title');
                const subtitle = bulkUploadBox.querySelector('.bulk-upload-subtitle');
                if (title) title.textContent = message;
                if (subtitle) subtitle.textContent = 'Vui lòng tải lên PDF files';

                const uploadIcon = bulkUploadBox.querySelector('.bulk-upload-icon');

                const existingError = bulkUploadBox.querySelector('.bulk-error-file');
                if (existingError) existingError.remove();

                if (uploadIcon && !bulkUploadBox.dataset.iconHtml) {
                    bulkUploadBox.dataset.iconHtml = uploadIcon.outerHTML;
                }

                const errorFile = document.createElement('div');
                errorFile.className = 'bulk-error-file';
                errorFile.innerHTML = `
                    <p class="bulk-error-file-name">${file.name}</p>
                    <p class="bulk-error-file-size">${(file.size / 1024).toFixed(2)} KB</p>
                `;

                if (uploadIcon) {
                    bulkUploadBox.replaceChild(errorFile, uploadIcon);
                } else if (title) {
                    bulkUploadBox.insertBefore(errorFile, title);
                } else {
                    bulkUploadBox.insertBefore(errorFile, bulkUploadBox.firstChild);
                }
            }

            function getFileIcon(file) {
                const fileName = file.name.toLowerCase();
                const fileType = file.type;

                if (fileName.endsWith('.xlsx') || fileName.endsWith('.xls') ||
                    fileType.includes('spreadsheet') || fileType.includes('excel')) {
                    const excelBtn = document.querySelector('.bulk-upload-btn[data-type="excel"]');
                    if (excelBtn) {
                        const excelIcon = excelBtn.querySelector('.btn-file-icon');
                        if (excelIcon) {
                            return excelIcon.src;
                        }
                    }
                    return '{{ asset('images/d/go-quick/excel.png') }}';
                }

                if (fileName.endsWith('.pdf') || fileType === 'application/pdf') {
                    const pdfBtn = document.querySelector('.bulk-upload-btn[data-type="pdf"]');
                    if (pdfBtn) {
                        const pdfIcon = pdfBtn.querySelector('.btn-file-icon');
                        if (pdfIcon) {
                            return pdfIcon.src;
                        }
                    }
                    return '{{ asset('images/d/go-quick/pdf.png') }}';
                }

                const pdfBtn = document.querySelector('.bulk-upload-btn[data-type="pdf"]');
                if (pdfBtn) {
                    const pdfIcon = pdfBtn.querySelector('.btn-file-icon');
                    if (pdfIcon) {
                        return pdfIcon.src;
                    }
                }
                return '{{ asset('images/d/go-quick/pdf.png') }}';
            }

            async function startBulkUpload(file) {
                bulkUploadBox.classList.add('d-none');
                bulkProgressSection.classList.remove('d-none');

                document.getElementById('bulkFileName').textContent = file.name;
                document.getElementById('bulkFileSize').textContent = `${(file.size / 1024).toFixed(2)} KB`;

                const thumbnailElement = document.querySelector('.bulk-file-thumb');
                if (thumbnailElement) {
                    thumbnailElement.src = getFileIcon(file);
                }

                uploadProgress = 0;
                updateBulkProgress(0);

                uploadInterval = setInterval(() => {
                    uploadProgress += Math.random() * 10;
                    if (uploadProgress >= 90) {
                        uploadProgress = 90;
                        clearInterval(uploadInterval);
                    }
                    updateBulkProgress(uploadProgress);
                }, 200);

                try {
                    const fileName = file.name.toLowerCase();
                    const isPDF = fileName.endsWith('.pdf') || file.type === 'application/pdf';
                    const isExcel = fileName.endsWith('.xlsx') || fileName.endsWith('.xls') || 
                                   file.type.includes('spreadsheet') || file.type.includes('excel');
                    const isZip = fileName.endsWith('.zip') || file.type === 'application/zip' || 
                                 file.type === 'application/x-zip-compressed';

                    let apiUrl = '';
                    if (isPDF) {
                        apiUrl = '{{ route("tools.go-quick.process-pdf") }}';
                    } else if (isExcel) {
                        apiUrl = '{{ route("tools.go-quick.process-excel") }}';
                    } else if (isZip) {
                        apiUrl = '{{ route("tools.go-quick.process-cccd") }}';
                    } else {
                        throw new Error('Vui lòng chọn file PDF, Excel hoặc ZIP');
                    }

                    currentAbortController = new AbortController();

                    const formData = new FormData();
                    formData.append('file', file);

                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json' 
                        },
                        body: formData,
                        redirect: 'follow',
                        signal: currentAbortController.signal
                    });

                    clearInterval(uploadInterval);
                    uploadProgress = 100;
                    updateBulkProgress(100);

                    if (response.status === 401 || response.status === 403) {
                        throw new Error('Bạn cần đăng nhập để sử dụng tính năng này. Vui lòng đăng nhập và thử lại.');
                    }

                    if (!response.ok) {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const errorResult = await response.json();
                            throw new Error(errorResult.message || `HTTP error! status: ${response.status}`);
                        } else {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                    }

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text);
                        if (text.includes('<!DOCTYPE') || text.includes('<html') || text.includes('login')) {
                            throw new Error('Bạn cần đăng nhập để sử dụng tính năng này. Vui lòng đăng nhập và thử lại.');
                        }
                        throw new Error('Server trả về dữ liệu không hợp lệ. Vui lòng thử lại.');
                    }

                    const result = await response.json();

                    if (currentAbortController && currentAbortController.signal.aborted) {
                        return;
                    }

                    if (result.status === 'success') {
                        window.bulkResult = result.data || result;
                        showBulkSuccess();
                    } else {
                        showBulkFailed(result.message || 'Xử lý thất bại');
                    }
                } catch (error) {
                    if (error.name === 'AbortError' || (currentAbortController && currentAbortController.signal.aborted)) {
                        console.log('Request đã được hủy');
                        return;
                    }
                    
                    console.error('Error:', error);
                    clearInterval(uploadInterval);
                    showBulkFailed(error.message || 'Có lỗi xảy ra khi kết nối server');
                } finally {
                    currentAbortController = null;
                }
            }

            function updateBulkProgress(percent) {
                const fill = document.getElementById('bulkProgressFill');
                const text = document.getElementById('bulkProgressPercent');
                if (fill) fill.style.width = percent + '%';
                if (text) text.textContent = Math.round(percent) + '%';
            }

            document.getElementById('bulkCancelBtn').addEventListener('click', function() {
                if (currentAbortController) {
                    currentAbortController.abort();
                    currentAbortController = null;
                }
                
                if (uploadInterval) {
                    clearInterval(uploadInterval);
                    uploadInterval = null;
                }
                
                bulkProgressSection.classList.add('d-none');
                bulkUploadBox.classList.remove('d-none');
                resetBulkUpload();
            });

            function showBulkSuccess(message, showDownloadSection = true) {
                const modalMessage = bulkSuccessModal.querySelector('.bulk-modal-message');
                if (modalMessage && message) {
                    modalMessage.textContent = message;
                } else if (modalMessage && !message) {
                    modalMessage.textContent = 'File của bạn đã trích xuất dữ liệu thành công!';
                }

                bulkSuccessModal.classList.add('show');

                setTimeout(() => {
                    bulkSuccessModal.classList.remove('show');

                    if (showDownloadSection) {
                        bulkUploadTab.classList.add('d-none');
                        bulkDownloadSection.classList.remove('d-none');
                    }
                }, 2000);
            }

            async function handleMultipleImagesUpload(imageFiles) {
                try {
                    bulkUploadBox.classList.add('d-none');
                    bulkProgressSection.classList.remove('d-none');

                    document.getElementById('bulkFileName').textContent = `${imageFiles.length} ảnh`;
                    document.getElementById('bulkFileSize').textContent = `${(imageFiles.reduce((sum, f) => sum + f.size, 0) / 1024).toFixed(2)} KB`;

                    const thumbnailElement = document.querySelector('.bulk-file-thumb');
                    if (thumbnailElement) {
                        thumbnailElement.src = '{{ asset("images/d/go-quick/folder.png") }}';
                    }

                    const formData = new FormData();
                    imageFiles.forEach((file) => {
                        formData.append('images[]', file);
                    });

                    uploadProgress = 0;
                    updateBulkProgress(0);

                    currentAbortController = new AbortController();

                    const progressInterval = setInterval(() => {
                        uploadProgress += Math.random() * 10;
                        if (uploadProgress >= 90) {
                            uploadProgress = 90;
                            clearInterval(progressInterval);
                        }
                        updateBulkProgress(uploadProgress);
                    }, 200);

                    const response = await fetch('{{ route("tools.go-quick.process-cccd-multiple-images") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json' 
                        },
                        body: formData,
                        signal: currentAbortController.signal
                    });

                    clearInterval(progressInterval);
                    uploadProgress = 100;
                    updateBulkProgress(100);

                    if (response.status === 401 || response.status === 403) {
                        throw new Error('Bạn cần đăng nhập để sử dụng tính năng này. Vui lòng đăng nhập và thử lại.');
                    }

                    if (response.status === 302 || response.redirected) {
                        throw new Error('Bạn cần đăng nhập để sử dụng tính năng này. Vui lòng đăng nhập và thử lại.');
                    }

                    if (!response.ok) {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const errorResult = await response.json();
                            throw new Error(errorResult.message || `HTTP error! status: ${response.status}`);
                        } else {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                    }

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text);
                        if (text.includes('<!DOCTYPE') || text.includes('<html') || text.includes('login')) {
                            throw new Error('Bạn cần đăng nhập để sử dụng tính năng này. Vui lòng đăng nhập và thử lại.');
                        }
                        throw new Error('Server trả về dữ liệu không hợp lệ. Vui lòng thử lại.');
                    }

                    const result = await response.json();

                    if (currentAbortController && currentAbortController.signal.aborted) {
                        return;
                    }

                    if (result.status === 'success') {
                        window.bulkResult = result.data || result;
                        showBulkSuccess();
                    } else {
                        showBulkFailed(result.message || 'Xử lý thất bại');
                    }
                } catch (error) {
                    if (error.name === 'AbortError' || (currentAbortController && currentAbortController.signal.aborted)) {
                        return;
                    }
                    
                    console.error('Error:', error);
                    clearInterval(progressInterval);
                    showBulkFailed(error.message || 'Có lỗi xảy ra khi kết nối server');
                } finally {
                    currentAbortController = null;
                }
            }

            function showBulkFailed(message, resetUpload = true) {
                const modalMessage = bulkFailedModal.querySelector('.bulk-modal-message');
                if (modalMessage && message) {
                    modalMessage.innerHTML = `Trích xuất dữ liệu thất bại!<br>${message}`;
                } else if (modalMessage && !message) {
                    modalMessage.innerHTML = 'Trích xuất dữ liệu thất bại!<br>Vui lòng kiểm tra lại file';
                }

                bulkFailedModal.classList.add('show');

                setTimeout(() => {
                    bulkFailedModal.classList.remove('show');

                    if (resetUpload) {
                        bulkProgressSection.classList.add('d-none');
                        bulkUploadBox.classList.remove('d-none');
                        resetBulkUpload();
                    }
                }, 2000);
            }

            function resetBulkUpload() {
                const title = bulkUploadBox.querySelector('.bulk-upload-title');
                const subtitle = bulkUploadBox.querySelector('.bulk-upload-subtitle');
                if (title) title.textContent = 'Kéo và thả tệp của bạn vào đây!';
                if (subtitle) subtitle.textContent = 'Vui lòng tải lên PDF files';

                bulkUploadBox.classList.remove('has-error');
                const existingError = bulkUploadBox.querySelector('.bulk-error-file');

                if (existingError && bulkUploadBox.dataset.iconHtml) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = bulkUploadBox.dataset.iconHtml;
                    const restoredIcon = tempDiv.firstElementChild;
                    bulkUploadBox.replaceChild(restoredIcon, existingError);
                    delete bulkUploadBox.dataset.iconHtml;
                } else if (existingError) {
                    existingError.remove();
                }

                if (bulkFileInput) {
                    bulkFileInput.value = '';
                }
                if (bulkFolderInput) {
                    bulkFolderInput.value = '';
                }

                currentBulkFile = null;
                uploadProgress = 0;
                
                if (currentAbortController) {
                    currentAbortController.abort();
                    currentAbortController = null;
                }
                
                if (uploadInterval) {
                    clearInterval(uploadInterval);
                    uploadInterval = null;
                }
            }

            document.getElementById('reloadDownloadBtn').addEventListener('click', function() {
                bulkDownloadSection.classList.add('d-none');
                bulkProgressSection.classList.add('d-none');
                bulkUploadBox.classList.remove('d-none');
                bulkUploadTab.classList.remove('d-none');
                resetBulkUpload();
            });

            document.getElementById('downloadFileBtn').addEventListener('click', async function() {
                if (window.bulkResult && window.bulkResult.customer) {
                    const customers = window.bulkResult.customer;
                    const downloadBtn = this;
                    
                    downloadBtn.disabled = true;
                    const originalText = downloadBtn.innerHTML;
                    downloadBtn.innerHTML = '<span>Đang tạo file...</span>';

                    try {
                        const response = await fetch('{{ route("tools.go-quick.export-excel") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            },
                            body: JSON.stringify({
                                customers: customers
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Không thể tạo file Excel');
                        }

                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.href = url;
                        
                        const contentDisposition = response.headers.get('content-disposition');
                        let fileName = 'cccd_data_' + new Date().getTime() + '.xlsx';
                        if (contentDisposition) {
                            const fileNameMatch = contentDisposition.match(/filename="(.+)"/);
                            if (fileNameMatch) {
                                fileName = fileNameMatch[1];
                            }
                        }
                        
                        link.download = fileName;
                        link.style.visibility = 'hidden';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        window.URL.revokeObjectURL(url);
                    } catch (error) {
                        console.error('Error:', error);
                        showBulkFailed('Không thể tải xuống file Excel. Vui lòng thử lại.', false);
                    } finally {
                        downloadBtn.disabled = false;
                        downloadBtn.innerHTML = originalText;
                    }
                } else {
                    showBulkFailed('Không có dữ liệu để tải xuống', false);
                }
            });

            const promoBanner = document.getElementById('promoBanner');
            const promoBannerClose = document.getElementById('promoBannerClose');
            
            if (promoBannerClose && promoBanner) {
                promoBannerClose.addEventListener('click', function() {
                    promoBanner.style.display = 'none';
                    localStorage.setItem('promoBannerHidden', 'true');
                });

                if (localStorage.getItem('promoBannerHidden') === 'true') {
                    promoBanner.style.display = 'none';
                }
            }
        });
    </script>
@endpush
