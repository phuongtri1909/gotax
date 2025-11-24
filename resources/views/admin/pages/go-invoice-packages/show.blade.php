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

