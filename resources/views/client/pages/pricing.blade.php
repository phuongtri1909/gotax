@extends('client.layouts.app')
@section('title', 'Bảng Giá - ' . config('app.name'))
@section('description', 'Xem bảng giá chi tiết cho tất cả các công cụ: Go Invoice, Go Bot, Go Soft, Go Quick.')

@section('content')
    <section class="pricing-page container-page">
        <div class="container">
            <div class="pricing-page-header text-center mb-5">
                <h1 class="pricing-page-title">Bảng Giá</h1>
                <p class="pricing-page-subtitle">Chọn gói dịch vụ phù hợp với nhu cầu của bạn</p>
            </div>

            <!-- Tool Tabs -->
            <div class="pricing-tabs-wrapper mb-5">
                <div class="pricing-tabs">
                    <button class="pricing-tab active" data-tool="go-invoice">
                        <span class="tab-icon">
                            <i class="fas fa-file-invoice"></i>
                        </span>
                        <span class="tab-text">Go Invoice</span>
                    </button>
                    <button class="pricing-tab" data-tool="go-bot">
                        <span class="tab-icon">
                            <i class="fas fa-robot"></i>
                        </span>
                        <span class="tab-text">Go Bot</span>
                    </button>
                    <button class="pricing-tab" data-tool="go-soft">
                        <span class="tab-icon">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <span class="tab-text">Go Soft</span>
                    </button>
                    <button class="pricing-tab" data-tool="go-quick">
                        <span class="tab-icon">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <span class="tab-text">Go Quick</span>
                    </button>
                </div>
            </div>

            <!-- Pricing Content -->
            <div class="pricing-content-wrapper">
                <!-- Go Invoice -->
                <div class="pricing-content active" id="pricing-go-invoice">
                    <x-pricing-section :packages="$goInvoicePackages" :showModal="false" toolType="go-invoice" />
                </div>

                <!-- Go Bot -->
                <div class="pricing-content" id="pricing-go-bot">
                    <x-pricing-bot-section :features="$botFeatures" :packages="$goBotPackages" :showModal="false" toolType="go-bot" />
                </div>

                <!-- Go Soft -->
                <div class="pricing-content" id="pricing-go-soft">
                    <x-pricing-section :packages="$goSoftPackages" :showModal="false" toolType="go-soft" />
                </div>

                <!-- Go Quick -->
                <div class="pricing-content" id="pricing-go-quick">
                    <x-pricing-bot-section :features="$quickFeatures" :packages="$goQuickPackages" :showModal="false" toolType="go-quick" />
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for all tools - placed outside content wrapper -->
    <x-payment.register-modal modalId="registerModal" />
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/pricing.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.pricing-tab');
            const contents = document.querySelectorAll('.pricing-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tool = this.getAttribute('data-tool');

                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Hide all content
                    contents.forEach(content => {
                        content.classList.remove('active');
                    });

                    // Show selected content with animation
                    const targetContent = document.getElementById('pricing-' + tool);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });

            // Handle URL hash to show specific tab
            if (window.location.hash) {
                const hash = window.location.hash.substring(1);
                const tab = document.querySelector(`.pricing-tab[data-tool="${hash}"]`);
                if (tab) {
                    tab.click();
                }
            }
        });
    </script>
@endpush

