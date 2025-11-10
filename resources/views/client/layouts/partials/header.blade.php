<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#ffffff">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="vi_VN">

    {!! SEO::generate() !!}

    @stack('custom_schema')

    <link rel="icon" href="{{ $faviconPath }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $faviconPath }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ $faviconPath }}">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta name="google-site-verification" content="pQiP1ejMlkYnhemJmmmDhBPesa7pbMtjNjZTSZBaksM" />
    @verbatim
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('/images/d/Thumbnail.png') }}"
        }
        </script>
    @endverbatim

    @stack('meta')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->

    {{-- styles --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite('resources/assets/frontend/css/styles.css')
    @vite('resources/assets/frontend/css/styles-header.css')
    @vite('resources/assets/frontend/css/components/footer.css')

    @stack('styles')
    {{-- end styles --}}
</head>

<body>
    <header class="header-main py-3 py-lg-0" id="header">
        <div class="container">
            <div class="header-custom">
                <div class="header-logo">
                    <a href="{{ route('home') }}" class="logo-link">
                        <img src="{{ $logoPath }}" alt="Logo" height="53px">
                    </a>
                </div>

                <nav class="header-nav d-none d-lg-flex">
                    <a href="{{ route('home') }}"
                        class="nav-link {{ Route::currentRouteNamed('home') ? 'active' : '' }}">Trang chủ</a>
                    <div class="nav-dropdown">
                        <a href="#" class="nav-link {{ Route::currentRouteNamed('tools.*') ? 'active' : '' }}" id="toolsDropdownBtn">
                            Công cụ
                            <i class="fas fa-chevron-down ms-1" style="font-size: 10px;"></i>
                        </a>
                        <div class="nav-dropdown-menu" id="toolsDropdown">
                            <a href="{{ route('tools.go-invoice') }}" class="nav-dropdown-item {{ Route::currentRouteNamed('tools.go-invoice') ? 'active' : '' }}">
                                Go Invoice
                            </a>
                            <a href="{{ route('tools.go-bot') }}" class="nav-dropdown-item {{ Route::currentRouteNamed('tools.go-bot') ? 'active' : '' }}">
                                Go Bot
                            </a>
                            <a href="{{ route('tools.go-soft') }}" class="nav-dropdown-item {{ Route::currentRouteNamed('tools.go-soft') ? 'active' : '' }}">
                                Go Soft
                            </a>
                            <a href="{{ route('tools.go-quick') }}" class="nav-dropdown-item {{ Route::currentRouteNamed('tools.go-quick') ? 'active' : '' }}">
                                Go Quick
                            </a>
                        </div>
                    </div>
                    <a href="#" class="nav-link">Bảng giá</a>
                    <a href="#" class="nav-link">Tài liệu</a>
                    <a href="{{ route('contact') }}" class="nav-link">Liên hệ</a>
                </nav>

                <div class="user-section">
                    <button class="search-btn-circle" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>

                    @guest
                        <a href="#" class="btn-register fw-semibold">Đăng ký</a>

                        <a href="{{ route('login') }}" class="btn-login">
                            Đăng Nhập
                        </a>
                    @endguest

                    @auth
                        <div class="user-dropdown">
                            <button class="notification-btn" id="notificationBtn">
                                <img src="{{ asset('/images/svg/notification.svg') }}" alt="Notification">
                                <span class="notification-badge" id="notificationBadge">0</span>
                            </button>

                            <div class="notification-dropdown" id="notificationDropdown">
                                <div class="notification-header">
                                    <h6 class="notification-title">Thông báo xu</h6>
                                    <button class="mark-all-read-btn" id="markAllReadBtn">
                                        <i class="fas fa-check-double me-1"></i>
                                        Đọc tất cả
                                    </button>
                                </div>

                                <div class="notification-list" id="notificationList">
                                    <div class="notification-loading">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        Đang tải...
                                    </div>
                                </div>

                                <div class="notification-footer">
                                    <a href="" class="notification-view-all">
                                        <i class="fas fa-history me-1"></i>
                                        Xem tất cả
                                    </a>
                                </div>
                            </div>

                            <button class="user-profile-btn p-0" id="userDropdownBtn">
                                <div class="user-avatar-container">
                                    @if (auth()->user() && auth()->user()->avatar)
                                        <img class="avatar" src="{{ Storage::url(auth()->user()->avatar) }}"
                                            alt="User">
                                    @else
                                        <img class="avatar-default"
                                            src="{{ asset('/images/default/avatar_default.jpg') }}" alt="User">
                                    @endif
                                </div>
                            </button>

                            <div class="user-dropdown-menu" id="userDropdown">
                                <div class="p-4 pb-2">
                                    <div class="user-info">
                                        <a href="{{ route('profile') }}" class="user-details text-decoration-none">
                                            <h6 class="user-name fw-semibold text-lg">{{ auth()->user()->full_name }}</h6>
                                            <p class="user-email text-md">{{ auth()->user()->email }}</p>
                                        </a>
                                    </div>

                                    <hr class="mt-3 mb-0 divider-header">

                                    <ul class="user-menu text-md">
                                        @if (auth()->user())
                                            <li>
                                                <a href="">
                                                    <span class="text-lg">Quản trị viên</span>
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a href="">
                                                <span class="text-lg">Gói đăng ký</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="">
                                                <span class="text-lg">Lịch sử</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="">
                                                <span class="text-lg">Trợ giúp</span>
                                            </a>
                                        </li>
                                        <hr class="my-0 divider-header">
                                        <li>
                                            <a href="">
                                                <span class="text-lg">Thông báo</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="">
                                                <span class="text-lg">Thiết lập tài khoản</span>
                                            </a>
                                        </li>
                                        <hr class="my-0 divider-header">
                                        <li>
                                            <a href="{{ route('logout') }}">
                                                <span class="color-primary-4 text-lg">Đăng xuất</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>

                <div class="d-lg-none">
                    <button class="btn border rounded-circle bg-white" id="mobileMenuToggle"
                        style="width: 45px; height: 45px;">
                        <img src="{{ asset('/images/svg/menu.svg') }}" alt="">
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="mobile-side-menu-overlay" id="mobileMenuOverlay"></div>

    <div class="mobile-side-menu" id="mobileSideMenu">
        <div class="mobile-menu-header">
            <a href="{{ route('home') }}" class="logo-link">
                <img src="{{ $logoPath }}" alt="Logo">
            </a>
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <ul class="mobile-nav-list">
            <li><a href="{{ route('home') }}"
                    class="text-md fw-medium {{ Route::currentRouteNamed('home') ? 'active' : '' }}">Trang chủ</a>
            </li>
            <li>
                <a href="#" class="text-lg fw-medium mobile-nav-dropdown-toggle" id="mobileToolsToggle">
                    Công cụ
                    <i class="fas fa-chevron-down ms-2" style="font-size: 12px;"></i>
                </a>
                <ul class="mobile-nav-dropdown" id="mobileToolsDropdown">
                    <li><a href="{{ route('tools.go-invoice') }}" class="text-md">Go Invoice</a></li>
                    <li><a href="{{ route('tools.go-bot') }}" class="text-md">Go Bot</a></li>
                    <li><a href="{{ route('tools.go-soft') }}" class="text-md">Go Soft</a></li>
                    <li><a href="{{ route('tools.go-quick') }}" class="text-md">Go Quick</a></li>
                </ul>
            </li>
            <li><a href="#" class="text-lg fw-medium">Bảng giá</a></li>
            <li><a href="#" class="text-lg fw-medium">Tài liệu</a></li>
            <li><a href="{{ route('contact') }}" class="text-lg fw-medium">Liên hệ</a></li>
        </ul>

        @guest
            <div class="mobile-actions">
                <a href="#" class="btn-register-mobile">Đăng ký</a>
                <a href="{{ route('login') }}" class="btn-login-mobile">Đăng Nhập</a>
            </div>
        @endguest
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileSideMenu = document.getElementById('mobileSideMenu');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const mobileMenuClose = document.getElementById('mobileMenuClose');

            // Tools Dropdown
            const toolsDropdownBtn = document.getElementById('toolsDropdownBtn');
            const toolsDropdown = document.getElementById('toolsDropdown');

            if (toolsDropdownBtn && toolsDropdown) {
                toolsDropdownBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toolsDropdown.classList.toggle('active');
                });

                document.addEventListener('click', function(e) {
                    if (!toolsDropdownBtn.contains(e.target) && !toolsDropdown.contains(e.target)) {
                        toolsDropdown.classList.remove('active');
                    }
                });
            }

            // User Dropdown
            const userDropdownBtn = document.getElementById('userDropdownBtn');
            const userDropdown = document.getElementById('userDropdown');

            mobileMenuToggle.addEventListener('click', function() {
                mobileSideMenu.classList.add('active');
                mobileMenuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            function closeMobileMenu() {
                mobileSideMenu.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            mobileMenuClose.addEventListener('click', closeMobileMenu);
            mobileMenuOverlay.addEventListener('click', closeMobileMenu);

            // Mobile Tools Dropdown
            const mobileToolsToggle = document.getElementById('mobileToolsToggle');
            const mobileToolsDropdown = document.getElementById('mobileToolsDropdown');

            if (mobileToolsToggle && mobileToolsDropdown) {
                mobileToolsToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    mobileToolsDropdown.classList.toggle('active');
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-chevron-down');
                        icon.classList.toggle('fa-chevron-up');
                    }
                });
            }

            const mobileNavLinks = document.querySelectorAll('.mobile-nav-list > li > a:not(.mobile-nav-dropdown-toggle), .mobile-nav-dropdown a');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });

            if (userDropdownBtn && userDropdown) {
                userDropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('active');
                });
            }

            document.addEventListener('click', function(e) {
                if (userDropdownBtn && userDropdown && !userDropdownBtn.contains(e.target) && !userDropdown
                    .contains(e.target)) {
                    userDropdown.classList.remove('active');
                }
            });

            @auth
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationList = document.getElementById('notificationList');
            const notificationBadge = document.getElementById('notificationBadge');
            const markAllReadBtn = document.getElementById('markAllReadBtn');

            if (notificationBtn && notificationDropdown) {
                notificationBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notificationDropdown.classList.toggle('active');

                    if (notificationDropdown.classList.contains('active')) {
                        loadNotifications();
                    }
                });
            }

            document.addEventListener('click', function(e) {
                if (notificationBtn && notificationDropdown && !notificationBtn.contains(e.target) && !
                    notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.remove('active');
                }
            });

            function loadNotifications() {
                if (!notificationList) return;

               
            }

            function displayNotifications(histories) {
                if (!notificationList) return;

                if (histories.length === 0) {
                    notificationList.innerHTML =
                        '<div class="notification-empty"><i class="fas fa-bell-slash"></i>Không có thông báo chưa đọc</div>';
                    return;
                }

                let html = '';
                histories.forEach(history => {
                    const iconClass = getNotificationIcon(history.type);
                    const amountClass = history.amount > 0 ? 'positive' : 'negative';

                    html += `
                        <div class="notification-item unread" data-history-id="${history.id}">
                            <div class="notification-icon ${history.type}">
                                <i class="fas ${iconClass}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title-text">${history.reason}</div>
                                ${history.description ? `<div class="notification-desc">${history.description}</div>` : ''}
                                <div class="notification-meta">
                                    <span>${formatDate(history.created_at)}</span>
                                    <span class="notification-amount ${amountClass}">${history.amount > 0 ? '+' : ''}${formatNumber(history.amount)} xu</span>
                                </div>
                            </div>
                        </div>
                    `;
                });

                notificationList.innerHTML = html;

                document.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const historyId = this.dataset.historyId;
                        markAsRead(historyId);
                    });
                });
            }

            function markAsRead(historyId) {
                
            }

            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function(e) {
                    e.stopPropagation();

                    
                });
            }

            function updateNotificationCount() {
                if (!notificationBadge) return;

                
            }

            function getNotificationIcon(type) {
                switch (type) {
                    case 'payment':
                        return 'fa-credit-card';
                    case 'purchase':
                        return 'fa-shopping-cart';
                    case 'manual':
                        return 'fa-user-cog';
                    case 'monthly_bonus':
                        return 'fa-gift';
                    default:
                        return 'fa-coins';
                }
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diff = now - date;
                const minutes = Math.floor(diff / 60000);
                const hours = Math.floor(diff / 3600000);
                const days = Math.floor(diff / 86400000);

                if (minutes < 1) return 'Vừa xong';
                if (minutes < 60) return `${minutes} phút trước`;
                if (hours < 24) return `${hours} giờ trước`;
                if (days < 7) return `${days} ngày trước`;

                return date.toLocaleDateString('vi-VN');
            }

            function formatNumber(num) {
                return new Intl.NumberFormat('vi-VN').format(num);
            }

            updateNotificationCount();
        @endauth
        });
    </script>
