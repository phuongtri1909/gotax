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
                @if($faqs->count() > 0)
                    @include('components.faq-section', [
                        'faqs' => $faqs->map(function($faq, $index) {
                            return [
                                'question' => $faq->question,
                                'answer' => $faq->answer,
                                'open' => $index === 0
                            ];
                        })->toArray()
                    ])

                    <div class="pagination-wrapper mt-4">
                        {{ $faqs->links('components.paginate') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted">Chưa có câu hỏi thường gặp nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/faqs.css')
@endpush





