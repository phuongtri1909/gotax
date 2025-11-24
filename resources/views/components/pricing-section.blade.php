@props([
    'packages' => [],
])

<section class="pricing-section">
    <div class="container">
        <h2 class="tools-title">Bảng Giá</h2>
        <p class="tools-subtitle">Giải pháp phù hợp cho mọi nhu cầu tải hóa đơn</p>
        <div class="row g-4">
            @foreach ($packages as $package)
                @php
                    $pkg = is_array($package) ? (object) $package : $package;
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="pricing-card {{ $pkg->highlight ?? '' }}">
                        <div class="pricing-header">
                            @if (isset($pkg->badge) && $pkg->badge)
                                <span class="pricing-badge pricing-badge-{{ $pkg->badge_type ?? 'popular' }}">
                                    {{ $pkg->badge }}
                                </span>
                            @endif
                            <div class="pricing-bg-name">
                                <h3 class="pricing-name py-2">{{ $pkg->name ?? '' }}</h3>
                            </div>
                            @if (isset($pkg->discount) && $pkg->discount)
                                <span class="pricing-discount">{{ $pkg->discount }}</span>
                            @endif
                        </div>

                        <div class="pricing-body">
                            <div class="pricing-price d-flex align-items-baseline justify-content-center">
                                <span class="price-amount">{{ $pkg->price ?? '' }}đ</span>
                                <span class="price-unit"> / năm</span>
                            </div>

                            @if (route::currentRouteName() == 'tools.go-invoice')
                                <div class="pricing-copyright">
                                    <p class="copyright-fee mb-0">Phí bản quyền: 500.000đ</p>
                                    <p class="copyright-note">(Thanh toán 1 lần duy nhất / tài khoản)</p>
                                </div>
                            @endif

                            <div class="pricing-mst">
                                <span class="mst-number">{{ $pkg->mst ?? '' }} MST</span>
                            </div>

                            <ul class="pricing-features">
                                @foreach ($pkg->features ?? [] as $feature)
                                    <li class="feature-item feature-item-end">
                                        <img src="{{ asset('images/d/tools/check.svg') }}" alt="Checkmark"
                                            class="checkmark-icon">
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            @php
                                $cleanPrice = str_replace(['.', ',', ' ', 'đ'], '', $pkg->price ?? '0');
                                $packageData = [
                                    'id' => $pkg->id ?? null,
                                    'package_id' => $pkg->id ?? null,
                                    'name' => $pkg->name ?? '',
                                    'description' => ($pkg->mst ?? '') . ' MST / năm',
                                    'price' => $cleanPrice,
                                    'badge' => $pkg->badge ?? null,
                                    'discount' => $pkg->discount ?? null,
                                ];
                            @endphp
                            <button type="button" 
                                    class="btn pricing-button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#registerModal"
                                    data-package="{{ json_encode($packageData) }}">
                                {{ $pkg->button_text ?? 'Đăng ký' }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<x-payment.register-modal modalId="registerModal" />

@push('styles')
    @vite('resources/assets/frontend/css/components/pricing-section.css')
@endpush
