@extends('admin.layouts.sidebar')

@section('title', 'Quản lý đăng ký dùng thử')

@section('main-content')
    <div class="category-container">
        <!-- Breadcrumb -->
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Danh sách đăng ký dùng thử</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-user-check icon-title"></i>
                    <h5>Danh sách đăng ký dùng thử</h5>
                </div>
            </div>

            <div class="filter-section">
                <form action="{{ route('admin.trial-registrations.index') }}" method="GET" class="filter-form">
                    <div class="row">
                        <div class="col-4">
                            <label for="search">Tìm kiếm</label>
                            <input type="text" id="search" name="search" class="filter-input"
                                placeholder="Tìm theo tên, email" value="{{ request('search') }}">
                        </div>
                        <div class="col-4">
                            <label for="tool_type">Tool</label>
                            <select id="tool_type" name="tool_type" class="filter-input">
                                <option value="">-- Tất cả --</option>
                                @foreach($toolTypes as $key => $name)
                                    <option value="{{ $key }}" {{ request('tool_type') === $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="is_read_filter">Trạng thái đọc</label>
                            <select id="is_read_filter" name="is_read" class="filter-input">
                                <option value="">-- Tất cả --</option>
                                <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>Đã đọc</option>
                                <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>Chưa đọc</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                        <a href="{{ route('admin.trial-registrations.index') }}" class="filter-clear-btn">
                            <i class="fas fa-times"></i> Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-content">
                @if ($registrations->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        @if (request()->hasAny(['search', 'tool_type', 'is_read']))
                            <h4>Không tìm thấy đăng ký nào</h4>
                            <p>Không có đăng ký nào phù hợp với bộ lọc hiện tại.</p>
                            <a href="{{ route('admin.trial-registrations.index') }}" class="action-button">
                                <i class="fas fa-times"></i> Xóa bộ lọc
                            </a>
                        @else
                            <h4>Chưa có đăng ký dùng thử nào</h4>
                            <p>Chưa có ai đăng ký dùng thử.</p>
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-medium">Họ và tên</th>
                                    <th class="column-medium">Email</th>
                                    <th class="column-medium">Tool</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày đăng ký</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registrations as $index => $registration)
                                    <tr class="{{ !$registration->is_read ? 'unread-row' : '' }}">
                                        <td class="text-center">
                                            {{ ($registrations->currentPage() - 1) * $registrations->perPage() + $index + 1 }}
                                        </td>
                                        <td>
                                            <strong>{{ $registration->user->full_name ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $registration->user->email }}" class="text-decoration-none">
                                                {{ $registration->user->email }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $toolTypes[$registration->tool_type] ?? $registration->tool_type }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($registration->is_read)
                                                <span class="status-badge active">Đã đọc</span>
                                            @else
                                                <span class="status-badge inactive">Chưa đọc</span>
                                            @endif
                                        </td>
                                        <td class="category-date">{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.trial-registrations.show', $registration) }}"
                                                    class="action-icon view-icon text-decoration-none" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @include('components.delete-form', [
                                                    'id' => $registration->id,
                                                    'route' => route('admin.trial-registrations.destroy', $registration),
                                                    'message' => "Bạn có chắc chắn muốn xóa đăng ký dùng thử này?",
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
                            Hiển thị {{ $registrations->firstItem() ?? 0 }} đến {{ $registrations->lastItem() ?? 0 }} của
                            {{ $registrations->total() }} đăng ký
                        </div>
                        <div class="pagination-controls">
                            {{ $registrations->appends(request()->query())->links('components.paginate') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .unread-row {
            background-color: #f0f8ff;
            font-weight: 500;
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

