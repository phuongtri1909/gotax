@extends('client.layouts.app')
@section('title', 'Go Soft - Tải Tờ Khai Hàng Loạt')
@section('description', 'Go Soft - Tải Tờ Khai Hàng Loạt')
@section('keyword', 'Go Soft, Tờ Khai')

@section('content')
    <section class="go-soft-section">
        <div class="container">
            <div class="sidebar-header text-center mb-3">
                <h1 class="sidebar-title">Go Soft</h1>
                <p class="sidebar-subtitle">Tải Tờ Khai Hàng Loạt</p>
            </div>
            <div class="row" id="goSoftMainRow">

                <!-- Sidebar -->
                <div class="col-lg-3 col-md-4 go-soft-sidebar">

                    <nav class="sidebar-menu">
                        <a href="{{ route('tools.go-soft.overview') }}"
                            class="sidebar-menu-item {{ request()->routeIs('tools.go-soft.overview') ? 'active' : '' }}">
                            <img src="{{ asset('images/d/go-soft/overview.svg') }}" alt="">
                            <span>Tổng Quan</span>
                        </a>
                        <a href="{{ route('tools.go-soft.xml-to-excel') }}"
                            class="sidebar-menu-item {{ request()->routeIs('tools.go-soft.xml-to-excel') ? 'active' : '' }}">
                            <img src="{{ asset('images/d/go-soft/chuyen.svg') }}" alt="">
                            <span>Chuyển XML → Excel</span>
                        </a>
                        <a href="{{ route('tools.go-soft.storage') }}"
                            class="sidebar-menu-item {{ request()->routeIs('tools.go-soft.storage') ? 'active' : '' }}">
                            <img src="{{ asset('images/d/go-soft/folder.svg') }}" alt="">
                            <span>Lưu trữ</span>
                        </a>
                        <a href="{{ route('tools.go-soft.support') }}"
                            class="sidebar-menu-item {{ request()->routeIs('tools.go-soft.support') ? 'active' : '' }}">
                            <img src="{{ asset('images/d/go-soft/support.svg') }}" alt="">
                            <span>Hỗ trợ</span>
                        </a>
                        <a href="#" class="sidebar-menu-item">
                            <img src="{{ asset('images/d/go-soft/company.svg') }}" alt="">
                            <span>Thêm công ty</span>
                        </a>
                        <a href="#" class="sidebar-menu-item" id="goSoftLogoutBtn">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path
                                    d="M13 14L17 10M17 10L13 6M17 10H7M7 17H5C4.46957 17 3.96086 16.7893 3.58579 16.4142C3.21071 16.0391 3 15.5304 3 15V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H7"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Đăng xuất</span>
                        </a>
                    </nav>


                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-md-8 go-soft-content">

                    @yield('go-soft-content')
                </div>

                <div class="sidebar-back mt-3">
                    <a href="{{ route('tools.go-soft') }}" class="btn btn-back-sidebar">
                        <img src="{{ asset('images/svg/arrow-left.svg') }}" alt="">
                        <span>Trở lại</span>
                    </a>
                </div>
            </div>

            <!-- Download Section (sẽ thay thế row khi có kết quả) -->
            @yield('go-soft-download')
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-soft.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('goSoftLogoutBtn');
            
            if (logoutBtn) {
                logoutBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    
                    const sessionId = localStorage.getItem('goSoftSessionId');
                    
                    if (sessionId) {
                        try {
                            const response = await fetch('{{ route("tools.go-soft.session.close") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    session_id: sessionId
                                })
                            });
                            
                            if (response.ok) {
                                const result = await response.json();
                                if (result.status === 'success') {
                                    console.log('Session closed successfully');
                                }
                            }
                        } catch (error) {
                            console.error('Error closing session:', error);
                        }
                    }
                    
                    localStorage.removeItem('goSoftSessionId');
                    localStorage.removeItem('goSoftLoggedIn');
                    localStorage.removeItem('goSoftDownloadData');
                    localStorage.removeItem('goSoftSelectedCompany');
                    
                    window.location.href = '{{ route("tools.go-soft.overview") }}';
                });
            }
        });
    </script>
@endpush
