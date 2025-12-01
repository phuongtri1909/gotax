@extends('admin.layouts.sidebar')

@section('title', 'Quản lý cấu hình giảm giá nâng cấp')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Cấu hình giảm giá nâng cấp</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-percent icon-title"></i>
                    <h5>Cấu hình giảm giá nâng cấp</h5>
                </div>
            </div>

            <div class="card-content">
                @if ($configs->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <h4>Chưa có cấu hình giảm giá nào</h4>
                        <p>Vui lòng chạy seeder để tạo cấu hình mặc định.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-medium">Tool</th>
                                    <th class="column-small text-center">Lần 1 (tháng đầu)</th>
                                    <th class="column-small text-center">Lần 2 (tháng đầu)</th>
                                    <th class="column-small text-center">Lần 3-4-5 (tháng đầu)</th>
                                    <th class="column-small text-center">Sau tháng đầu</th>
                                    <th class="column-small text-center">Gia hạn (hết hạn)</th>
                                    <th class="column-small text-center">Cross-product</th>
                                    <th class="column-small text-center">Lần đầu mua</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày tạo</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($configs as $index => $config)
                                    @php
                                        $isTimeBased = in_array($config->tool_type, [
                                            \App\Models\PackageUpgradeConfig::TOOL_TYPE_GO_INVOICE,
                                            \App\Models\PackageUpgradeConfig::TOOL_TYPE_GO_SOFT
                                        ]);
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $toolNames[$config->tool_type] ?? $config->tool_type }}</strong>
                                        </td>
                                        <td class="text-center">
                                            @if($isTimeBased && $config->first_upgrade_discount_first_month)
                                                <span class="badge bg-primary">{{ number_format($config->first_upgrade_discount_first_month, 2) }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($isTimeBased && $config->second_upgrade_discount_first_month)
                                                <span class="badge bg-primary">{{ number_format($config->second_upgrade_discount_first_month, 2) }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($isTimeBased && $config->subsequent_upgrade_discount_first_month)
                                                <span class="badge bg-primary">{{ number_format($config->subsequent_upgrade_discount_first_month, 2) }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($isTimeBased && $config->upgrade_discount_after_first_month)
                                                <span class="badge bg-info">{{ number_format($config->upgrade_discount_after_first_month, 2) }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($isTimeBased && $config->renewal_discount_after_expired)
                                                <span class="badge bg-warning">{{ number_format($config->renewal_discount_after_expired, 2) }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($config->cross_product_discount)
                                                <span class="badge bg-success">{{ number_format($config->cross_product_discount, 2) }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($config->first_purchase_discount)
                                                <span class="badge bg-success">{{ number_format($config->first_purchase_discount, 2) }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($config->status === 'active')
                                                <span class="status-badge active">Kích hoạt</span>
                                            @else
                                                <span class="status-badge inactive">Tắt</span>
                                            @endif
                                        </td>
                                        <td class="category-date">{{ $config->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.package-upgrade-configs.show', $config) }}"
                                                    class="action-icon view-icon text-decoration-none" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.package-upgrade-configs.edit', $config) }}"
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

