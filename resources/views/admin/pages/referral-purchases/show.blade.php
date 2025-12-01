@extends('admin.layouts.sidebar')

@section('title', 'Chi tiết mã giới thiệu')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.referral-purchases.index') }}">Quản lý mã giới thiệu</a></li>
                <li class="breadcrumb-item current">Chi tiết</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-user-friends icon-title"></i>
                    <h5>Chi tiết mã giới thiệu</h5>
                </div>
                <div class="card-actions">
                    <a href="{{ route('admin.referral-purchases.index') }}" class="action-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-content">
                <div class="category-details">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="detail-section">
                                <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                                    <i class="fas fa-info-circle"></i> Thông tin cơ bản
                                </h6>
                                <div class="detail-item">
                                    <label class="detail-label">ID:</label>
                                    <span class="detail-value">{{ $referralPurchase->id }}</span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Mã giới thiệu:</label>
                                    <span class="detail-value"><strong>{{ $referralPurchase->referral_code }}</strong></span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Tool:</label>
                                    <span class="detail-value">
                                        <span class="badge bg-primary">{{ $toolNames[$referralPurchase->tool_type] ?? $referralPurchase->tool_type }}</span>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Mã giao dịch:</label>
                                    <span class="detail-value"><strong>{{ $referralPurchase->transaction_code }}</strong></span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Số tiền:</label>
                                    <span class="detail-value price-badge">{{ number_format($referralPurchase->amount, 0, ',', '.') }} đ</span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Trạng thái:</label>
                                    <span class="detail-value">
                                        @if ($referralPurchase->status === 'success')
                                            <span class="status-badge active">Thành công</span>
                                        @elseif($referralPurchase->status === 'pending')
                                            <span class="status-badge pending">Chờ xử lý</span>
                                        @elseif($referralPurchase->status === 'failed')
                                            <span class="status-badge inactive">Thất bại</span>
                                        @else
                                            <span class="status-badge cancelled">Đã hủy</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Ngày tạo:</label>
                                    <span class="detail-value">{{ $referralPurchase->created_at->format('d/m/Y H:i:s') }}</span>
                                </div>
                                @if ($referralPurchase->purchase_date)
                                    <div class="detail-item">
                                        <label class="detail-label">Ngày mua:</label>
                                        <span class="detail-value">{{ $referralPurchase->purchase_date->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="detail-section">
                                <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                                    <i class="fas fa-user"></i> Thông tin người giới thiệu
                                </h6>
                                @if($referralPurchase->referrer)
                                    <div class="detail-item">
                                        <label class="detail-label">ID:</label>
                                        <span class="detail-value">
                                            <a href="{{ route('admin.users.show', $referralPurchase->referrer) }}" target="_blank">
                                                {{ $referralPurchase->referrer->id }}
                                            </a>
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <label class="detail-label">Họ và tên:</label>
                                        <span class="detail-value">{{ $referralPurchase->referrer->full_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <label class="detail-label">Email:</label>
                                        <span class="detail-value">{{ $referralPurchase->referrer->email ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <label class="detail-label">Số điện thoại:</label>
                                        <span class="detail-value">{{ $referralPurchase->referrer->phone ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <label class="detail-label">Mã giới thiệu:</label>
                                        <span class="detail-value"><strong>{{ $referralPurchase->referrer->referral_code ?? 'N/A' }}</strong></span>
                                    </div>
                                @else
                                    <div class="detail-item">
                                        <span class="text-muted">Không tìm thấy thông tin người giới thiệu</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="detail-section">
                                <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                                    <i class="fas fa-user-plus"></i> Thông tin người được giới thiệu
                                </h6>
                                @if($referralPurchase->referredUser)
                                    <div class="detail-item">
                                        <label class="detail-label">ID:</label>
                                        <span class="detail-value">
                                            <a href="{{ route('admin.users.show', $referralPurchase->referredUser) }}" target="_blank">
                                                {{ $referralPurchase->referredUser->id }}
                                            </a>
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <label class="detail-label">Họ và tên:</label>
                                        <span class="detail-value">{{ $referralPurchase->referredUser->full_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <label class="detail-label">Email:</label>
                                        <span class="detail-value">{{ $referralPurchase->referredUser->email ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <label class="detail-label">Số điện thoại:</label>
                                        <span class="detail-value">{{ $referralPurchase->referredUser->phone ?? 'N/A' }}</span>
                                    </div>
                                @else
                                    <div class="detail-item">
                                        <span class="text-muted">Người được giới thiệu chưa đăng ký tài khoản</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .status-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-badge.active {
            background-color: rgba(34, 116, 71, 0.2);
            color: #227447;
        }

        .status-badge.pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #856404;
        }

        .status-badge.inactive {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .status-badge.cancelled {
            background-color: rgba(108, 117, 125, 0.2);
            color: #6c757d;
        }

        .price-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
@endpush

