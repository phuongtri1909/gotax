@extends('client.layouts.app')
@section('title', 'Go Soft - Tải Tờ Khai Hàng Loạt')
@section('description', 'Go Soft - Tải Tờ Khai Hàng Loạt')
@section('keyword', 'Go Soft, Tờ Khai')

@section('content')
    <section class="go-soft-section">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-4 go-soft-sidebar">
                    <div class="sidebar-header">
                        <h1 class="sidebar-title">Go Soft</h1>
                        <p class="sidebar-subtitle">Tải Tờ Khai Hàng Loạt</p>
                    </div>
                    
                    <nav class="sidebar-menu">
                        <a href="{{ route('tools.go-soft.overview') }}" class="sidebar-menu-item {{ request()->routeIs('tools.go-soft.overview') ? 'active' : '' }}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <rect x="3" y="3" width="6" height="6" stroke="currentColor" stroke-width="2"/>
                                <rect x="11" y="3" width="6" height="6" stroke="currentColor" stroke-width="2"/>
                                <rect x="3" y="11" width="6" height="6" stroke="currentColor" stroke-width="2"/>
                                <rect x="11" y="11" width="6" height="6" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span>Tổng Quan</span>
                        </a>
                        <a href="{{ route('tools.go-soft.xml-to-excel') }}" class="sidebar-menu-item {{ request()->routeIs('tools.go-soft.xml-to-excel') ? 'active' : '' }}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M13 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V16C4 16.5304 4.21071 17.0391 4.58579 17.4142C4.96086 17.7893 5.46957 18 6 18H14C14.5304 18 15.0391 17.7893 15.4142 17.4142C15.7893 17.0391 16 16.5304 16 16V6L13 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13 2V6H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Chuyển XML → Excel</span>
                        </a>
                        <a href="{{ route('tools.go-soft.storage') }}" class="sidebar-menu-item {{ request()->routeIs('tools.go-soft.storage') ? 'active' : '' }}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M13 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V16C4 16.5304 4.21071 17.0391 4.58579 17.4142C4.96086 17.7893 5.46957 18 6 18H14C14.5304 18 15.0391 17.7893 15.4142 17.4142C15.7893 17.0391 16 16.5304 16 16V6L13 2Z" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span>Lưu trữ</span>
                        </a>
                        <a href="{{ route('tools.go-soft.support') }}" class="sidebar-menu-item {{ request()->routeIs('tools.go-soft.support') ? 'active' : '' }}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2"/>
                                <path d="M10 6V10M10 14H10.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>Hỗ trợ</span>
                        </a>
                        <a href="#" class="sidebar-menu-item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M16 6H13M10 3V6M10 6V9M10 6H13M10 6H7M7 6H4M7 6V3M7 6V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>Thêm công ty</span>
                        </a>
                        <a href="#" class="sidebar-menu-item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M13 14L17 10M17 10L13 6M17 10H7M7 17H5C4.46957 17 3.96086 16.7893 3.58579 16.4142C3.21071 16.0391 3 15.5304 3 15V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Đăng xuất</span>
                        </a>
                    </nav>
                    
                    <div class="sidebar-back">
                        <a href="{{ route('tools.go-soft') }}" class="btn btn-back-sidebar">
                            <img src="{{ asset('images/svg/arrow-left.svg') }}" alt="">
                            <span>Trở lại</span>
                        </a>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="col-lg-9 col-md-8 go-soft-content">
                    @yield('go-soft-content')
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/tools/go-soft.css')
@endpush



