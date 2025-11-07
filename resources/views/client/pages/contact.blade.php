@extends('client.layouts.app')
@section('title', 'Liên Hệ - ' . config('app.name'))
@section('description', 'Liên hệ với chúng tôi để được tư vấn miễn phí')
@section('keywords', 'liên hệ, tư vấn, gotax')

@section('content')
    <section class="contact-page container-page">
        @include('components.title-page', [
            'title' => 'LIÊN HỆ VỚI CHÚNG TÔI',
            'breadcrumb' => [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Pages']
            ]
        ])

        <div class="container">
            <div class="contact-content-wrapper mt-5">
                <div class="contact-form-section bg-white rounded-3 px-4 py-3 px-lg-5 py-lg-4">
                    <h2 class="contact-form-title">Nhận Tư Vấn Miễn Phí</h2>
                    <p class="contact-form-subtitle">Để lại thông tin để được tư vấn dịch vụ chi tiết!</p>

                    @include('components.toast-main')
                    @include('components.toast')

                    <form method="POST" action="{{ route('contact.post') }}" class="contact-form">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}"
                                placeholder="Họ và Tên" required>
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}"
                                placeholder="Email" required>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone') }}"
                                placeholder="Phone" required>
                            @error('phone')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <textarea class="form-control @error('service') is-invalid @enderror"
                                id="service" name="service" rows="4"
                                placeholder="Dịch vụ cần tư vấn" required>{{ old('service') }}</textarea>
                            @error('service')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="contact-submit-btn">
                            Đăng Ký Ngay
                        </button>

                        <p class="contact-disclaimer m-0">
                            By contacting us, you agree to your Terms of Service and Privacy Policy
                        </p>
                    </form>
                </div>

                <div class="contact-info-section py-3 py-lg-4">
                    <h2 class="contact-info-title">Thông Tin Liên Hệ</h2>

                    <div class="contact-info-item">
                        <div class="contact-icon-wrapper">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.62 10.79C8.06 13.62 10.38 15.94 13.21 17.38L15.41 15.18C15.69 14.9 16.08 14.82 16.43 14.93C17.55 15.3 18.75 15.5 20 15.5C20.55 15.5 21 15.95 21 16.5V20C21 20.55 20.55 21 20 21C10.61 21 3 13.39 3 4C3 3.45 3.45 3 4 3H7.5C8.05 3 8.5 3.45 8.5 4C8.5 5.25 8.7 6.45 9.07 7.57C9.18 7.92 9.1 8.31 8.82 8.59L6.62 10.79Z" fill="var(--primary-color)"/>
                            </svg>
                        </div>
                        <div class="contact-info-content">
                            <p class="contact-info-label">Phone</p>
                            <p class="contact-info-value">0989 466 992</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon-wrapper">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="var(--primary-color)"/>
                            </svg>
                        </div>
                        <div class="contact-info-content">
                            <p class="contact-info-label">Email</p>
                            <p class="contact-info-value">wetechsoft.vn@gmail.com</p>
                        </div>
                    </div>

                    <div class="contact-map-wrapper">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.0964666091254!2d105.78052341493278!3d21.02812778599829!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab4cdd226e51%3A0x9c5b6b5b5b5b5b5b!2zSMOgIE3huqFpIEh1eWU!5e0!3m2!1svi!2s!4v1234567890123!5m2!1svi!2s"
                            width="100%" 
                            height="100%" 
                            style="border:0; border-radius: 12px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="contact-map-iframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/contact.css')
@endpush

