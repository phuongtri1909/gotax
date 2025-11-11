@extends('client.layouts.app')
@section('title', 'Dùng thử Go-Invoice - ' . config('app.name'))
@section('description', 'Đăng ký tài khoản dùng thử Go-Invoice - ' . config('app.name'))
@section('keywords', 'Dùng thử Go Invoice, ' . config('app.name'))

@section('content')
    <section class="home-page container-page py-5">
        <div class="container">
           <div class="p-5 pt-0">
            <h1 class="trial-title text-center mb-4 pt-5">ĐĂNG KÝ TÀI KHOẢN DÙNG THỬ GO-INVOICE</h1>
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-5">
                    <div class="trial-illustration">
                        <img src="{{ asset('images/d/trials/register.png') }}" alt="Go-Invoice Illustration" class="img-fluid">
                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="trial-form-card">
                        <form method="post" action="#" onsubmit="event.preventDefault();">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label color-primary">Họ và tên*</label>
                                <input type="text" class="form-control trial-input" placeholder="Tên đăng nhập">
                            </div>
                            <div class="mb-3">
                                <label class="form-label color-primary">Email*</label>
                                <input type="email" class="form-control trial-input" placeholder="Nhập email của bạn">
                            </div>
                            <div class="mb-4">
                                <label class="form-label color-primary">Số điện thoại*</label>
                                <input type="tel" class="form-control trial-input" placeholder="Nhập số điện thoại của bạn">
                            </div>
                            <button type="submit" class="btn trial-submit w-100">Đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="trial-policy mt-5">
                <h2 class="trial-policy-title mb-0">Chính sách dùng thử:</h2>
                <p class="trial-policy-text">
                    GoTax dành tặng Quý Khách 2 tuần miễn phí để trải nghiệm. Sau đó, Quý Khách có thể đánh giá và cân nhắc đăng ký chính thức.
                </p>
            </div>
           </div>
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-invoice-trial.css')
@endpush


