@extends('admin.layouts.sidebar')

@section('title', 'Chi tiết gói đã bán')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.purchases.index') }}">Quản lý gói đã bán</a></li>
                <li class="breadcrumb-item current">Chi tiết</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-shopping-cart icon-title"></i>
                    <h5>Chi tiết gói đã bán</h5>
                </div>
                <div class="card-actions">
                    <a href="{{ route('admin.purchases.index') }}" class="action-button">
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
                                    <span class="detail-value">{{ $purchase->id }}</span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Tool:</label>
                                    <span class="detail-value">
                                        <span
                                            class="badge bg-primary">{{ $toolNames[$purchase->tool_type] ?? $purchase->tool_type }}</span>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Mã giao dịch:</label>
                                    <span class="detail-value"><strong>{{ $purchase->transaction_code }}</strong></span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Trạng thái:</label>
                                    <span class="detail-value">
                                        @if ($purchase->status === 'success')
                                            <span class="status-badge active">Thành công</span>
                                        @elseif($purchase->status === 'pending')
                                            <span class="status-badge pending">Chờ xử lý</span>
                                        @elseif($purchase->status === 'failed')
                                            <span class="status-badge inactive">Thất bại</span>
                                        @else
                                            <span class="status-badge cancelled">Đã hủy</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Loại:</label>
                                    <span class="detail-value">
                                        @if (isset($purchase->is_upgrade) && $purchase->is_upgrade)
                                            <span class="badge bg-warning">Nâng cấp</span>
                                        @else
                                            <span class="badge bg-success">Mua mới</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Hạn tool:</label>
                                    <span class="detail-value">
                                        ({{ \Carbon\Carbon::parse($purchase->expires_tool)->format('d/m/Y H:i') }})</span>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Ngày tạo:</label>
                                    <span class="detail-value">{{ $purchase->created_at->format('d/m/Y H:i:s') }}</span>
                                </div>
                                @if ($purchase->updated_at && $purchase->updated_at != $purchase->created_at)
                                    <div class="detail-item">
                                        <label class="detail-label">Cập nhật lần cuối:</label>
                                        <span class="detail-value">{{ $purchase->updated_at->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                @endif
                                @if ($purchase->processed_at)
                                    <div class="detail-item">
                                        <label class="detail-label">Ngày xử lý:</label>
                                        <span
                                            class="detail-value">{{ \Carbon\Carbon::parse($purchase->processed_at)->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="detail-section">
                                <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                                    <i class="fas fa-user"></i> Thông tin khách hàng
                                </h6>

                                @if ($purchase->user)
                                    <div
                                        style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #dee2e6;">
                                        <h6 style="margin-bottom: 15px; color: #6c757d; font-weight: 600; font-size: 14px;">
                                            <i class="fas fa-user-circle"></i> Thông tin tài khoản
                                        </h6>
                                        <div class="detail-item">
                                            <label class="detail-label">User ID:</label>
                                            <span class="detail-value">
                                                <a href="{{ route('admin.users.show', $purchase->user) }}"
                                                    class="text-decoration-none">
                                                    #{{ $purchase->user->id }}
                                                </a>
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <label class="detail-label">Tên đầy đủ:</label>
                                            <span class="detail-value">
                                                <a href="{{ route('admin.users.show', $purchase->user) }}"
                                                    class="text-decoration-none">
                                                    {{ $purchase->user->full_name ?? 'N/A' }}
                                                </a>
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <label class="detail-label">Email:</label>
                                            <span class="detail-value">
                                                <a href="mailto:{{ $purchase->user->email }}"
                                                    class="text-decoration-none">{{ $purchase->user->email }}</a>
                                            </span>
                                        </div>
                                        @if ($purchase->user->phone)
                                            <div class="detail-item">
                                                <label class="detail-label">Số điện thoại:</label>
                                                <span class="detail-value">
                                                    <a href="tel:{{ str_replace(' ', '', $purchase->user->phone) }}"
                                                        class="text-decoration-none">{{ $purchase->user->phone }}</a>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div>
                                    <h6 style="margin-bottom: 15px; color: #6c757d; font-weight: 600; font-size: 14px;">
                                        <i class="fas fa-shopping-cart"></i> Thông tin đặt hàng
                                    </h6>
                                    <div class="detail-item">
                                        <label class="detail-label">Tên đầy đủ:</label>
                                        <span class="detail-value">
                                            {{ $purchase->first_name ?? '' }} {{ $purchase->last_name ?? '' }}
                                            @if (!$purchase->first_name && !$purchase->last_name)
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <label class="detail-label">Email:</label>
                                        <span class="detail-value">
                                            @if ($purchase->email)
                                                <a href="mailto:{{ $purchase->email }}"
                                                    class="text-decoration-none">{{ $purchase->email }}</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <label class="detail-label">Số điện thoại:</label>
                                        <span class="detail-value">
                                            @if ($purchase->phone)
                                                <a href="tel:{{ str_replace(' ', '', $purchase->phone) }}"
                                                    class="text-decoration-none">{{ $purchase->phone }}</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </span>
                                    </div>
                                    @if ($purchase->vat_mst || $purchase->vat_company || $purchase->vat_address)
                                        <div class="detail-item">
                                            <label class="detail-label">MST:</label>
                                            <span class="detail-value">{{ $purchase->vat_mst ?? 'N/A' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label class="detail-label">Tên công ty:</label>
                                            <span class="detail-value">{{ $purchase->vat_company ?? 'N/A' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label class="detail-label">Địa chỉ công ty:</label>
                                            <span class="detail-value">{{ $purchase->vat_address ?? 'N/A' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="detail-section">
                                <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                                    <i class="fas fa-box"></i> Thông tin gói
                                </h6>
                                <div class="detail-item">
                                    <label class="detail-label">Gói:</label>
                                    <span class="detail-value">
                                        @if ($purchase->package)
                                            <strong>{{ $purchase->package->name }}</strong>
                                            @if ($purchase->package->id)
                                                <br><small class="text-muted">ID: {{ $purchase->package->id }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </span>
                                </div>
                                @if (isset($purchase->is_upgrade) && $purchase->is_upgrade && $purchase->oldPackage)
                                    <div class="detail-item">
                                        <label class="detail-label">Gói cũ:</label>
                                        <span class="detail-value">
                                            <strong>{{ $purchase->oldPackage->name }}</strong>
                                            @if ($purchase->oldPackage->id)
                                                <br><small class="text-muted">ID: {{ $purchase->oldPackage->id }}</small>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                                <div class="detail-item">
                                    <label class="detail-label">Package ID:</label>
                                    <span class="detail-value">{{ $purchase->package_id ?? 'N/A' }}</span>
                                </div>
                                @if (isset($purchase->old_package_id) && $purchase->old_package_id)
                                    <div class="detail-item">
                                        <label class="detail-label">Old Package ID:</label>
                                        <span class="detail-value">{{ $purchase->old_package_id }}</span>
                                    </div>
                                @endif
                                <div class="detail-item">
                                    <label class="detail-label">Giá gốc:</label>
                                    <span class="detail-value">
                                        @php
                                            $originalPrice = $purchase->amount;
                                            if ($purchase->discount_amount) {
                                                $originalPrice = $purchase->amount + $purchase->discount_amount;
                                            } elseif (isset($purchase->upgradeHistory) && $purchase->upgradeHistory) {
                                                $originalPrice = $purchase->upgradeHistory->new_package_price ?? $purchase->amount;
                                            } elseif ($purchase->package) {
                                                $originalPrice = $purchase->package->price;
                                            }
                                        @endphp
                                        <span class="price-badge">{{ number_format($originalPrice, 0, ',', '.') }} đ</span>
                                    </span>
                                </div>
                                @if ($purchase->discount_percent || $purchase->discount_amount)
                                <div class="detail-item">
                                    <label class="detail-label">% Giảm giá:</label>
                                    <span class="detail-value">
                                        <span class="badge bg-info">{{ number_format($purchase->discount_percent ?? 0, 2) }}%</span>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Số tiền giảm:</label>
                                    <span class="detail-value">
                                        <span class="badge bg-success">{{ number_format($purchase->discount_amount ?? 0, 0, ',', '.') }} đ</span>
                                    </span>
                                </div>
                                @endif
                                <div class="detail-item">
                                    <label class="detail-label">Số tiền thanh toán:</label>
                                    <span class="detail-value">
                                        <span class="price-badge">{{ number_format($purchase->amount, 0, ',', '.') }}
                                            đ</span>
                                    </span>
                                </div>
                                @if (isset($purchase->license_fee) && $purchase->license_fee)
                                    <div class="detail-item">
                                        <label class="detail-label">Phí bản quyền:</label>
                                        <span class="detail-value">
                                            <span
                                                class="price-badge">{{ number_format($purchase->license_fee, 0, ',', '.') }}
                                                đ</span>
                                        </span>
                                    </div>
                                @endif
                                @if ($purchase->tool_type === 'go-quick')
                                    <div class="detail-item">
                                        <label class="detail-label">Giới hạn CCCD:</label>
                                        <span class="detail-value">
                                            <span
                                                class="limit-badge">{{ number_format($purchase->cccd_limit ?? 0) }}</span>
                                        </span>
                                    </div>
                                @else
                                    <div class="detail-item">
                                        <label class="detail-label">Giới hạn MST:</label>
                                        <span class="detail-value">
                                            <span
                                                class="limit-badge">{{ number_format($purchase->mst_limit ?? 0) }}</span>
                                        </span>
                                    </div>
                                @endif

                                @if (in_array($purchase->tool_type, ['go-invoice', 'go-soft']))
                                    <div class="detail-item">
                                        <label class="detail-label">Hạn tool:</label>
                                        <span class="detail-value">
                                            @if ($purchase->expires_tool)
                                                @if (\Carbon\Carbon::parse($purchase->expires_tool)->isFuture())
                                                    <span
                                                        class="badge bg-success">{{ \Carbon\Carbon::parse($purchase->expires_tool)->format('d/m/Y H:i') }}</span>
                                                @else
                                                    <span class="badge bg-danger">Đã hết hạn
                                                        ({{ \Carbon\Carbon::parse($purchase->expires_tool)->format('d/m/Y H:i') }})</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Chưa có</span>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="detail-section">
                                <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                                    <i class="fas fa-university"></i> Thông tin thanh toán
                                </h6>
                                <div class="detail-item">
                                    <label class="detail-label">Bank ID:</label>
                                    <span class="detail-value">{{ $purchase->bank_id ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Ngân hàng:</label>
                                    <span class="detail-value">
                                        @if ($purchase->bank)
                                            <strong>{{ $purchase->bank->name }}</strong><br>
                                            <small class="text-muted">STK: {{ $purchase->bank->account_number }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </span>
                                </div>
                                @if ($purchase->casso_transaction_id)
                                    <div class="detail-item">
                                        <label class="detail-label">Casso Transaction ID:</label>
                                        <span
                                            class="detail-value"><code>{{ $purchase->casso_transaction_id }}</code></span>
                                    </div>
                                @endif
                                @if ($purchase->casso_response)
                                    <div class="detail-item">
                                        <label class="detail-label">Casso Response:</label>
                                        <div class="detail-value">
                                            <div
                                                style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary-color); max-height: 200px; overflow-y: auto;">
                                                <pre style="margin: 0; white-space: pre-wrap; word-wrap: break-word; font-size: 12px;">{{ json_encode(json_decode($purchase->casso_response), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($purchase->note)
                                    <div class="detail-item">
                                        <label class="detail-label">Ghi chú:</label>
                                        <div class="detail-value">
                                            <div
                                                style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary-color); white-space: pre-wrap;">
                                                {{ $purchase->note }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if (isset($purchase->is_upgrade) && $purchase->is_upgrade && $purchase->upgradeHistory)
                            <div class="col-md-12 mb-3">
                                <div class="detail-section">
                                    <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                                        <i class="fas fa-arrow-up"></i> Thông tin nâng cấp
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <label class="detail-label">Upgrade History ID:</label>
                                                <span
                                                    class="detail-value">{{ $purchase->upgrade_history_id ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label class="detail-label">Giá gói cũ:</label>
                                                <span class="detail-value">
                                                    <span
                                                        class="price-badge">{{ number_format($purchase->upgradeHistory->old_package_price ?? 0, 0, ',', '.') }}
                                                        đ</span>
                                                </span>
                                            </div>
                                            <div class="detail-item">
                                                <label class="detail-label">Giá gói mới:</label>
                                                <span class="detail-value">
                                                    <span
                                                        class="price-badge">{{ number_format($purchase->upgradeHistory->new_package_price ?? 0, 0, ',', '.') }}
                                                        đ</span>
                                                </span>
                                            </div>
                                            <div class="detail-item">
                                                <label class="detail-label">Chênh lệch giá:</label>
                                                <span class="detail-value">
                                                    <span
                                                        class="price-badge">{{ number_format($purchase->upgradeHistory->price_difference ?? 0, 0, ',', '.') }}
                                                        đ</span>
                                                </span>
                                            </div>
                                            @if ($purchase->upgradeHistory->discount_percent)
                                                <div class="detail-item">
                                                    <label class="detail-label">% Giảm giá:</label>
                                                    <span class="detail-value">
                                                        <span
                                                            class="badge bg-info">{{ number_format($purchase->upgradeHistory->discount_percent, 2) }}%</span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if ($purchase->upgradeHistory->discount_amount)
                                                <div class="detail-item">
                                                    <label class="detail-label">Số tiền giảm:</label>
                                                    <span class="detail-value">
                                                        <span
                                                            class="badge bg-success">{{ number_format($purchase->upgradeHistory->discount_amount, 0, ',', '.') }}
                                                            đ</span>
                                                    </span>
                                                </div>
                                            @endif
                                            @if ($purchase->upgradeHistory->final_amount)
                                                <div class="detail-item">
                                                    <label class="detail-label">Số tiền cuối cùng:</label>
                                                    <span class="detail-value">
                                                        <span
                                                            class="price-badge">{{ number_format($purchase->upgradeHistory->final_amount, 0, ',', '.') }}
                                                            đ</span>
                                                    </span>
                                                </div>
                                            @endif
                                            @if (isset($purchase->upgradeHistory->keep_current_expires) && $purchase->upgradeHistory->keep_current_expires)
                                                <div class="detail-item">
                                                    <label class="detail-label">Giữ hạn hiện tại:</label>
                                                    <span class="detail-value">
                                                        <span class="badge bg-info">Có</span>
                                                    </span>
                                                </div>
                                            @endif
                                            @if ($purchase->upgradeHistory->created_at)
                                                <div class="detail-item">
                                                    <label class="detail-label">Ngày tạo upgrade:</label>
                                                    <span
                                                        class="detail-value">{{ $purchase->upgradeHistory->created_at->format('d/m/Y H:i:s') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
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
            min-width: 180px;
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

        .limit-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
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
