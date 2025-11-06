@php
    $data = $data ?? [
        'text' => 'Bắt Đầu Sử Dụng Nền Tảng Thông Minh Go Suite!',
        'button_text' => 'Đăng Ký Ngay',
        'button_link' => '#',
        'icon' => asset('images/d/cta-banner/cta-icon.svg'),
    ];
@endphp

<section class="cta-banner-section">
    <img src="{{ asset('images/d/cta-banner/cta1.png') }}" alt="CTA Background 1" class="cta-bg-image cta-bg-1">
    <img src="{{ asset('images/d/cta-banner/cta2.png') }}" alt="CTA Background 2" class="cta-bg-image cta-bg-2">
    <div class="cta-banner-content">
        <div class="d-flex align-items-center">
            <div class="cta-icon-wrapper me-4">
                <div class="cta-icon-circle">
                    <img src="{{ $data['icon'] }}" alt="Icon" class="cta-icon">
                </div>
            </div>
            <p class="cta-text">{{ $data['text'] }}</p>
        </div>
        <div class="cta-button-wrapper">
            <a href="{{ $data['button_link'] }}" class="cta-button">{{ $data['button_text'] }}</a>
        </div>
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/cta-banner-section.css')
@endpush

