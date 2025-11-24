@extends('admin.layouts.sidebar')

@section('title', 'Quản lý liên hệ')

@section('main-content')
    <div class="category-container">
        <!-- Breadcrumb -->
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Danh sách liên hệ</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-inbox icon-title"></i>
                    <h5>Danh sách liên hệ</h5>
                </div>
            </div>

            <div class="filter-section">
                <form action="{{ route('admin.contacts.index') }}" method="GET" class="filter-form">
                    <div class="row">
                        <div class="col-6">
                            <label for="search">Tìm kiếm</label>
                            <input type="text" id="search" name="search" class="filter-input"
                                placeholder="Tìm theo tên, email, số điện thoại" value="{{ request('search') }}">
                        </div>
                        <div class="col-6">
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
                        <a href="{{ route('admin.contacts.index') }}" class="filter-clear-btn">
                            <i class="fas fa-times"></i> Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-content">
                @if ($contacts->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        @if (request()->hasAny(['search', 'is_read']))
                            <h4>Không tìm thấy liên hệ nào</h4>
                            <p>Không có liên hệ nào phù hợp với bộ lọc hiện tại.</p>
                            <a href="{{ route('admin.contacts.index') }}" class="action-button">
                                <i class="fas fa-times"></i> Xóa bộ lọc
                            </a>
                        @else
                            <h4>Chưa có liên hệ nào</h4>
                            <p>Chưa có ai liên hệ với bạn.</p>
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
                                    <th class="column-medium">Số điện thoại</th>
                                    <th class="column-large">Dịch vụ cần tư vấn</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày gửi</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $index => $contact)
                                    <tr class="{{ !$contact->is_read ? 'unread-row' : '' }}">
                                        <td class="text-center">
                                            {{ ($contacts->currentPage() - 1) * $contacts->perPage() + $index + 1 }}
                                        </td>
                                        <td>
                                            <strong>{{ $contact->name }}</strong>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                                {{ $contact->email }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="tel:{{ str_replace(' ', '', $contact->phone) }}" class="text-decoration-none">
                                                {{ $contact->phone }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ Str::limit($contact->service, 100) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($contact->is_read)
                                                <span class="status-badge active">Đã đọc</span>
                                            @else
                                                <span class="status-badge inactive">Chưa đọc</span>
                                            @endif
                                        </td>
                                        <td class="category-date">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.contacts.show', $contact) }}"
                                                    class="action-icon view-icon text-decoration-none" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @include('components.delete-form', [
                                                    'id' => $contact->id,
                                                    'route' => route('admin.contacts.destroy', $contact),
                                                    'message' => "Bạn có chắc chắn muốn xóa liên hệ này?",
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
                            Hiển thị {{ $contacts->firstItem() ?? 0 }} đến {{ $contacts->lastItem() ?? 0 }} của
                            {{ $contacts->total() }} liên hệ
                        </div>
                        <div class="pagination-controls">
                            {{ $contacts->appends(request()->query())->links('components.paginate') }}
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

