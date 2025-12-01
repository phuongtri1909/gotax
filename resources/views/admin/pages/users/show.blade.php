@extends('admin.layouts.sidebar')

@section('title', 'Chi tiết người dùng')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Quản lý người dùng</a></li>
                <li class="breadcrumb-item current">Chi tiết</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-user icon-title"></i>
                    <h5>Chi tiết người dùng</h5>
                </div>
                <div class="card-actions">
                    <a href="{{ route('admin.users.index') }}" class="action-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-content">
                <div class="category-details">
                    <div class="detail-section">
                        <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                            <i class="fas fa-info-circle"></i> Thông tin cá nhân
                        </h6>
                        <div class="detail-item">
                            <label class="detail-label">Avatar:</label>
                            <div class="detail-value">
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default/avatar_default.jpg') }}"
                                     alt="{{ $user->full_name ?? 'User' }}"
                                     class="user-avatar-large"
                                     onerror="this.src='{{ asset('images/default/avatar_default.jpg') }}'">
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">ID:</label>
                            <span class="detail-value">{{ $user->id }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Tên đầy đủ:</label>
                            <span class="detail-value">{{ $user->full_name ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Email:</label>
                            <span class="detail-value">
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a>
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Số điện thoại:</label>
                            <span class="detail-value">
                                @if($user->phone)
                                    <a href="tel:{{ str_replace(' ', '', $user->phone) }}" class="text-decoration-none">{{ $user->phone }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Vai trò:</label>
                            <span class="detail-value">
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-primary">User</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Trạng thái:</label>
                            <span class="detail-value">
                                @if ($user->active == 1)
                                    <span class="status-badge active">Kích hoạt</span>
                                @else
                                    <span class="status-badge inactive">Tắt</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Mã giới thiệu:</label>
                            <span class="detail-value">
                                @if($user->referral_code)
                                    <span class="badge bg-info">{{ $user->referral_code }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Ngày tạo:</label>
                            <span class="detail-value">{{ $user->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        @if ($user->updated_at != $user->created_at)
                        <div class="detail-item">
                            <label class="detail-label">Cập nhật lần cuối:</label>
                            <span class="detail-value">{{ $user->updated_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="detail-section" style="margin-top: 30px;">
                        <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                            <i class="fas fa-box"></i> Gói đang sử dụng
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="package-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; background: #fff;">
                                    <h6 style="color: #495057; margin-bottom: 15px;">
                                        <i class="fas fa-file-invoice text-primary"></i> Go Invoice
                                    </h6>
                                    @if($goInvoiceUse && $goInvoiceUse->package)
                                        <div class="package-info">
                                            <p><strong>Gói:</strong> {{ $goInvoiceUse->package->name }}</p>
                                            <p><strong>Giới hạn MST:</strong> <span class="badge bg-info">{{ number_format($goInvoiceUse->mst_limit ?? 0) }}</span></p>
                                            @if($goInvoiceUse->expires_at)
                                                <p><strong>Hạn sử dụng:</strong> 
                                                    @if($goInvoiceUse->expires_at->isFuture())
                                                        <span class="badge bg-success">{{ $goInvoiceUse->expires_at->format('d/m/Y H:i') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">Đã hết hạn ({{ $goInvoiceUse->expires_at->format('d/m/Y H:i') }})</span>
                                                    @endif
                                                </p>
                                            @else
                                                <p><strong>Hạn sử dụng:</strong> <span class="text-muted">Không giới hạn</span></p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted">Chưa có gói</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="package-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; background: #fff;">
                                    <h6 style="color: #495057; margin-bottom: 15px;">
                                        <i class="fas fa-laptop-code text-success"></i> Go Soft
                                    </h6>
                                    @if($goSoftUse && $goSoftUse->package)
                                        <div class="package-info">
                                            <p><strong>Gói:</strong> {{ $goSoftUse->package->name }}</p>
                                            <p><strong>Giới hạn MST:</strong> <span class="badge bg-info">{{ number_format($goSoftUse->mst_limit ?? 0) }}</span></p>
                                            @if($goSoftUse->expires_at)
                                                <p><strong>Hạn sử dụng:</strong> 
                                                    @if($goSoftUse->expires_at->isFuture())
                                                        <span class="badge bg-success">{{ $goSoftUse->expires_at->format('d/m/Y H:i') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">Đã hết hạn ({{ $goSoftUse->expires_at->format('d/m/Y H:i') }})</span>
                                                    @endif
                                                </p>
                                            @else
                                                <p><strong>Hạn sử dụng:</strong> <span class="text-muted">Không giới hạn</span></p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted">Chưa có gói</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="package-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; background: #fff;">
                                    <h6 style="color: #495057; margin-bottom: 15px;">
                                        <i class="fas fa-robot text-warning"></i> Go Bot
                                    </h6>
                                    @if($goBotUse && $goBotUse->package)
                                        <div class="package-info">
                                            <p><strong>Gói:</strong> {{ $goBotUse->package->name }}</p>
                                            <p><strong>Lượt dùng MST còn lại:</strong> <span class="badge bg-primary">{{ number_format($goBotUse->mst_limit ?? 0) }}</span></p>
                                        </div>
                                    @else
                                        <p class="text-muted">Chưa có gói</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="package-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; background: #fff;">
                                    <h6 style="color: #495057; margin-bottom: 15px;">
                                        <i class="fas fa-bolt text-danger"></i> Go Quick
                                    </h6>
                                    @if($goQuickUse && $goQuickUse->package)
                                        <div class="package-info">
                                            <p><strong>Gói:</strong> {{ $goQuickUse->package->name }}</p>
                                            <p><strong>Lượt dùng CCCD còn lại:</strong> <span class="badge bg-primary">{{ number_format($goQuickUse->cccd_limit ?? 0) }}</span></p>
                                            <p><strong>Tổng đã sử dụng:</strong> <span class="badge bg-info">{{ number_format($goQuickUse->total_used ?? 0) }}</span></p>
                                            <p><strong>Tổng CCCD đã trích xuất:</strong> <span class="badge bg-info">{{ number_format($goQuickUse->total_cccd_extracted ?? 0) }}</span></p>
                                        </div>
                                    @else
                                        <p class="text-muted">Chưa có gói</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section" style="margin-top: 30px;">
                        <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                            <i class="fas fa-history"></i> Lịch sử mua gói ({{ $paginatedPurchases->total() }})
                        </h6>
                        
                        @if($paginatedPurchases->isEmpty())
                            <div class="empty-state" style="padding: 40px; text-align: center;">
                                <i class="fas fa-shopping-cart" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                <p class="text-muted">Chưa có lịch sử mua gói</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th class="column-small">STT</th>
                                            <th class="column-medium">Tool</th>
                                            <th class="column-medium">Mã giao dịch</th>
                                            <th class="column-medium">Gói</th>
                                            <th class="column-small text-center">Số tiền</th>
                                            <th class="column-small text-center">Loại</th>
                                            <th class="column-small text-center">Trạng thái</th>
                                            <th class="column-medium">Ngày tạo</th>
                                            <th class="column-small text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($paginatedPurchases as $index => $purchase)
                                            <tr>
                                                <td class="text-center">{{ ($paginatedPurchases->currentPage() - 1) * $paginatedPurchases->perPage() + $index + 1 }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $toolNames[$purchase->tool_type] ?? $purchase->tool_type }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ $purchase->transaction_code }}</strong>
                                                </td>
                                                <td>
                                                    @if($purchase->package)
                                                        <strong>{{ $purchase->package->name }}</strong>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="price-badge">{{ number_format($purchase->amount, 0, ',', '.') }} đ</span>
                                                </td>
                                                <td class="text-center">
                                                    @if(isset($purchase->is_upgrade) && $purchase->is_upgrade)
                                                        <span class="badge bg-warning">Nâng cấp</span>
                                                    @else
                                                        <span class="badge bg-success">Mua mới</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($purchase->status === 'success')
                                                        <span class="status-badge active">Thành công</span>
                                                    @elseif($purchase->status === 'pending')
                                                        <span class="status-badge pending">Chờ xử lý</span>
                                                    @elseif($purchase->status === 'failed')
                                                        <span class="status-badge inactive">Thất bại</span>
                                                    @else
                                                        <span class="status-badge cancelled">Đã hủy</span>
                                                    @endif
                                                </td>
                                                <td class="category-date">{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="action-buttons-wrapper">
                                                        <a href="{{ route('admin.purchases.show', ['toolType' => $purchase->tool_type, 'id' => $purchase->id]) }}"
                                                            class="action-icon view-icon text-decoration-none" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="pagination-wrapper">
                                <div class="pagination-info">
                                    Hiển thị {{ $paginatedPurchases->firstItem() ?? 0 }} đến {{ $paginatedPurchases->lastItem() ?? 0 }} của
                                    {{ $paginatedPurchases->total() }} gói đã mua
                                </div>
                                <div class="pagination-controls">
                                    {{ $paginatedPurchases->links('components.paginate') }}
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

        .status-badge.inactive {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .status-badge.pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #856404;
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

        .package-card {
            height: 100%;
        }

        .package-info p {
            margin-bottom: 10px;
        }

        .user-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dee2e6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

