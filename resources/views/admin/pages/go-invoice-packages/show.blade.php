@extends('admin.layouts.sidebar')

@section('title', 'Chi tiết gói GoInvoice')

@section('main-content')
    <div class="category-container">
        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-box icon-title"></i>
                    <h5>Chi tiết gói GoInvoice</h5>
                </div>
                <div class="card-actions">
                    <a href="{{ route('admin.go-invoice-packages.edit', $goInvoicePackage) }}" class="action-button">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>

                    <a href="{{ route('admin.go-invoice-packages.index') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-content">
                <div class="category-details">
                    <div class="detail-section">
                        <div class="detail-item">
                            <label class="detail-label">ID:</label>
                            <span class="detail-value">{{ $goInvoicePackage->id }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Tên gói:</label>
                            <span class="detail-value">{{ $goInvoicePackage->name }}</span>
                            @if($goInvoicePackage->badge)
                                <span class="badge-badge">{{ $goInvoicePackage->badge }}</span>
                            @endif
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Slug:</label>
                            <span class="detail-value">{{ $goInvoicePackage->slug }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giá:</label>
                            <span class="detail-value price-badge">{{ number_format($goInvoicePackage->price, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giới hạn MST:</label>
                            <span class="detail-value limit-badge">{{ number_format($goInvoicePackage->mst_limit) }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Phí bản quyền:</label>
                            <span class="detail-value fee-badge">{{ number_format($goInvoicePackage->license_fee, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giảm giá:</label>
                            <span class="detail-value discount-badge">{{ $goInvoicePackage->discount_percent }}%</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Thứ tự:</label>
                            <span class="detail-value">{{ $goInvoicePackage->order }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Trạng thái:</label>
                            <span class="detail-value">
                                @if($goInvoicePackage->status === 'active')
                                    <span class="status-badge active">Kích hoạt</span>
                                @else
                                    <span class="status-badge inactive">Tắt</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Ngày tạo:</label>
                            <span class="detail-value">{{ $goInvoicePackage->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Cập nhật lần cuối:</label>
                            <span class="detail-value">{{ $goInvoicePackage->updated_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                    </div>

                    @if($goInvoicePackage->description)
                    <div class="detail-section">
                        <div class="detail-item">
                            <label class="detail-label">Mô tả:</label>
                            <div class="detail-value">
                                <p>{{ $goInvoicePackage->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($goInvoicePackage->features && count($goInvoicePackage->features) > 0)
                    <div class="detail-section">
                        <div class="detail-item">
                            <label class="detail-label">Tính năng:</label>
                            <div class="detail-value">
                                <ul class="features-list">
                                    @foreach($goInvoicePackage->features as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="detail-section" style="margin-top: 30px;">
                    <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">
                        <i class="fas fa-shopping-cart"></i> Danh sách gói đã bán ({{ $purchases->total() }})
                    </h6>
                    
                    @if($purchases->isEmpty())
                        <div class="empty-state" style="padding: 40px; text-align: center;">
                            <i class="fas fa-shopping-cart" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                            <p class="text-muted">Chưa có gói nào được bán</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th class="column-small">STT</th>
                                        <th class="column-medium">Mã giao dịch</th>
                                        <th class="column-medium">Khách hàng</th>
                                        <th class="column-small text-center">Số tiền</th>
                                        <th class="column-small text-center">Phí bản quyền</th>
                                        <th class="column-small text-center">Giới hạn MST</th>
                                        <th class="column-small text-center">Loại</th>
                                        <th class="column-small text-center">Trạng thái</th>
                                        <th class="column-medium">Ngày tạo</th>
                                        <th class="column-small text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $index => $purchase)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($purchases->currentPage() - 1) * $purchases->perPage() + $index + 1 }}
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
                                            <td class="text-center">
                                                <span class="price-badge">{{ number_format($purchase->amount, 0, ',', '.') }} đ</span>
                                            </td>
                                            <td class="text-center">
                                                @if(isset($purchase->license_fee) && $purchase->license_fee)
                                                    <span class="fee-badge">{{ number_format($purchase->license_fee, 0, ',', '.') }} đ</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="limit-badge">{{ number_format($purchase->mst_limit ?? 0) }}</span>
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
                                                    <a href="{{ route('admin.purchases.show', ['toolType' => 'go-invoice', 'id' => $purchase->id]) }}"
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
                                Hiển thị {{ $purchases->firstItem() ?? 0 }} đến {{ $purchases->lastItem() ?? 0 }} của
                                {{ $purchases->total() }} gói đã bán
                            </div>
                            <div class="pagination-controls">
                                {{ $purchases->links('components.paginate') }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="detail-actions">
                    <a href="{{ route('admin.go-invoice-packages.index') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <a href="{{ route('admin.go-invoice-packages.edit', $goInvoicePackage) }}" class="action-button">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
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

        .fee-badge {
            background: #fff3e0;
            color: #e65100;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .discount-badge {
            background: #ffebee;
            color: #c62828;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-badge {
            background: #f3e5f5;
            color: #7b1fa2;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 11px;
            margin-left: 8px;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.active {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-badge.inactive {
            background: #ffebee;
            color: #c62828;
        }

        .status-badge.pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #856404;
        }

        .status-badge.cancelled {
            background-color: rgba(108, 117, 125, 0.2);
            color: #6c757d;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .features-list li {
            padding: 8px 12px;
            margin: 4px 0;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #D1A66E;
        }
    </style>
@endpush

