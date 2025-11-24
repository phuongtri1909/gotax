@extends('admin.layouts.sidebar')

@section('title', 'Quản lý cấu hình dùng thử')

@section('main-content')
    <div class="category-container">
        <!-- Breadcrumb -->
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Cấu hình dùng thử</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-cog icon-title"></i>
                    <h5>Cấu hình dùng thử</h5>
                </div>
            </div>

            <div class="card-content">
                @if ($trials->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h4>Chưa có cấu hình dùng thử nào</h4>
                        <p>Vui lòng chạy seeder để tạo cấu hình mặc định.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-medium">Tool</th>
                                    <th class="column-medium">MST Limit</th>
                                    <th class="column-medium">CCCD Limit</th>
                                    <th class="column-medium">Thời hạn (ngày)</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày tạo</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trials as $index => $trial)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $toolNames[$trial->tool_type] ?? $trial->tool_type }}</strong>
                                        </td>
                                        <td>
                                            @if($trial->mst_limit)
                                                <span class="badge bg-info">{{ $trial->mst_limit }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($trial->cccd_limit)
                                                <span class="badge bg-info">{{ $trial->cccd_limit }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($trial->expires_days)
                                                <span class="badge bg-success">{{ $trial->expires_days }} ngày</span>
                                            @else
                                                <span class="text-muted">Không giới hạn</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($trial->status)
                                                <span class="status-badge active">Kích hoạt</span>
                                            @else
                                                <span class="status-badge inactive">Tắt</span>
                                            @endif
                                        </td>
                                        <td class="category-date">{{ $trial->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.trials.show', $trial) }}"
                                                    class="action-icon view-icon text-decoration-none" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.trials.edit', $trial) }}"
                                                    class="action-icon edit-icon text-decoration-none" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

        .status-badge.inactive {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
    </style>
@endpush

