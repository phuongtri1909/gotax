@extends('admin.layouts.sidebar')

@section('title', 'Quản lý gói GoBot')

@section('main-content')
    <div class="category-container">
        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-box icon-title"></i>
                    <h5>Danh sách gói GoBot</h5>
                </div>
                <a href="{{ route('admin.go-bot-packages.create') }}" class="action-button">
                    <i class="fas fa-plus"></i> Thêm gói
                </a>
            </div>

            <div class="card-content">
                @if ($packages->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <h4>Chưa có gói nào</h4>
                        <p>Bắt đầu bằng cách thêm gói đầu tiên.</p>
                        <a href="{{ route('admin.go-bot-packages.create') }}" class="action-button">
                            <i class="fas fa-plus"></i> Thêm gói mới
                        </a>
                    </div>
                @else
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-large">Tên gói</th>
                                    <th class="column-medium">Giá</th>
                                    <th class="column-medium">Giới hạn MST</th>
                                    <th class="column-medium">Giảm giá (%)</th>
                                    <th class="column-medium">Thứ tự</th>
                                    <th class="column-medium">Trạng thái</th>
                                    <th class="column-medium">Ngày tạo</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($packages as $index => $package)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($packages->currentPage() - 1) * $packages->perPage() + $index + 1 }}
                                        </td>
                                        <td class="item-title">
                                            <strong>{{ $package->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="price-badge">{{ number_format($package->price, 0, ',', '.') }} đ</span>
                                        </td>
                                        <td>
                                            <span class="limit-badge">{{ number_format($package->mst_limit) }}</span>
                                        </td>
                                        <td>
                                            @if($package->discount_percent > 0)
                                                <span class="discount-badge">{{ $package->discount_percent }}%</span>
                                            @else
                                                <span class="text-muted">0%</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $package->order }}
                                        </td>
                                        <td>
                                            @if($package->status === 'active')
                                                <span class="status-badge active">Kích hoạt</span>
                                            @else
                                                <span class="status-badge inactive">Tắt</span>
                                            @endif
                                        </td>
                                        <td class="package-date">
                                            {{ $package->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.go-bot-packages.show', $package) }}"
                                                    class="action-icon view-icon text-decoration-none" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('admin.go-bot-packages.edit', $package) }}"
                                                    class="action-icon edit-icon text-decoration-none" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @include('components.delete-form', [
                                                    'id' => $package->id,
                                                    'route' => route('admin.go-bot-packages.destroy', $package),
                                                    'message' => "Bạn có chắc chắn muốn xóa gói '{$package->name}'?",
                                                ])
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Hiển thị {{ $packages->firstItem() ?? 0 }} đến {{ $packages->lastItem() ?? 0 }} của
                            {{ $packages->total() }} gói
                        </div>
                        <div class="pagination-controls">
                            {{ $packages->links('components.paginate') }}
                        </div>
                    </div>
                @endif
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

        .package-date {
            font-size: 14px;
            color: #6c757d;
        }
    </style>
@endpush

