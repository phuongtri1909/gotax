@extends('admin.layouts.sidebar')

@section('title', 'Chi tiết cấu hình giảm giá nâng cấp')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.package-upgrade-configs.index') }}">Cấu hình giảm giá nâng cấp</a></li>
                <li class="breadcrumb-item current">Chi tiết</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-percent icon-title"></i>
                    <h5>Chi tiết cấu hình giảm giá nâng cấp</h5>
                </div>
                <div class="card-actions">
                    <a href="{{ route('admin.package-upgrade-configs.edit', $packageUpgradeConfig) }}" class="action-button">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.package-upgrade-configs.index') }}" class="action-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-content">
                <div class="category-details">
                    <div class="detail-section">
                        <div class="detail-item">
                            <label class="detail-label">Tool:</label>
                            <span class="detail-value">
                                <span class="badge bg-primary">{{ $toolNames[$packageUpgradeConfig->tool_type] ?? $packageUpgradeConfig->tool_type }}</span>
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giảm giá lần đầu nâng cấp (tháng đầu):</label>
                            <span class="detail-value">
                                @if($packageUpgradeConfig->first_upgrade_discount_first_month)
                                    <span class="badge bg-primary">{{ number_format($packageUpgradeConfig->first_upgrade_discount_first_month, 2) }}%</span>
                                @else
                                    <span class="text-muted">Chưa cấu hình</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giảm giá lần 2 nâng cấp (tháng đầu):</label>
                            <span class="detail-value">
                                @if($packageUpgradeConfig->second_upgrade_discount_first_month)
                                    <span class="badge bg-primary">{{ number_format($packageUpgradeConfig->second_upgrade_discount_first_month, 2) }}%</span>
                                @else
                                    <span class="text-muted">Chưa cấu hình</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giảm giá lần 3-4-5 nâng cấp (tháng đầu):</label>
                            <span class="detail-value">
                                @if($packageUpgradeConfig->subsequent_upgrade_discount_first_month)
                                    <span class="badge bg-primary">{{ number_format($packageUpgradeConfig->subsequent_upgrade_discount_first_month, 2) }}%</span>
                                @else
                                    <span class="text-muted">Chưa cấu hình</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giảm giá nâng cấp sau tháng đầu:</label>
                            <span class="detail-value">
                                @if($packageUpgradeConfig->upgrade_discount_after_first_month)
                                    <span class="badge bg-info">{{ number_format($packageUpgradeConfig->upgrade_discount_after_first_month, 2) }}%</span>
                                @else
                                    <span class="text-muted">Chưa cấu hình</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giảm giá gia hạn sau khi hết hạn:</label>
                            <span class="detail-value">
                                @if($packageUpgradeConfig->renewal_discount_after_expired)
                                    <span class="badge bg-warning">{{ number_format($packageUpgradeConfig->renewal_discount_after_expired, 2) }}%</span>
                                @else
                                    <span class="text-muted">Chưa cấu hình</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giảm giá cross-product:</label>
                            <span class="detail-value">
                                @if($packageUpgradeConfig->cross_product_discount)
                                    <span class="badge bg-success">{{ number_format($packageUpgradeConfig->cross_product_discount, 2) }}%</span>
                                @else
                                    <span class="text-muted">Chưa cấu hình</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giảm giá lần đầu mua:</label>
                            <span class="detail-value">
                                @if($packageUpgradeConfig->first_purchase_discount)
                                    <span class="badge bg-success">{{ number_format($packageUpgradeConfig->first_purchase_discount, 2) }}%</span>
                                @else
                                    <span class="text-muted">Chưa cấu hình</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Trạng thái:</label>
                            <span class="detail-value">
                                @if ($packageUpgradeConfig->status === 'active')
                                    <span class="status-badge active">Kích hoạt</span>
                                @else
                                    <span class="status-badge inactive">Tắt</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Ngày tạo:</label>
                            <span class="detail-value">{{ $packageUpgradeConfig->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        @if ($packageUpgradeConfig->updated_at != $packageUpgradeConfig->created_at)
                        <div class="detail-item">
                            <label class="detail-label">Cập nhật lần cuối:</label>
                            <span class="detail-value">{{ $packageUpgradeConfig->updated_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .category-details {
            padding: 20px;
        }

        .detail-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .detail-item {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 250px;
            margin-right: 15px;
        }

        .detail-value {
            color: #333;
            flex: 1;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-badge.active {
            background-color: rgba(34, 116, 71, 0.2);
            color: #227447;
        }

        .status-badge.inactive {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .detail-item {
                flex-direction: column;
                gap: 5px;
            }

            .detail-label {
                min-width: auto;
                margin-right: 0;
            }
        }
    </style>
@endpush

