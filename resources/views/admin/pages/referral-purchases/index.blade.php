@extends('admin.layouts.sidebar')

@section('title', 'Quản lý mã giới thiệu')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Quản lý mã giới thiệu</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-user-friends icon-title"></i>
                    <h5>Quản lý mã giới thiệu</h5>
                </div>
            </div>

            <div class="card-content">
                <div class="filter-section" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <form method="GET" action="{{ route('admin.referral-purchases.index') }}" class="row g-3">
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
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Thành công</option>
                                <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Thất bại</option>
                                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="referral_code" class="form-label">Mã giới thiệu:</label>
                            <input type="text" name="referral_code" id="referral_code" class="form-control" 
                                   value="{{ $referralCode }}" placeholder="Nhập mã giới thiệu">
                        </div>
                        <div class="col-md-3">
                            <label for="per_page" class="form-label">Số lượng/trang:</label>
                            <select name="per_page" id="per_page" class="form-select">
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-12 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Lọc
                            </button>
                            <a href="{{ route('admin.referral-purchases.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                @if ($referrals->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h4>Chưa có mã giới thiệu nào</h4>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-medium">Mã giới thiệu</th>
                                    <th class="column-medium">Người giới thiệu</th>
                                    <th class="column-medium">Người được giới thiệu</th>
                                    <th class="column-medium">Tool</th>
                                    <th class="column-medium">Mã giao dịch</th>
                                    <th class="column-small text-center">Số tiền</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày tạo</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($referrals as $index => $referral)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($referrals->currentPage() - 1) * $referrals->perPage() + $index + 1 }}
                                        </td>
                                        <td>
                                            <strong>{{ $referral->referral_code }}</strong>
                                        </td>
                                        <td>
                                            @if($referral->referrer)
                                                <div>
                                                    <strong>{{ $referral->referrer->full_name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $referral->referrer->email ?? 'N/A' }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($referral->referredUser)
                                                <div>
                                                    <strong>{{ $referral->referredUser->full_name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $referral->referredUser->email ?? 'N/A' }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">Chưa đăng ký</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $toolNames[$referral->tool_type] ?? $referral->tool_type }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $referral->transaction_code }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="price-badge">{{ number_format($referral->amount, 0, ',', '.') }} đ</span>
                                        </td>
                                        <td class="text-center">
                                            @if($referral->status === 'success')
                                                <span class="status-badge active">Thành công</span>
                                            @elseif($referral->status === 'pending')
                                                <span class="status-badge pending">Chờ xử lý</span>
                                            @elseif($referral->status === 'failed')
                                                <span class="status-badge inactive">Thất bại</span>
                                            @else
                                                <span class="status-badge cancelled">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td class="category-date">{{ $referral->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.referral-purchases.show', $referral) }}"
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
                            Hiển thị {{ $referrals->firstItem() ?? 0 }} đến {{ $referrals->lastItem() ?? 0 }} của
                            {{ $referrals->total() }} mã giới thiệu
                        </div>
                        <div class="pagination-controls">
                            {{ $referrals->appends(request()->query())->links('components.paginate') }}
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
    </style>
@endpush

