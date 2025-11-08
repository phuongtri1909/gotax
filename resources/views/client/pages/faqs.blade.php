@extends('client.layouts.app')
@section('title', 'Câu Hỏi Thường Gặp - ' . config('app.name'))
@section('description', 'Câu hỏi thường gặp về dịch vụ GoTax')
@section('keywords', 'faq, câu hỏi thường gặp, gotax')

@section('content')
    <section class="faqs-page container-page">
        @include('components.title-page', [
            'title' => 'CÂU HỎI THƯỜNG GẶP',
            'breadcrumb' => [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Pages']
            ]
        ])

        <div class="container">
            <div class="faqs-content-wrapper mt-5">
                @include('components.faq-section', [
                    'faqs' => [
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
                    ]
                ])
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/faqs.css')
@endpush





