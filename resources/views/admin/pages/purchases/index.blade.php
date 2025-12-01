@extends('admin.layouts.sidebar')

@section('title', 'Quản lý gói đã bán')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Quản lý gói đã bán</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-shopping-cart icon-title"></i>
                    <h5>Quản lý gói đã bán</h5>
                </div>
            </div>

            <div class="card-content">
                <div class="filter-section" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <form method="GET" action="{{ route('admin.purchases.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="tool_type" class="form-label">Tool:</label>
                            <select name="tool_type" id="tool_type" class="form-select">
                                <option value="all" {{ $toolType === 'all' ? 'selected' : '' }}>Tất cả</option>
                                <option value="go-invoice" {{ $toolType === 'go-invoice' ? 'selected' : '' }}>Go Invoice</option>
                                <option value="go-soft" {{ $toolType === 'go-soft' ? 'selected' : '' }}>Go Soft</option>
                                <option value="go-bot" {{ $toolType === 'go-bot' ? 'selected' : '' }}>Go Bot</option>
                                <option value="go-quick" {{ $toolType === 'go-quick' ? 'selected' : '' }}>Go Quick</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái:</label>
                            <select name="status" id="status" class="form-select">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Tất cả</option>
                                <option value="success" {{ $status === 'success' || !request()->has('status') ? 'selected' : '' }}>Thành công</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Thất bại</option>
                                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="per_page" class="form-label">Số lượng/trang:</label>
                            <select name="per_page" id="per_page" class="form-select">
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Lọc
                            </button>
                        </div>
                    </form>
                </div>

                @if ($paginatedPurchases->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h4>Chưa có gói nào được bán</h4>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-medium">Tool</th>
                                    <th class="column-medium">Mã giao dịch</th>
                                    <th class="column-medium">Khách hàng</th>
                                    <th class="column-medium">Gói</th>
                                    <th class="column-small text-center">Số tiền</th>
                                    <th class="column-small text-center">Phí bản quyền</th>
                                    <th class="column-small text-center">Giới hạn</th>
                                    <th class="column-small text-center">Loại</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày tạo</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paginatedPurchases as $index => $purchase)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($paginatedPurchases->currentPage() - 1) * $paginatedPurchases->perPage() + $index + 1 }}
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $toolNames[$purchase->tool_type] ?? $purchase->tool_type }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $purchase->transaction_code }}</strong>
                                        </td>
                                        <td>
                                            @if($purchase->user)
                                                <div>
                                                    <strong>{{ $purchase->user->full_name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $purchase->email ?? 'N/A' }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($purchase->package)
                                                <strong>{{ $purchase->package->name }}</strong>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                            @if(isset($purchase->is_upgrade) && $purchase->is_upgrade)
                                                <br><small class="badge bg-info">Nâng cấp</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="price-badge">{{ number_format($purchase->amount, 0, ',', '.') }} đ</span>
                                        </td>
                                        <td class="text-center">
                                            @if($purchase->tool_type === 'go-invoice' && isset($purchase->license_fee) && $purchase->license_fee)
                                                <span class="fee-badge">{{ number_format($purchase->license_fee, 0, ',', '.') }} đ</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($purchase->tool_type === 'go-quick')
                                                <span class="limit-badge">{{ number_format($purchase->cccd_limit ?? 0) }}</span>
                                            @else
                                                <span class="limit-badge">{{ number_format($purchase->mst_limit ?? 0) }}</span>
                                            @endif
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
                            {{ $paginatedPurchases->total() }} gói đã bán
                        </div>
                        <div class="pagination-controls">
                            {{ $paginatedPurchases->appends(request()->query())->links('components.paginate') }}
                        </div>
                    </div>
                @endif
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

        .limit-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
@endpush

