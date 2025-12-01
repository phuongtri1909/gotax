@extends('admin.layouts.sidebar')

@section('title', 'Quản lý người dùng')

@section('main-content')
    <div class="category-container">
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item current">Quản lý người dùng</li>
            </ol>
        </div>

        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-users icon-title"></i>
                    <h5>Quản lý người dùng</h5>
                </div>
            </div>

            <div class="card-content">
                <div class="filter-section" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Tìm kiếm:</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   value="{{ request('search') }}" 
                                   placeholder="Tên, email, số điện thoại...">
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label">Vai trò:</label>
                            <select name="role" id="role" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="active" class="form-label">Trạng thái:</label>
                            <select name="active" id="active" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Kích hoạt</option>
                                <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Tắt</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Tìm
                            </button>
                        </div>
                    </form>
                </div>

                @if ($users->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Chưa có người dùng nào</h4>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">STT</th>
                                    <th class="column-small text-center">Avatar</th>
                                    <th class="column-medium">Tên</th>
                                    <th class="column-medium">Email</th>
                                    <th class="column-medium">Số điện thoại</th>
                                    <th class="column-small text-center">Vai trò</th>
                                    <th class="column-small text-center">Trạng thái</th>
                                    <th class="column-medium">Ngày tạo</th>
                                    <th class="column-small text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                                        </td>
                                        <td class="text-center">
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default/avatar_default.jpg') }}"
                                                 alt="{{ $user->full_name ?? 'User' }}"
                                                 class="user-avatar-thumb"
                                                 onerror="this.src='{{ asset('images/default/avatar_default.jpg') }}'">
                                        </td>
                                        <td>
                                            <strong>{{ $user->full_name ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                {{ $user->email }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($user->phone)
                                                <a href="tel:{{ str_replace(' ', '', $user->phone) }}" class="text-decoration-none">
                                                    {{ $user->phone }}
                                                </a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($user->role === 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-primary">User</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($user->active == 1)
                                                <span class="status-badge active">Kích hoạt</span>
                                            @else
                                                <span class="status-badge inactive">Tắt</span>
                                            @endif
                                        </td>
                                        <td class="category-date">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="action-buttons-wrapper">
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                    class="action-icon view-icon text-decoration-none" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Hiển thị {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }} của
                            {{ $users->total() }} người dùng
                        </div>
                        <div class="pagination-controls">
                            {{ $users->links('components.paginate') }}
                        </div>
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

        .user-avatar-thumb {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #dee2e6;
        }
    </style>
@endpush

