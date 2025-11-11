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
                        @endphp
                        <div class="pricing-bot-card {{ $index === 0 ? 'active' : '' }}">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input pricing-radio" type="radio" name="pricing_bot_package" 
                                       id="package_{{ $index }}" value="{{ $pkg->name ?? '' }}"
                                       {{ $index === 0 ? 'checked' : '' }}>
                                <label class="form-check-label flex-grow-1" for="package_{{ $index }}">
                                    <div class="pricing-bot-card-content">
                                        <div class="pricing-bot-left">
                                            <span class="package-name">{{ $pkg->name ?? '' }}</span>
                                            @if(isset($pkg->badge) && $pkg->badge)
                                                <span class="package-badge">{{ $pkg->badge }}</span>
                                            @endif
                                        </div>
                                        <div class="pricing-bot-right">
                                            <div class="pricing-info-item">
                                                <span class="info-label">Số lượng MST</span>
                                                <span class="info-value mst-value">{{ $pkg->mst ?? '' }} MST</span>
                                            </div>
                                            <div class="pricing-info-item">
                                                <span class="info-label">Phí đăng ký</span>
                                                <span class="info-value price-value">{{ $pkg->price ?? '' }}₫</span>
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

@push('styles')
    @vite('resources/assets/frontend/css/components/pricing-bot-section.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('.pricing-bot-card .pricing-radio');
            
            radioButtons.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    // Remove active class from all cards
                    document.querySelectorAll('.pricing-bot-card').forEach(function(card) {
                        card.classList.remove('active');
                    });
                    
                    // Add active class to selected card
                    if (this.checked) {
                        this.closest('.pricing-bot-card').classList.add('active');
                    }
                });
            });
        });
    </script>
@endpush