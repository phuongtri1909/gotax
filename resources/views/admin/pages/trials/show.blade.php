@extends('admin.layouts.sidebar')

@section('title', 'Chi tiết cấu hình dùng thử')

@section('main-content')
    <div class="category-container">
        <!-- Breadcrumb -->
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.trials.index') }}">Cấu hình dùng thử</a></li>
                <li class="breadcrumb-item current">Chi tiết</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-cog icon-title"></i>
                    <h5>Chi tiết cấu hình dùng thử</h5>
                </div>
                <div class="card-actions">
                    <a href="{{ route('admin.trials.edit', $trial) }}" class="action-button">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.trials.index') }}" class="action-button">
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
                                <span class="badge bg-primary">{{ $toolNames[$trial->tool_type] ?? $trial->tool_type }}</span>
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">MST Limit:</label>
                            <span class="detail-value">
                                @if($trial->mst_limit)
                                    <span class="badge bg-info">{{ $trial->mst_limit }}</span>
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">CCCD Limit:</label>
                            <span class="detail-value">
                                @if($trial->cccd_limit)
                                    <span class="badge bg-info">{{ $trial->cccd_limit }}</span>
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Thời hạn (ngày):</label>
                            <span class="detail-value">
                                @if($trial->expires_days)
                                    <span class="badge bg-success">{{ $trial->expires_days }} ngày</span>
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Trạng thái:</label>
                            <span class="detail-value">
                                @if ($trial->status)
                                    <span class="status-badge active">Kích hoạt</span>
                                @else
                                    <span class="status-badge inactive">Tắt</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Mô tả:</label>
                            <div class="detail-value">
                                @if($trial->description)
                                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary-color); white-space: pre-wrap;">
                                        {{ $trial->description }}
                                    </div>
                                @else
                                    <span class="text-muted">Chưa có mô tả</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Ngày tạo:</label>
                            <span class="detail-value">{{ $trial->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        @if ($trial->updated_at != $trial->created_at)
                        <div class="detail-item">
                            <label class="detail-label">Cập nhật lần cuối:</label>
                            <span class="detail-value">{{ $trial->updated_at->format('d/m/Y H:i:s') }}</span>
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

