@php
    $testimonials = $testimonials ?? [
        [
            'id' => 1,
            'text' => 'Mỗi kỳ kê khai thuế, team mình gần như \'giải phóng\' được cả tuần nhờ vào công cụ này. Không thể thiếu được nữa!',
            'rating' => 5,
            'avatar' => asset('images/dev/avatar.png'),
            'name' => 'Thuy Le Thi',
        ],
        [
            'id' => 2,
            'text' => 'Trợ thủ đắc lực\' cho dân kế toán - cảm giác như có thêm một đồng nghiệp chỉ chuyên lo phần tra cứu và tải dữ liệu. Nhẹ đầu hơn hẳn mỗi kỳ quyết toán.',
            'rating' => 5,
            'avatar' => asset('images/dev/avatar1.png'),
            'name' => 'Nguyen Thi Hue',
        ],
        [
            'id' => 3,
            'text' => 'Giải pháp quá tuyệt vời cho bộ phận kế toán doanh nghiệp vừa và nhỏ. Tiết kiệm được nhiều nhân sự và công sức cho công việc kiểm tra và tải dữ liệu thuế.',
            'rating' => 5,
            'avatar' => asset('images/dev/avatar2.png'),
            'name' => 'Phuong Anh Dao',
        ],
        [
            'id' => 4,
            'text' => 'Rất tiện lợi cho dân kế toán như mình. Tải hóa đơn, tờ khai số lượng lớn mà không lo lỗi hệ thống hay quá tải.',
            'rating' => 5,
            'avatar' => asset('images/dev/avatar3.png'),
            'name' => 'Dao Minh Duc',
        ],
    ];
    $viewMoreLink = $viewMoreLink ?? '#';
@endphp

<section class="testimonials-section">
    <h2 class="testimonials-title">
        <span class="title-part-1">Khách Hàng Nói Gì Về</span>
        <span class="title-part-2"> Go Suite</span>
    </h2>

    <div class="testimonials-grid">
        @foreach ($testimonials as $testimonial)
            <div class="testimonial-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="quote-icon">
                        <img src="{{ asset('images/svg/testimonials/quote.svg') }}" alt="Quote" class="quote-icon-img">
                    </div>
                    <div class="testimonial-rating">
                        @for ($i = 0; $i < $testimonial['rating']; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                </div>
                <p class="testimonial-text mb-0">{{ $testimonial['text'] }}</p>
                <hr class="mb-4 mt-0 divider-testimonials">
                <div class="testimonial-author">
                    <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="author-avatar">
                    <span class="author-name">{{ $testimonial['name'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="testimonials-footer">
        <a href="{{ $viewMoreLink }}" class="view-more-button">
            Xem thêm <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/testimonials-section.css')
@endpush

