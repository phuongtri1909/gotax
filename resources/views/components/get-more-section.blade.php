@php
    $data = $data ?? [
        'title' => 'Nhận Được Nhiều Hơn!',
        'description' => 'Tận hưởng tất cả các công cụ bạn cần để làm việc hiệu quả, đồng thời đảm bảo dữ liệu của bạn an toàn và bảo mật.',
        'benefits' => [
            'Truy Cập Đầy Đủ Vào Các Công Cụ',
            'Không Giới Hạn Số Lượng Hoá Đơn Tải Về',
            'Tặng Kèm Khoá Học Thủ Tục Pháp Lý',
            'Kết Nối Các Công Cụ Và Tạo Ra Quy Trình Làm Việc Hiệu Quả',
        ],
        'button_text' => 'Đăng Ký Premium',
        'button_link' => '#',
        'illustration_image' => asset('images/d/get-more.png'),
    ];
@endphp

<section class="get-more-section">
    <div class="get-more-content-wrapper">
        <div class="get-more-illustration">
            <img src="{{ $data['illustration_image'] }}" alt="Illustration" class="illustration-img">
        </div>
        <div class="get-more-text-content">
            <h2 class="get-more-title">{{ $data['title'] }}</h2>
            <p class="get-more-description">{{ $data['description'] }}</p>
            <ul class="get-more-benefits">
                @foreach ($data['benefits'] as $benefit)
                    <li class="benefit-item">
                        <img src="{{ asset('images/svg/checkmark.svg') }}" alt="Checkmark" class="checkmark-icon">
                        <span>{{ $benefit }}</span>
                    </li>
                @endforeach
            </ul>
            <a href="{{ $data['button_link'] }}" class="get-more-button btn btn-sm px-3">{{ $data['button_text'] }}</a>
        </div>
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/get-more-section.css')
@endpush

