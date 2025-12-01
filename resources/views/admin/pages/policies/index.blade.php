@extends('admin.layouts.sidebar')

@section('title', 'Quản lý chính sách')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Chính sách</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-shield-alt icon-title"></i>
                    <h5>Danh sách chính sách</h5>
                </div>
                <a href="{{ route('admin.policies.create') }}" class="action-button">
                    <i class="fas fa-plus"></i> Thêm chính sách
                </a>
            </div>

            <div class="filter-section">
                <form action="{{ route('admin.policies.index') }}" method="GET" class="filter-form">
                    <div class="row">
                        <div class="col-6">
                            <label for="search">Tìm kiếm</label>
                            <input type="text" id="search" name="search" class="filter-input"
                                placeholder="Tìm theo tiêu đề" value="{{ request('search') }}">
                        </div>
                        <div class="col-6">
                            <label for="status_filter">Trạng thái</label>
                            <select id="status_filter" name="status" class="filter-input">
                                <option value="">-- Tất cả --</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Kích hoạt</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tắt</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                        <a href="{{ route('admin.policies.index') }}" class="filter-clear-btn">
                            <i class="fas fa-times"></i> Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-content">
                @if ($policies->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        @if (request()->hasAny(['search', 'status']))
                            <h4>Không tìm thấy chính sách nào</h4>
                            <p>Không có chính sách nào phù hợp với bộ lọc hiện tại.</p>
                            <a href="{{ route('admin.policies.index') }}" class="action-button">
                                <i class="fas fa-times"></i> Xóa bộ lọc
                            </a>
                        @else
                            <h4>Chưa có chính sách nào</h4>
                            <p>Bắt đầu bằng cách thêm chính sách đầu tiên.</p>
                            <a href="{{ route('admin.policies.create') }}" class="action-button">
                                <i class="fas fa-plus"></i> Thêm chính sách mới
                            </a>
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-large">Tiêu đề</th>
                                    <th class="column-medium">Nội dung</th>
                                    <th class="column-small text-center">Thứ tự</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policies as $index => $policy)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($policies->currentPage() - 1) * $policies->perPage() + $index + 1 }}
                                        </td>
                                        <td class="item-title">
                                            <strong>{{ Str::limit($policy->title, 100) }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ Str::limit(strip_tags(html_entity_decode($policy->content ?? '', ENT_QUOTES, 'UTF-8')), 150) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="order-badge">{{ $policy->order }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge {{ $policy->status === 'active' ? 'active' : 'inactive' }}">
                                                {{ $policy->status === 'active' ? 'Kích hoạt' : 'Tắt' }}
                                            </span>
                                        </td>
                                        <td class="category-date">{{ $policy->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.policies.edit', $policy) }}"
                                                    class="action-icon edit-icon text-decoration-none" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @include('components.delete-form', [
                                                    'id' => $policy->id,
                                                    'route' => route('admin.policies.destroy', $policy),
                                                    'message' => "Bạn có chắc chắn muốn xóa chính sách '{$policy->title}'?",
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
                            Hiển thị {{ $policies->firstItem() ?? 0 }} đến {{ $policies->lastItem() ?? 0 }} của
                            {{ $policies->total() }} chính sách
                        </div>
                        <div class="pagination-controls">
                            {{ $policies->appends(request()->query())->links('components.paginate') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
