@extends('admin.layouts.sidebar')

@section('title', 'Quản lý tài liệu')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Tài liệu</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-book icon-title"></i>
                    <h5>Danh sách tài liệu</h5>
                </div>
                <a href="{{ route('admin.documents.create') }}" class="action-button">
                    <i class="fas fa-plus"></i> Thêm tài liệu
                </a>
            </div>

            <div class="filter-section">
                <form action="{{ route('admin.documents.index') }}" method="GET" class="filter-form">
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
                        <a href="{{ route('admin.documents.index') }}" class="filter-clear-btn">
                            <i class="fas fa-times"></i> Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-content">
                @if ($documents->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        @if (request()->hasAny(['search', 'status']))
                            <h4>Không tìm thấy tài liệu nào</h4>
                            <p>Không có tài liệu nào phù hợp với bộ lọc hiện tại.</p>
                            <a href="{{ route('admin.documents.index') }}" class="action-button">
                                <i class="fas fa-times"></i> Xóa bộ lọc
                            </a>
                        @else
                            <h4>Chưa có tài liệu nào</h4>
                            <p>Bắt đầu bằng cách thêm tài liệu đầu tiên.</p>
                            <a href="{{ route('admin.documents.create') }}" class="action-button">
                                <i class="fas fa-plus"></i> Thêm tài liệu mới
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
                                @foreach ($documents as $index => $document)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($documents->currentPage() - 1) * $documents->perPage() + $index + 1 }}
                                        </td>
                                        <td class="item-title">
                                            <strong>{{ Str::limit($document->title, 100) }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ Str::limit(strip_tags(html_entity_decode($document->content ?? '', ENT_QUOTES, 'UTF-8')), 150) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="order-badge">{{ $document->order }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge {{ $document->status === 'active' ? 'active' : 'inactive' }}">
                                                {{ $document->status === 'active' ? 'Kích hoạt' : 'Tắt' }}
                                            </span>
                                        </td>
                                        <td class="category-date">{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.documents.edit', $document) }}"
                                                    class="action-icon edit-icon text-decoration-none" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @include('components.delete-form', [
                                                    'id' => $document->id,
                                                    'route' => route('admin.documents.destroy', $document),
                                                    'message' => "Bạn có chắc chắn muốn xóa tài liệu '{$document->title}'?",
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
                            Hiển thị {{ $documents->firstItem() ?? 0 }} đến {{ $documents->lastItem() ?? 0 }} của
                            {{ $documents->total() }} tài liệu
                        </div>
                        <div class="pagination-controls">
                            {{ $documents->appends(request()->query())->links('components.paginate') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

