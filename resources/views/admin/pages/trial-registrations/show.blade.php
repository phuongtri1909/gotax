@extends('admin.layouts.sidebar')

@section('title', 'Chi tiết đăng ký dùng thử')

@section('main-content')
    <div class="category-container">
        <!-- Breadcrumb -->
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.trial-registrations.index') }}">Danh sách đăng ký dùng thử</a></li>
                <li class="breadcrumb-item current">Chi tiết</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-user-check icon-title"></i>
                    <h5>Chi tiết đăng ký dùng thử</h5>
                </div>
                <div class="card-actions">
                    @if (!$trialRegistration->is_read)
                        <form action="{{ route('admin.trial-registrations.mark-read', $trialRegistration) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="action-button">
                                <i class="fas fa-check"></i> Đánh dấu đã đọc
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.trial-registrations.index') }}" class="action-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-content">
                <div class="detail-section">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Họ và tên:</label>
                                <div class="detail-value">{{ $trialRegistration->user->full_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Email:</label>
                                <div class="detail-value">
                                    <a href="mailto:{{ $trialRegistration->user->email }}" class="text-decoration-none">
                                        {{ $trialRegistration->user->email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Số điện thoại:</label>
                                <div class="detail-value">
                                    @if($trialRegistration->user->phone)
                                        <a href="tel:{{ str_replace(' ', '', $trialRegistration->user->phone) }}" class="text-decoration-none">
                                            {{ $trialRegistration->user->phone }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Tool:</label>
                                <div class="detail-value">
                                    <span class="badge bg-primary">{{ $toolNames[$trialRegistration->tool_type] ?? $trialRegistration->tool_type }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Trạng thái:</label>
                                <div class="detail-value">
                                    @if ($trialRegistration->is_read)
                                        <span class="status-badge active">Đã đọc</span>
                                    @else
                                        <span class="status-badge inactive">Chưa đọc</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Ngày đăng ký:</label>
                                <div class="detail-value">{{ $trialRegistration->created_at->format('d/m/Y H:i:s') }}</div>
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
        .detail-section {
            padding: 20px 0;
        }

        .detail-item {
            margin-bottom: 20px;
        }

        .detail-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .detail-value {
            color: #666;
            font-size: 15px;
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
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            font-weight: 600;
        }
    </style>
@endpush

