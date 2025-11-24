@extends('admin.layouts.sidebar')

@section('title', 'Quản lý đánh giá khách hàng')

@section('main-content')
    <div class="category-container">
        <!-- Breadcrumb -->
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Đánh giá khách hàng</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-star icon-title"></i>
                    <h5>Danh sách đánh giá khách hàng</h5>
                </div>
                <a href="{{ route('admin.testimonials.create') }}" class="action-button">
                    <i class="fas fa-plus"></i> Thêm đánh giá
                </a>
            </div>

            <div class="filter-section">
                <form action="{{ route('admin.testimonials.index') }}" method="GET" class="filter-form">
                    <div class="row">
                        <div class="col-6">
                            <label for="search">Tìm kiếm</label>
                            <input type="text" id="search" name="search" class="filter-input"
                                placeholder="Tìm theo tên hoặc nội dung" value="{{ request('search') }}">
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
                        <a href="{{ route('admin.testimonials.index') }}" class="filter-clear-btn">
                            <i class="fas fa-times"></i> Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-content">
                @if ($testimonials->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        @if (request()->hasAny(['search', 'status']))
                            <h4>Không tìm thấy đánh giá nào</h4>
                            <p>Không có đánh giá nào phù hợp với bộ lọc hiện tại.</p>
                            <a href="{{ route('admin.testimonials.index') }}" class="action-button">
                                <i class="fas fa-times"></i> Xóa bộ lọc
                            </a>
                        @else
                            <h4>Chưa có đánh giá nào</h4>
                            <p>Bắt đầu bằng cách thêm đánh giá đầu tiên.</p>
                            <a href="{{ route('admin.testimonials.create') }}" class="action-button">
                                <i class="fas fa-plus"></i> Thêm đánh giá mới
                            </a>
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-small">Ảnh</th>
                                    <th class="column-medium">Tên khách hàng</th>
                                    <th class="column-large">Nội dung</th>
                                    <th class="column-small text-center">Đánh giá</th>
                                    <th class="column-small text-center">Thứ tự</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày tạo</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testimonials as $index => $testimonial)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($testimonials->currentPage() - 1) * $testimonials->perPage() + $index + 1 }}
                                        </td>
                                        <td class="text-center">
                                            <img src="{{ $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : asset('images/default/avatar_default.jpg') }}"
                                                alt="{{ $testimonial->name }}" class="testimonial-avatar-thumb">
                                        </td>
                                        <td>
                                            <strong>{{ $testimonial->name }}</strong>
                                        </td>
                                        <td class="item-title">
                                            <span class="text-muted">{{ Str::limit($testimonial->text, 150) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="testimonial-rating-display">
                                                @for ($i = 0; $i < $testimonial->rating; $i++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="order-badge">{{ $testimonial->order }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge {{ $testimonial->status ? 'active' : 'inactive' }}">
                                                {{ $testimonial->status ? 'Kích hoạt' : 'Tắt' }}
                                            </span>
                                        </td>
                                        <td class="category-date">{{ $testimonial->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                                    class="action-icon edit-icon text-decoration-none" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @include('components.delete-form', [
                                                    'id' => $testimonial->id,
                                                    'route' => route('admin.testimonials.destroy', $testimonial),
                                                    'message' => "Bạn có chắc chắn muốn xóa đánh giá này?",
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
                            Hiển thị {{ $testimonials->firstItem() ?? 0 }} đến {{ $testimonials->lastItem() ?? 0 }} của
                            {{ $testimonials->total() }} đánh giá
                        </div>
                        <div class="pagination-controls">
                            {{ $testimonials->appends(request()->query())->links('components.paginate') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .testimonial-avatar-thumb {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .testimonial-rating-display {
            display: inline-flex;
            gap: 2px;
        }

        .testimonial-rating-display i {
            font-size: 14px;
        }

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

