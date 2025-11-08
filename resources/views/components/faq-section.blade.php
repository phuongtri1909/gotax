@php
    $faqs = $faqs ?? [
        [
            'question' => 'Website có bị gián đoạn khi Tổng cục Thuế nâng cấp không?',
            'answer' => 'Chúng tôi theo dõi sát lịch bảo trì hệ thống thuế và sẽ thông báo rõ trên website nếu có ảnh hưởng. Trong trường hợp bị gián đoạn, hệ thống sẽ tự động thử lại sau.',
            'open' => true
        ],
        [
            'question' => 'Có giới hạn số lượng hóa đơn hoặc mã số thuế khi tải/tra cứu không?',
            'answer' => 'Không có giới hạn số lượng hóa đơn hoặc mã số thuế khi sử dụng dịch vụ tra cứu và tải về của chúng tôi.',
            'open' => false
        ],
        [
            'question' => 'Tôi có thể sử dụng web này cho nhiều doanh nghiệp không?',
            'answer' => 'Có, bạn có thể sử dụng website này cho nhiều doanh nghiệp khác nhau với cùng một tài khoản.',
            'open' => false
        ],
        [
            'question' => 'Web có mất phí nâng cấp không?',
            'answer' => 'Chúng tôi cung cấp các gói dịch vụ với mức phí rõ ràng. Bạn có thể xem chi tiết các gói dịch vụ và mức phí tại trang đăng ký.',
            'open' => false
        ],
        [
            'question' => 'Dữ liệu sau khi tải về có thể nhập vào phần mềm kế toán không?',
            'answer' => 'Có, dữ liệu sau khi tải về được định dạng chuẩn và có thể nhập trực tiếp vào các phần mềm kế toán phổ biến.',
            'open' => false
        ]
    ];
@endphp

<div class="faq-section">
    <div class="faq-list">
        @foreach($faqs as $index => $faq)
            <div class="faq-item {{ $faq['open'] ?? false ? 'active' : '' }}" data-index="{{ $index }}">
                <div class="faq-question" onclick="toggleFaq({{ $index }})">
                    <span class="faq-question-text">{{ $faq['question'] }}</span>
                    <span class="faq-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="faq-icon-minus">
                            <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="faq-icon-plus">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
    <script>
        function toggleFaq(index) {
            const faqItem = document.querySelector(`.faq-item[data-index="${index}"]`);
            if (!faqItem) return;

            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            // Open clicked item if it was closed
            if (!isActive) {
                faqItem.classList.add('active');
            }
        }
    </script>
@endpush

@push('styles')
    @vite('resources/assets/frontend/css/components/faq-section.css')
@endpush





