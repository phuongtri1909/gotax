@props([
    'title' => 'Công Cụ Tải Hoá Đơn Điện Tử Hàng Loạt!',
    'description' =>
        'Tăng tốc quy trình tải hoá đơn điện tử. Công cụ thông minh, giao diện dễ dùng, hỗ trợ mọi quy mô doanh nghiệp và đảm bảo dữ liệu luôn an toàn tuyệt đối.',
    'titleLeft' => 'Đơn giản hóa quy trình lấy hóa đơn điện tử',
    'descriptionLeft' => 'Công cụ tải hóa đơn điện tử hàng loạt – Nhanh, Chính xác, Bảo mật',
    'primaryText' => 'Đăng Ký Ngay!',
    'primaryLink' => '#',
    'secondaryText' => 'Dùng Thử',
    'secondaryLink' => '#',
    'image' => null,
])

<section class="tool-hero-section">
    <h2 class="tools-title">{{ $title }}</h2>
    <p class="tools-subtitle">{{ $description }}</p>

    <div class="row align-items-center g-4 g-lg-5">
        <div class="col-12 col-lg-5">
            <div class="tool-hero-left">
                <h2 class="tool-hero-title">{!! $titleLeft !!}</h2>
                <p class="tool-hero-desc">
                    {{ $descriptionLeft }}
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ $primaryLink }}" class="btn tool-hero-btn-primary" data-scroll-to-pricing>
                        {{ $primaryText }}
                    </a>
                    <a href="{{ $secondaryLink }}" class="btn tool-hero-btn-secondary">
                        {{ $secondaryText }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-1">
            <div class="tool-hero-divider"></div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="tool-hero-right">
                <img src="{{ $image ?? asset('images/d/tools/banner-invoice.png') }}" alt="Tool Preview"
                    class="tool-hero-image"
                    onerror="this.src='https://via.placeholder.com/960x540/0B4069/FFFFFF?text=Tool+Preview';">
            </div>
        </div>
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/tool-hero.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerBtn = document.querySelector('[data-scroll-to-pricing]');
            if (registerBtn) {
                registerBtn.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href === '#' || href === '') {
                        e.preventDefault();
                        const pricingSection = document.getElementById('pricing-section');
                        if (pricingSection) {
                            const headerOffset = 100;
                            const elementPosition = pricingSection.getBoundingClientRect().top;
                            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            }
        });
    </script>
@endpush
