@props([
    'features' => [],
    'packages' => [],
])

<section class="pricing-bot-section">
    <div class="container">
        <h2 class="tools-title text-center">Bảng Giá</h2>
        <p class="tools-subtitle text-center mb-5">Giải pháp phù hợp cho mọi nhu cầu tải hóa đơn</p>
        
        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="pricing-bot-features-card">
                    <div class="features-header">
                        <h3 class="features-title">Tính năng nổi bật</h3>
                    </div>
                    <ul class="features-list">
                        @foreach($features as $feature)
                            <li class="feature-item">
                                <span>{{ $feature }}</span>
                                <img src="{{ asset('images/d/tools/check-circle.svg') }}" alt="Checkmark" class="checkmark-icon">
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="col-12 col-lg-7">
                <div class="pricing-bot-packages">
                    @foreach($packages as $index => $package)
                        @php
                            $pkg = is_array($package) ? (object) $package : $package;
                            $cleanPrice = str_replace(['.', ',', ' ', 'đ'], '', $pkg->price ?? '0');
                            $packageData = [
                                'name' => $pkg->name ?? '',
                                'description' => ($pkg->mst ?? '') . ' MST / năm',
                                'price' => $cleanPrice,
                                'discount' => $pkg->discount ?? null,
                            ];
                        @endphp
                        <div class="pricing-bot-card"
                             data-package="{{ json_encode($packageData) }}">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input pricing-radio" type="radio" name="pricing_bot_package" 
                                       id="package_{{ $index }}" value="{{ $pkg->name ?? '' }}">
                                <label class="form-check-label flex-grow-1" for="package_{{ $index }}">
                                    <div class="pricing-bot-card-content">
                                        <div class="pricing-bot-left">
                                            <span class="package-name">{{ $pkg->name ?? '' }}</span>
                                            @if(isset($pkg->discount) && $pkg->discount)
                                                <span class="package-badge">{{ $pkg->discount }}</span>
                                            @endif
                                        </div>
                                        <div class="pricing-bot-right">
                                            <div class="pricing-info-item align-items-end">
                                                <span class="info-label">{{ $pkg->title }}</span>
                                                <span class="info-value mst-value">{{ $pkg->mst ?? '' }} MST</span>
                                            </div>
                                            <div class="pricing-info-item align-items-end">
                                                <span class="info-label">Phí đăng ký</span>
                                                <span class="info-value price-value">{{ $pkg->price ?? '' }} đ</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<x-payment.register-modal modalId="registerModal" />

@push('styles')
    @vite('resources/assets/frontend/css/components/pricing-bot-section.css')
@endpush

@push('scripts')
    <script>
        (function() {
            function initPricingBot() {
                const modal = document.getElementById('registerModal');
                if (!modal) {
                    setTimeout(initPricingBot, 100);
                    return;
                }
                
                const radioButtons = document.querySelectorAll('.pricing-bot-card .pricing-radio');
                const cards = document.querySelectorAll('.pricing-bot-card');
                
                function openModalWithPackage(card) {
                    const packageDataStr = card.getAttribute('data-package');
                    
                    if (packageDataStr && modal) {
                        try {
                            const packageData = JSON.parse(packageDataStr);
                            
                            // Create temporary button to trigger modal with data
                            // This will use the modal's updatePackageInfo function
                            const tempButton = document.createElement('button');
                            tempButton.style.display = 'none';
                            tempButton.setAttribute('data-bs-toggle', 'modal');
                            tempButton.setAttribute('data-bs-target', '#registerModal');
                            tempButton.setAttribute('data-package', packageDataStr);
                            document.body.appendChild(tempButton);
                            
                            // Trigger click to open modal (Bootstrap will handle it and call updatePackageInfo)
                            tempButton.click();
                            
                            // Remove temp button after modal opens
                            setTimeout(function() {
                                if (document.body.contains(tempButton)) {
                                    document.body.removeChild(tempButton);
                                }
                            }, 500);
                        } catch (e) {
                            console.error('Error opening modal:', e);
                        }
                    }
                }
                
                function calculateTotal() {
                    if (!modal) return;
                    
                    const registrationFeeText = modal.querySelector('[data-registration-fee]')?.textContent || '0đ';
                    const discountText = modal.querySelector('[data-discount]')?.textContent || '0đ';
                    
                    const registrationFee = parseFloat(registrationFeeText.replace(/[^\d]/g, '') || 0);
                    const discount = parseFloat(discountText.replace(/[^\d-]/g, '') || 0);
                    const total = Math.max(0, registrationFee + discount);
                    
                    const totalEl = modal.querySelector('[data-total-amount]');
                    if (totalEl) {
                        totalEl.textContent = total.toLocaleString('vi-VN') + 'đ';
                    }
                }
                
                radioButtons.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        if (this.checked) {
                            const card = this.closest('.pricing-bot-card');
                            
                            cards.forEach(function(c) {
                                c.classList.remove('active');
                            });
                            
                            card.classList.add('active');
                            
                            setTimeout(function() {
                                openModalWithPackage(card);
                            }, 10);
                        }
                    });
                });
                
                cards.forEach(function(card) {
                    card.addEventListener('click', function(e) {
                        if (e.target.type === 'radio') {
                            return;
                        }
                        
                        if (e.target.tagName === 'LABEL' || e.target.closest('label')) {
                            return;
                        }
                        
                        const radio = this.querySelector('.pricing-radio');
                        if (radio) {
                            radio.checked = true;
                            radio.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                });
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initPricingBot);
            } else {
                setTimeout(initPricingBot, 100);
            }
        })();
    </script>
@endpush