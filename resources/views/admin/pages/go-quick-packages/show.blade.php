@extends('admin.layouts.sidebar')

@section('title', 'Chi tiết gói GoQuick')

@section('main-content')
    <div class="category-container">
        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-box icon-title"></i>
                    <h5>Chi tiết gói GoQuick</h5>
                </div>
                <div class="card-actions">
                    <a href="{{ route('admin.go-quick-packages.edit', $goQuickPackage) }}" class="action-button">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>

                    <a href="{{ route('admin.go-quick-packages.index') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-content">
                <div class="category-details">
                    <div class="detail-section">
                        <div class="detail-item">
                            <label class="detail-label">ID:</label>
                            <span class="detail-value">{{ $goQuickPackage->id }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Tên gói:</label>
                            <span class="detail-value">{{ $goQuickPackage->name }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Slug:</label>
                            <span class="detail-value">{{ $goQuickPackage->slug }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giá:</label>
                            <span class="detail-value price-badge">{{ number_format($goQuickPackage->price, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giới hạn CCCD:</label>
                            <span class="detail-value limit-badge">{{ number_format($goQuickPackage->cccd_limit) }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Giảm giá:</label>
                            <span class="detail-value discount-badge">{{ $goQuickPackage->discount_percent }}%</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Thứ tự:</label>
                            <span class="detail-value">{{ $goQuickPackage->order }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Trạng thái:</label>
                            <span class="detail-value">
                                @if($goQuickPackage->status === 'active')
                                    <span class="status-badge active">Kích hoạt</span>
                                @else
                                    <span class="status-badge inactive">Tắt</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Ngày tạo:</label>
                            <span class="detail-value">{{ $goQuickPackage->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Cập nhật lần cuối:</label>
                            <span class="detail-value">{{ $goQuickPackage->updated_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                    </div>

                    @if($goQuickPackage->description)
                    <div class="detail-section">
                        <div class="detail-item">
                            <label class="detail-label">Mô tả:</label>
                            <div class="detail-value">
                                <p>{{ $goQuickPackage->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="detail-actions">
                    <a href="{{ route('admin.go-quick-packages.index') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <a href="{{ route('admin.go-quick-packages.edit', $goQuickPackage) }}" class="action-button">
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

        .discount-badge {
            background: #ffebee;
            color: #c62828;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
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
    </style>
@endpush

