@extends('admin.layouts.sidebar')

@section('title', 'Quản lý câu hỏi thường gặp')

@section('main-content')
    <div class="category-container">
        <!-- Breadcrumb -->
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Câu hỏi thường gặp</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-question-circle icon-title"></i>
                    <h5>Danh sách câu hỏi thường gặp</h5>
                </div>
                <a href="{{ route('admin.faqs.create') }}" class="action-button">
                    <i class="fas fa-plus"></i> Thêm câu hỏi
                </a>
            </div>

            <div class="filter-section">
                <form action="{{ route('admin.faqs.index') }}" method="GET" class="filter-form">
                    <div class="row">
                        <div class="col-6">
                            <label for="search">Tìm kiếm</label>
                            <input type="text" id="search" name="search" class="filter-input"
                                placeholder="Tìm theo câu hỏi" value="{{ request('search') }}">
                        </div>
                        <div class="col-6">
                            <label for="status_filter">Trạng thái</label>
                            <select id="status_filter" name="status" class="filter-input">
                                <option value="">-- Tất cả --</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Kích hoạt</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tắt</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                        <a href="{{ route('admin.faqs.index') }}" class="filter-clear-btn">
                            <i class="fas fa-times"></i> Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-content">
                @if ($faqs->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        @if (request()->hasAny(['search', 'status']))
                            <h4>Không tìm thấy câu hỏi nào</h4>
                            <p>Không có câu hỏi nào phù hợp với bộ lọc hiện tại.</p>
                            <a href="{{ route('admin.faqs.index') }}" class="action-button">
                                <i class="fas fa-times"></i> Xóa bộ lọc
                            </a>
                        @else
                            <h4>Chưa có câu hỏi nào</h4>
                            <p>Bắt đầu bằng cách thêm câu hỏi đầu tiên.</p>
                            <a href="{{ route('admin.faqs.create') }}" class="action-button">
                                <i class="fas fa-plus"></i> Thêm câu hỏi mới
                            </a>
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-large">Câu hỏi</th>
                                    <th class="column-medium">Câu trả lời</th>
                                    <th class="column-small text-center">Thứ tự</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày tạo</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($faqs as $index => $faq)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($faqs->currentPage() - 1) * $faqs->perPage() + $index + 1 }}
                                        </td>
                                        <td class="item-title">
                                            <strong>{{ Str::limit($faq->question, 100) }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ Str::limit($faq->answer, 150) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="order-badge">{{ $faq->order }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge {{ $faq->status ? 'active' : 'inactive' }}">
                                                {{ $faq->status ? 'Kích hoạt' : 'Tắt' }}
                                            </span>
                                        </td>
                                        <td class="category-date">{{ $faq->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.faqs.edit', $faq) }}"
                                                    class="action-icon edit-icon text-decoration-none" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @include('components.delete-form', [
                                                    'id' => $faq->id,
                                                    'route' => route('admin.faqs.destroy', $faq),
                                                    'message' => "Bạn có chắc chắn muốn xóa câu hỏi này?",
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
                            Hiển thị {{ $faqs->firstItem() ?? 0 }} đến {{ $faqs->lastItem() ?? 0 }} của
                            {{ $faqs->total() }} câu hỏi
                        </div>
                        <div class="pagination-controls">
                            {{ $faqs->appends(request()->query())->links('components.paginate') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .order-badge {
            background: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
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

        .item-title {
            word-break: break-word;
            line-height: 1.4;
        }
    </style>
@endpush

