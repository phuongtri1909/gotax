@extends('client.pages.tools.go-soft.layout')

@section('go-soft-content')
    <div class="go-soft-xml-convert">
        <!-- Upload Box -->
        <div class="xml-upload-wrapper" id="xmlUploadWrapper">
            <div class="xml-upload-box" id="xmlUploadBox">
                <div class="xml-upload-icon">
                    <img src="{{ asset('images/d/go-soft/upload-illustration.svg') }}" alt="Upload" class="upload-illustration">
                </div>
                <h3 class="xml-upload-title">Kéo và thả tệp của bạn vào đây!</h3>
                <p class="xml-upload-subtitle">Vui lòng tải lên <strong>XML files (GTGT)</strong></p>
                <p class="xml-upload-limit">Kích thước tối đa của một tập tin là <strong>10 MB</strong></p>
                
                <button class="btn-upload-xml" id="btnUploadXml">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M17 13V15C17 15.5304 16.7893 16.0391 16.4142 16.4142C16.0391 16.7893 15.5304 17 15 17H5C4.46957 17 3.96086 16.7893 3.58579 16.4142C3.21071 16.0391 3 15.5304 3 15V13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13 6L10 3L7 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 3V13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Tải Lên File Tờ Khai</span>
                </button>
                
                <input type="file" id="xmlFileInput" accept=".xml" hidden>
            </div>
            
            <!-- Progress Section -->
            <div class="xml-progress-section d-none" id="xmlProgressSection">
                <div class="xml-file-card">
                    <div class="xml-file-info">
                        <img src="{{ asset('images/svg/xml-icon.svg') }}" alt="XML" class="xml-file-thumb">
                        <div class="xml-file-details">
                            <p class="xml-file-name" id="xmlFileName">file-giá-trị-gia-tăng.xml</p>
                            <p class="xml-file-size" id="xmlFileSize">34 KB</p>
                        </div>
                    </div>
                    <div class="xml-progress-bar">
                        <div class="xml-progress-fill" id="xmlProgressFill" style="width: 0%"></div>
                    </div>
                    <div class="xml-progress-info">
                        <span class="xml-progress-percent" id="xmlProgressPercent">0%</span>
                    </div>
                </div>
                <button class="btn-cancel" id="xmlCancelBtn">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>Cancel</span>
                </button>
            </div>
        </div>
        
        <!-- Back Button -->
        <div class="content-back">
            <a href="{{ route('tools.go-soft') }}" class="btn btn-back-content">
                <img src="{{ asset('images/svg/arrow-left.svg') }}" alt="">
                <span>Trở lại</span>
            </a>
        </div>
    </div>
    
    <!-- Success/Failed Modals -->
    <x-go-soft.result-modals />
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const xmlUploadBox = document.getElementById('xmlUploadBox');
    const xmlUploadWrapper = document.getElementById('xmlUploadWrapper');
    const xmlFileInput = document.getElementById('xmlFileInput');
    const btnUploadXml = document.getElementById('btnUploadXml');
    const xmlProgressSection = document.getElementById('xmlProgressSection');
    const xmlCancelBtn = document.getElementById('xmlCancelBtn');
    
    let currentXmlFile = null;
    let xmlUploadProgress = 0;
    let xmlUploadInterval = null;
    
    // Upload button click
    btnUploadXml.addEventListener('click', function() {
        xmlFileInput.click();
    });
    
    // File input change
    xmlFileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            handleXmlFileUpload(files[0]);
        }
    });
    
    // Drag and drop
    xmlUploadBox.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    xmlUploadBox.addEventListener('dragleave', function() {
        this.classList.remove('dragover');
    });
    
    xmlUploadBox.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = Array.from(e.dataTransfer.files);
        if (files.length > 0) {
            handleXmlFileUpload(files[0]);
        }
    });
    
    // Handle XML file upload
    function handleXmlFileUpload(file) {
        // Validate file
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        // Clear error state
        xmlUploadBox.classList.remove('has-error');
        const existingError = xmlUploadWrapper.querySelector('.xml-error-file');
        if (existingError) existingError.remove();
        
        // Restore original title
        const title = xmlUploadBox.querySelector('.xml-upload-title');
        const subtitle = xmlUploadBox.querySelector('.xml-upload-subtitle');
        if (title) title.textContent = 'Kéo và thả tệp của bạn vào đây!';
        if (subtitle) subtitle.innerHTML = 'Vui lòng tải lên <strong>XML files (GTGT)</strong>';
        
        // Check file size
        if (file.size > maxSize) {
            showXmlError(file, 'Loại tệp tin không đúng!');
            return;
        }
        
        // Check file type
        if (!file.name.match(/\.xml$/i)) {
            showXmlError(file, 'Loại tệp tin không đúng!');
            return;
        }
        
        // File is valid - show progress
        currentXmlFile = file;
        startXmlUpload(file);
    }
    
    // Show XML error
    function showXmlError(file, message) {
        xmlUploadBox.classList.add('has-error');
        
        // Change title
        const title = xmlUploadBox.querySelector('.xml-upload-title');
        const subtitle = xmlUploadBox.querySelector('.xml-upload-subtitle');
        if (title) title.textContent = message;
        if (subtitle) subtitle.innerHTML = 'Vui lòng tải lên <strong>XML files (GTGT)</strong>';
        
        // Add error file display
        const errorFile = document.createElement('div');
        errorFile.className = 'xml-error-file';
        errorFile.innerHTML = `
            <p class="xml-error-file-name">${file.name}</p>
            <p class="xml-error-file-size">${(file.size / 1024).toFixed(2)} KB</p>
        `;
        xmlUploadBox.insertBefore(errorFile, btnUploadXml);
    }
    
    // Start XML upload with progress
    function startXmlUpload(file) {
        // Hide upload box, show progress
        xmlUploadBox.classList.add('d-none');
        xmlProgressSection.classList.remove('d-none');
        
        // Set file info
        document.getElementById('xmlFileName').textContent = file.name;
        document.getElementById('xmlFileSize').textContent = `${(file.size / 1024).toFixed(2)} KB`;
        
        // Simulate upload progress
        xmlUploadProgress = 0;
        updateXmlProgress(0);
        
        xmlUploadInterval = setInterval(() => {
            xmlUploadProgress += Math.random() * 15;
            if (xmlUploadProgress >= 100) {
                xmlUploadProgress = 100;
                clearInterval(xmlUploadInterval);
                
                // Fake API call result (50% success)
                setTimeout(() => {
                    const isSuccess = Math.random() > 0.5;
                    if (isSuccess) {
                        showXmlSuccess();
                    } else {
                        showXmlFailed();
                    }
                }, 500);
            }
            updateXmlProgress(xmlUploadProgress);
        }, 200);
    }
    
    // Update progress bar
    function updateXmlProgress(percent) {
        const fill = document.getElementById('xmlProgressFill');
        const text = document.getElementById('xmlProgressPercent');
        if (fill) fill.style.width = percent + '%';
        if (text) text.textContent = Math.round(percent) + '%';
    }
    
    // Cancel upload
    xmlCancelBtn.addEventListener('click', function() {
        if (xmlUploadInterval) {
            clearInterval(xmlUploadInterval);
        }
        // Reset to upload box
        xmlProgressSection.classList.add('d-none');
        xmlUploadBox.classList.remove('d-none');
        resetXmlUpload();
    });
    
    // Show success
    function showXmlSuccess() {
        document.getElementById('goSoftSuccessModal').classList.add('show');
        
        setTimeout(() => {
            document.getElementById('goSoftSuccessModal').classList.remove('show');
            // Reset for next upload
            xmlProgressSection.classList.add('d-none');
            xmlUploadBox.classList.remove('d-none');
            resetXmlUpload();
        }, 2000);
    }
    
    // Show failed
    function showXmlFailed() {
        document.getElementById('goSoftFailedModal').classList.add('show');
        
        setTimeout(() => {
            document.getElementById('goSoftFailedModal').classList.remove('show');
            // Reset to upload box
            xmlProgressSection.classList.add('d-none');
            xmlUploadBox.classList.remove('d-none');
            resetXmlUpload();
        }, 2000);
    }
    
    // Reset XML upload
    function resetXmlUpload() {
        const title = xmlUploadBox.querySelector('.xml-upload-title');
        const subtitle = xmlUploadBox.querySelector('.xml-upload-subtitle');
        if (title) title.textContent = 'Kéo và thả tệp của bạn vào đây!';
        if (subtitle) subtitle.innerHTML = 'Vui lòng tải lên <strong>XML files (GTGT)</strong>';
        
        xmlUploadBox.classList.remove('has-error');
        const existingError = xmlUploadWrapper.querySelector('.xml-error-file');
        if (existingError) existingError.remove();
        
        currentXmlFile = null;
        xmlUploadProgress = 0;
    }
});
</script>
@endpush


