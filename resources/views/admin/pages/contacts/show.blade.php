@extends('admin.layouts.sidebar')

@section('title', 'Chi tiết liên hệ')

@section('main-content')
    <div class="category-container">
        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-inbox icon-title"></i>
                    <h5>Chi tiết liên hệ</h5>
                </div>
                <div class="card-actions">
                    @if (!$contact->is_read)
                        <form method="POST" action="{{ route('admin.contacts.mark-read', $contact) }}" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="action-button">
                                <i class="fas fa-check"></i> Đánh dấu đã đọc
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.contacts.index') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-content">
                <div class="category-details">
                    <div class="detail-section">
                        <div class="detail-item">
                            <label class="detail-label">Họ và tên:</label>
                            <span class="detail-value">{{ $contact->name }}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Email:</label>
                            <span class="detail-value">
                                <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                    {{ $contact->email }}
                                </a>
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Số điện thoại:</label>
                            <span class="detail-value">
                                <a href="tel:{{ str_replace(' ', '', $contact->phone) }}" class="text-decoration-none">
                                    {{ $contact->phone }}
                                </a>
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Dịch vụ cần tư vấn:</label>
                            <div class="detail-value">
                                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary-color);">
                                    {{ $contact->service }}
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Trạng thái:</label>
                            <span class="detail-value">
                                @if ($contact->is_read)
                                    <span class="status-badge active">Đã đọc</span>
                                @else
                                    <span class="status-badge inactive">Chưa đọc</span>
                                @endif
                            </span>
                        </div>
                        @if ($contact->ip_address)
                        <div class="detail-item">
                            <label class="detail-label">IP Address:</label>
                            <span class="detail-value">{{ $contact->ip_address }}</span>
                        </div>
                        @endif
                        <div class="detail-item">
                            <label class="detail-label">Ngày gửi:</label>
                            <span class="detail-value">{{ $contact->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        @if ($contact->updated_at != $contact->created_at)
                        <div class="detail-item">
                            <label class="detail-label">Cập nhật lần cuối:</label>
                            <span class="detail-value">{{ $contact->updated_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .category-details {
            padding: 20px;
        }

        .detail-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .detail-item {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 180px;
            margin-right: 15px;
        }

        .detail-value {
            color: #333;
            flex: 1;
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

        @media (max-width: 768px) {
            .detail-item {
                flex-direction: column;
                gap: 5px;
            }

            .detail-label {
                min-width: auto;
                margin-right: 0;
            }
        }
    </style>
@endpush

